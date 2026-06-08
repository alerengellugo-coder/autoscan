<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Models\Enums\QuotationStatus;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\QuotationApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    private function statusOptions(): array
    {
        return collect(QuotationStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->toArray();
    }

    public function index(Request $request)
    {
        $query = Quotation::query()->with(['client', 'vehicle']);
        if ($request->filled('status')) $query->where('status', $request->input('status'));
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where('quotation_number', 'like', "%{$s}%")->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$s}%"));
        }
        $quotations = $query->orderByDesc('created_at')->paginate($request->input('per_page', 15))->withQueryString();

        return view('quotations.index', [
            'quotations'    => $quotations,
            'status_options' => $this->statusOptions(),
            'filters'       => $request->only('search', 'status', 'per_page'),
        ]);
    }

    public function clientQuotations(Request $request)
    {
        $query = Quotation::query()->with(['client', 'vehicle'])->where('client_id', Auth::id());
        if ($request->filled('status')) $query->where('status', $request->input('status'));
        $quotations = $query->orderByDesc('created_at')->paginate($request->input('per_page', 10))->withQueryString();

        return view('client.quotations.index', [
            'quotations'    => $quotations,
            'status_options' => $this->statusOptions(),
            'filters'       => $request->only('status', 'per_page'),
        ]);
    }

    public function create(Request $request)
    {
        return view('quotations.create', [
            'clients'  => User::clients()->active()->orderBy('name')->get(['id', 'name']),
            'vehicles' => Vehicle::active()->orderBy('brand')->get(['id', 'brand', 'model', 'plate', 'client_id']),
            'products' => Product::active()->orderBy('name')->get(['id', 'name', 'price', 'stock_quantity', 'min_stock_alert']),
            'service_orders' => ServiceOrder::where('client_id', $request->input('client_id'))->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'    => ['required', 'exists:users,id'],
            'vehicle_id'   => ['nullable', 'exists:vehicles,id'],
            'service_order_id' => ['nullable', 'exists:service_orders,id'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'tax_rate'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount'      => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'in:percentage,fixed'],
            'valid_until'   => ['nullable', 'date'],
            'notes'         => ['nullable', 'string', 'max:1000'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.product_id'    => ['nullable', 'exists:products,id'],
            'items.*.description'  => ['required', 'string', 'max:500'],
            'items.*.quantity'      => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'    => ['required', 'numeric', 'min:0'],
            'items.*.discount'      => ['nullable', 'numeric', 'min:0'],
        ]);

        $quotation = DB::transaction(function () use ($validated) {
            $items = $validated['items'];
            unset($validated['items']);

            $validated['status'] = QuotationStatus::Draft->value;
            // number is auto-generated in model boot()

            $subtotal = collect($items)->sum(fn ($item) => $item['quantity'] * $item['unit_price']);
            $taxRate = $validated['tax_rate'] ?? 0;
            $tax = $subtotal * ($taxRate / 100);
            $validated['subtotal'] = $subtotal;
            $validated['tax'] = $tax;
            $validated['total'] = $subtotal + $tax - ($validated['discount'] ?? 0);

            $quotation = Quotation::create($validated);

            foreach ($items as $item) {
                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount'   => $item['discount'] ?? null,
                    'total'      => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $quotation;
        });

        return redirect()->route('admin.cotizaciones.show', $quotation)->with('success', "Cotización {$quotation->quotation_number} creada.");
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['client', 'vehicle', 'items', 'serviceOrder']);
        $user = Auth::user();
        $page = $user->isAdmin() ? 'quotations.show' : 'client.quotations.show';

        return view($page, [
            'quotation'     => $quotation,
            'status_options' => $this->statusOptions(),
        ]);
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate(['status' => 'required|string']);
        $newStatus = QuotationStatus::from($validated['status']);
        $oldStatus = $quotation->status;
        $quotation->update(['status' => $newStatus->value]);

        // Notify admin when client approves a quotation
        if ($newStatus === QuotationStatus::Approved && $oldStatus !== QuotationStatus::Approved) {
            // Notify all admins
            $admins = User::admins()->active()->get();
            foreach ($admins as $admin) {
                $admin->notify(new QuotationApproved($quotation));
            }
        }

        return back()->with('success', 'Estado actualizado.');
    }

    public function generatePdf(Quotation $quotation) { return response()->json(['message' => 'PDF generation not implemented yet']); }

    public function convertToSale(Request $request, Quotation $quotation)
    {
        if ($quotation->status !== QuotationStatus::Approved->value) {
            return back()->withErrors(['error' => 'Solo se pueden convertir cotizaciones aprobadas.']);
        }

        $sale = DB::transaction(function () use ($quotation) {
            $sale = \App\Models\Sale::create([
                'client_id'      => $quotation->client_id,
                'quotation_id'   => $quotation->id,
                'description'    => $quotation->description,
                'subtotal'       => $quotation->subtotal,
                'tax_rate'       => $quotation->tax_rate,
                'tax'            => $quotation->tax,
                'discount'       => $quotation->discount,
                'discount_type'  => $quotation->discount_type,
                'total'          => $quotation->total,
                'status'         => \App\Models\Enums\SaleStatus::Pending->value,
                'paid_amount'    => 0,
            ]);

            foreach ($quotation->items as $item) {
                $sale->items()->create([
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount'   => $item->discount,
                    'total'      => $item->total,
                ]);
            }

            return $sale;
        });

        return redirect()->route('admin.ventas.show', $sale)->with('success', "Venta {$sale->sale_number} creada desde cotización.");
    }
}
