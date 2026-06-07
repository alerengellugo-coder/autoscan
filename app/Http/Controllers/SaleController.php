<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Enums\PaymentMethod;
use App\Models\Enums\SaleStatus;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    private function statusOptions(): array
    {
        return collect(SaleStatus::cases())->map(fn ($s) => ['value' => $s->value, 'label' => $s->label()])->values()->all();
    }

    public function index(Request $request): Response
    {
        $query = Sale::query()->with(['client', 'quotation']);
        if ($request->filled('status')) $query->where('status', $request->input('status'));
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where('sale_number', 'like', "%{$s}%")->orWhereHas('client', fn ($c) => $c->where('name', 'like', "%{$s}%"));
        }
        $sales = $query->orderByDesc('created_at')->paginate($request->input('per_page', 15))->withQueryString();

        $stats = [
            'total_sales' => Sale::count(),
            'total_revenue' => Sale::where('status', SaleStatus::Paid->value)->sum('total'),
            'pending_payment' => Sale::where('status', '!=', SaleStatus::Cancelled->value)->sumRaw('COALESCE(total - COALESCE(paid_amount, 0), 0)'),
        ];

        return Inertia::render('Admin/Sales/Index', [
            'sales'         => $sales,
            'stats'         => $stats,
            'status_options' => $this->statusOptions(),
            'filters'       => $request->only('search', 'status', 'per_page'),
        ]);
    }

    public function clientSales(Request $request): Response
    {
        $query = Sale::query()->with(['client', 'quotation'])->where('client_id', Auth::id());
        $sales = $query->orderByDesc('created_at')->paginate($request->input('per_page', 10))->withQueryString();
        return Inertia::render('Client/Sales/Index', [
            'sales'         => $sales,
            'status_options' => $this->statusOptions(),
            'filters'       => $request->only('per_page'),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Admin/Sales/Create', [
            'clients' => User::clients()->active()->orderBy('name')->get(['id', 'name']),
            'products' => Product::active()->orderBy('name')->get(['id', 'name', 'price', 'stock_quantity']),
            'quotations' => Quotation::where('status', 'approved')->orderByDesc('created_at')->get(['id', 'quotation_number', 'client_id', 'total']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'    => ['required', 'exists:users,id'],
            'quotation_id' => ['nullable', 'exists:quotations,id'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'tax_rate'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'payment_method' => ['nullable', 'string'],
            'notes'        => ['nullable', 'string', 'max:1000'],
            'items'        => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['nullable', 'exists:products,id'],
            'items.*.description'  => ['required', 'string'],
            'items.*.quantity'      => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'   => ['required', 'numeric', 'min:0'],
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $items = $validated['items'];
            unset($validated['items']);
            $validated['status'] = SaleStatus::Pending->value;
            // number is auto-generated in model boot()
            $subtotal = collect($items)->sum(fn ($i) => $i['quantity'] * $i['unit_price']);
            $taxRate = $validated['tax_rate'] ?? 0;
            $tax = $subtotal * ($taxRate / 100);
            $validated['subtotal'] = $subtotal;
            $validated['tax'] = $tax;
            $validated['total'] = $subtotal + $tax;
            $sale = Sale::create($validated);
            foreach ($items as $item) {
                $sale->items()->create(['product_id' => $item['product_id'] ?? null, 'description' => $item['description'], 'quantity' => $item['quantity'], 'unit_price' => $item['unit_price'], 'total' => $item['quantity'] * $item['unit_price']]);
            }
            return $sale;
        });

        return redirect()->route('admin.ventas.show', $sale)->with('success', "Venta {$sale->sale_number} creada.");
    }

    public function show(Sale $sale): Response
    {
        $sale->load(['client', 'quotation', 'items']);
        return Inertia::render('Admin/Sales/Show', ['sale' => $sale, 'status_options' => $this->statusOptions()]);
    }

    public function registerPayment(Request $request, Sale $sale)
    {
        $validated = $request->validate(['amount' => 'required|numeric|min:0', 'method' => 'nullable|string']);
        $sale->update(['paid_amount' => ($sale->paid_amount ?? 0) + $validated['amount'], 'payment_method' => $validated['method'] ?? $sale->payment_method]);
        return back()->with('success', 'Pago registrado.');
    }

    public function cancel(Sale $sale) { Gate::authorize('cancel-sale'); $sale->update(['status' => SaleStatus::Cancelled->value]); return back()->with('success', 'Venta cancelada.'); }
}
