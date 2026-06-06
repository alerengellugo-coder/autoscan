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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: QuotationController
 *
 * Manages quotations (estimates/quotes) for service work and products.
 * Supports the full lifecycle: create → send to client → approve/reject → convert to sale.
 * Includes PDF generation placeholder and sale conversion.
 */
class QuotationController extends Controller
{
    /**
     * Display a listing of quotations.
     *
     * Supports status filtering. Scoped by role:
     *   - Admin: all quotations.
     *   - Client: their own quotations.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $query = Quotation::query()->with(['client', 'vehicle', 'technician']);

        // Scope by role
        if ($user->isClient()) {
            $query->forClient($user->id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->input('status'));
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('quotation_number', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('client', function ($clientQ) use ($searchTerm) {
                        $clientQ->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange(
                $request->input('date_from'),
                $request->input('date_to')
            );
        }

        $quotations = $query->orderByDesc('created_at')
            ->paginate($request->input('per_page', 15))
            ->withQueryString();

        return Inertia::render('Quotations/Index', [
            'quotations' => $quotations,
            'filters'    => $request->only('search', 'status', 'date_from', 'date_to', 'per_page'),
            'statuses'   => QuotationStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new quotation.
     *
     * Loads clients, vehicles, and products for the form.
     */
    public function create(Request $request): Response
    {
        $user = Auth::user();

        // Pre-fill from service order if provided
        $serviceOrder = null;
        if ($request->filled('service_order_id')) {
            $serviceOrder = ServiceOrder::find($request->integer('service_order_id'));
        }

        // Load data for dropdowns
        $clients = User::clients()->active()->orderBy('name')->get(['id', 'name']);
        $vehicles = Vehicle::with('client')->active()->orderBy('brand')->get();
        $technicians = User::technicians()->active()->orderBy('name')->get(['id', 'name']);
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'sku', 'price', 'stock']);

        return Inertia::render('Quotations/Create', [
            'clients'       => $clients,
            'vehicles'      => $vehicles,
            'technicians'   => $technicians,
            'products'      => $products,
            'serviceOrder'  => $serviceOrder,
        ]);
    }

    /**
     * Store a newly created quotation in storage.
     *
     * Creates the quotation with its items, calculates totals automatically
     * via the model's calculateTotals() method.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        Gate::authorize('manage-quotations');

        $validated = $request->validate([
            'client_id'           => ['required', 'exists:users,id'],
            'vehicle_id'          => ['nullable', 'exists:vehicles,id'],
            'technician_id'       => ['nullable', 'exists:users,id'],
            'service_order_id'    => ['nullable', 'exists:service_orders,id'],
            'description'         => ['required', 'string', 'max:2000'],
            'tax_rate'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount'           => ['nullable', 'numeric', 'min:0'],
            'discount_type'       => ['nullable', 'string', 'in:fixed,percentage'],
            'valid_until'         => ['nullable', 'date', 'after:today'],
            'notes'               => ['nullable', 'string', 'max:1000'],
            'terms_and_conditions' => ['nullable', 'string', 'max:5000'],
            'items'               => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'exists:products,id'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity'    => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'items.*.discount'    => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percentage'],
            'items.*.notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $quotation = DB::transaction(function () use ($validated, $user) {
            $quotationData = array_merge($validated, [
                'status' => QuotationStatus::Draft->value,
            ]);
            unset($quotationData['items']);

            $quotation = Quotation::create($quotationData);

            // Create quotation items
            foreach ($validated['items'] as $itemData) {
                $itemData['quotation_id'] = $quotation->id;
                $itemData['total'] = $this->calculateItemTotal($itemData);
                QuotationItem::create($itemData);
            }

            // Recalculate totals
            $quotation->load('items');
            $quotation->calculateTotals();

            return $quotation;
        });

        return redirect()
            ->route('admin.cotizaciones.show', $quotation)
            ->with('success', "Cotización {$quotation->quotation_number} creada exitosamente.");
    }

    /**
     * Display the specified quotation with its items.
     */
    public function show(Quotation $quotation): Response
    {
        Gate::authorize('manage-quotations');

        $quotation->load([
            'client',
            'vehicle',
            'technician',
            'serviceOrder',
            'items.product',
        ]);

        return Inertia::render('Quotations/Show', [
            'quotation' => $quotation,
            'statuses'  => QuotationStatus::cases(),
        ]);
    }

    /**
     * Update the specified quotation in storage.
     *
     * Allows editing only when the status is Draft or PendingClient.
     * Replaces all items and recalculates totals.
     */
    public function update(Request $request, Quotation $quotation)
    {
        Gate::authorize('manage-quotations');

        if (! $quotation->status->isEditable()) {
            return back()->with('error', 'No se puede editar una cotización que ya fue aprobada o rechazada.');
        }

        $validated = $request->validate([
            'client_id'           => ['required', 'exists:users,id'],
            'vehicle_id'          => ['nullable', 'exists:vehicles,id'],
            'technician_id'       => ['nullable', 'exists:users,id'],
            'service_order_id'    => ['nullable', 'exists:service_orders,id'],
            'description'         => ['required', 'string', 'max:2000'],
            'tax_rate'            => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount'           => ['nullable', 'numeric', 'min:0'],
            'discount_type'       => ['nullable', 'string', 'in:fixed,percentage'],
            'valid_until'         => ['nullable', 'date', 'after:today'],
            'notes'               => ['nullable', 'string', 'max:1000'],
            'terms_and_conditions' => ['nullable', 'string', 'max:5000'],
            'items'               => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'exists:products,id'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity'    => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'items.*.discount'    => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percentage'],
            'items.*.notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $quotation = DB::transaction(function () use ($quotation, $validated) {
            $updateData = $validated;
            unset($updateData['items']);

            $quotation->update($updateData);

            // Remove old items and create new ones
            $quotation->items()->delete();

            foreach ($validated['items'] as $itemData) {
                $itemData['quotation_id'] = $quotation->id;
                $itemData['total'] = $this->calculateItemTotal($itemData);
                QuotationItem::create($itemData);
            }

            $quotation->load('items');
            $quotation->calculateTotals();

            return $quotation;
        });

        return redirect()
            ->route('admin.cotizaciones.show', $quotation)
            ->with('success', 'Cotización actualizada exitosamente.');
    }

    /**
     * Update the status of a quotation (approve, reject, or send to client).
     *
     * Handles status transitions:
     *   - Admin can approve, reject, or send to client.
     *   - Client can approve or reject their own quotations.
     */
    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', array_column(QuotationStatus::cases(), 'value'))],
        ]);

        $newStatus = QuotationStatus::from($validated['status']);
        $user = Auth::user();

        Gate::authorize('manage-quotations');

        DB::transaction(function () use ($quotation, $newStatus, $user) {
            switch ($newStatus) {
                case QuotationStatus::Approved:
                    $quotation->approve();
                    break;

                case QuotationStatus::Rejected:
                    $quotation->reject();
                    break;

                case QuotationStatus::PendingClient:
                    $quotation->sendToClient();
                    break;

                case QuotationStatus::Expired:
                    $quotation->markAsExpired();
                    break;

                default:
                    abort(400, 'Transición de estado no permitida.');
                    break;
            }
        });

        return back()->with('success', "Estado de cotización actualizado a '{$newStatus->label()}'.");
    }

    /**
     * Generate a PDF for the specified quotation.
     *
     * Placeholder implementation that returns a simple response.
     * In production, this would use barryvdh/laravel-dompdf or similar.
     */
    public function generatePdf(Quotation $quotation)
    {
        Gate::authorize('manage-quotations');

        $quotation->load(['client', 'vehicle', 'technician', 'items.product']);

        // Placeholder: In production, use DOMPDF or similar
        // $pdf = \PDF::loadView('pdfs.quotation', ['quotation' => $quotation]);
        // return $pdf->download("cotizacion-{$quotation->quotation_number}.pdf");

        return back()->with('info', 'La generación de PDF estará disponible próximamente.');
    }

    /**
     * Convert an approved quotation to a sale.
     *
     * Transfers all quotation items to sale items, decrements product stock,
     * and creates the sale record.
     */
    public function convertToSale(Quotation $quotation)
    {
        Gate::authorize('convert-quotation');

        if ($quotation->status !== QuotationStatus::Approved) {
            return back()->with('error', 'Solo se pueden convertir cotizaciones aprobadas.');
        }

        // Check if already converted
        if ($quotation->sale()->exists()) {
            return back()->with('error', 'Esta cotización ya fue convertida a venta.');
        }

        $sale = DB::transaction(function () use ($quotation) {
            // Create the sale
            $sale = \App\Models\Sale::create([
                'client_id'      => $quotation->client_id,
                'quotation_id'   => $quotation->id,
                'description'    => $quotation->description,
                'status'         => \App\Models\Enums\SaleStatus::Pending->value,
                'tax_rate'       => $quotation->tax_rate,
                'discount'       => $quotation->discount,
                'discount_type'  => $quotation->discount_type,
                'notes'          => $quotation->notes,
                'subtotal'       => $quotation->subtotal,
                'tax'            => $quotation->tax,
                'total'          => $quotation->total,
                'paid_amount'    => 0,
            ]);

            // Create sale items and decrement stock
            foreach ($quotation->items as $quotationItem) {
                $saleItem = \App\Models\SaleItem::create([
                    'sale_id'     => $sale->id,
                    'product_id'  => $quotationItem->product_id,
                    'description' => $quotationItem->description,
                    'quantity'    => $quotationItem->quantity,
                    'unit_price'  => $quotationItem->unit_price,
                    'cost'        => $quotationItem->product?->cost,
                    'discount'    => $quotationItem->discount,
                    'discount_type' => $quotationItem->discount_type,
                    'total'       => $quotationItem->total,
                    'notes'       => $quotationItem->notes,
                ]);

                // Decrement product stock
                if ($quotationItem->product) {
                    $quotationItem->product->decrementStock((int) $quotationItem->quantity);
                }
            }

            return $sale;
        });

        return redirect()
            ->route('admin.ventas.show', $sale)
            ->with('success', "Cotización {$quotation->quotation_number} convertida a venta {$sale->sale_number} exitosamente.");
    }

    /**
     * Calculate the total for a single quotation item.
     *
     * @param  array<string, mixed>  $item
     * @return float
     */
    private function calculateItemTotal(array $item): float
    {
        $unitPrice = (float) $item['unit_price'];
        $quantity = (float) $item['quantity'];
        $discount = (float) ($item['discount'] ?? 0);
        $discountType = $item['discount_type'] ?? 'fixed';

        if ($discountType === 'percentage') {
            $effectivePrice = $unitPrice * (1 - $discount / 100);
        } else {
            $effectivePrice = $unitPrice - $discount;
        }

        return round(max(0, $effectivePrice) * $quantity, 2);
    }
}
