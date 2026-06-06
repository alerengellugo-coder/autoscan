<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enums\PaymentMethod;
use App\Models\Enums\QuotationStatus;
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

/**
 * Controller: SaleController
 *
 * Manages sales lifecycle including creation (from quotation or manual),
 * payment registration, and cancellation with stock restoration.
 */
class SaleController extends Controller
{
    /**
     * Display a listing of sales.
     *
     * Supports status filtering. Scoped by role:
     *   - Admin: all sales.
     *   - Client: their own sales.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        $query = Sale::query()->with(['client', 'quotation']);

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
                $q->where('sale_number', 'like', "%{$searchTerm}%")
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

        // Paid date range
        if ($request->filled('paid_from') && $request->filled('paid_to')) {
            $query->byPaidDateRange(
                $request->input('paid_from'),
                $request->input('paid_to')
            );
        }

        $sales = $query->orderByDesc('created_at')
            ->paginate($request->input('per_page', 15))
            ->withQueryString();

        return Inertia::render('Sales/Index', [
            'sales'    => $sales,
            'filters'  => $request->only('search', 'status', 'date_from', 'date_to', 'paid_from', 'paid_to', 'per_page'),
            'statuses' => SaleStatus::cases(),
        ]);
    }

    /**
     * Show the form for creating a new sale.
     *
     * Can be pre-filled from a quotation. Loads clients, products, and
     * payment methods for the form.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('manage-sales');

        // Pre-fill from quotation if provided
        $quotation = null;
        if ($request->filled('quotation_id')) {
            $quotation = Quotation::with(['items.product', 'client', 'vehicle'])
                ->where('id', $request->integer('quotation_id'))
                ->where('status', QuotationStatus::Approved->value)
                ->first();
        }

        $clients = User::clients()->active()->orderBy('name')->get(['id', 'name']);
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'sku', 'price', 'stock']);
        $paymentMethods = PaymentMethod::cases();

        return Inertia::render('Sales/Create', [
            'clients'        => $clients,
            'products'       => $products,
            'paymentMethods' => $paymentMethods,
            'quotation'      => $quotation,
        ]);
    }

    /**
     * Store a newly created sale in storage.
     *
     * Supports both manual creation and creation from quotation.
     * Decrements product stock for each item.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-sales');

        $validated = $request->validate([
            'client_id'     => ['required', 'exists:users,id'],
            'quotation_id'  => ['nullable', 'exists:quotations,id'],
            'description'   => ['required', 'string', 'max:2000'],
            'tax_rate'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount'      => ['nullable', 'numeric', 'min:0'],
            'discount_type' => ['nullable', 'string', 'in:fixed,percentage'],
            'notes'         => ['nullable', 'string', 'max:1000'],
            'items'         => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['required', 'exists:products,id'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity'    => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'items.*.cost'        => ['nullable', 'numeric', 'min:0'],
            'items.*.discount'    => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percentage'],
            'items.*.notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $sale = DB::transaction(function () use ($validated) {
            $saleData = array_merge($validated, [
                'status'      => SaleStatus::Pending->value,
                'paid_amount' => 0,
            ]);
            unset($saleData['items']);

            $sale = Sale::create($saleData);

            // Create sale items and decrement stock
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);

                // Check stock availability
                if ($product && $product->stock < (int) $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente para el producto '{$product->name}'. Disponible: {$product->stock}, Solicitado: {$itemData['quantity']}.");
                }

                $itemData['sale_id'] = $sale->id;
                $itemData['total'] = $this->calculateItemTotal($itemData);
                SaleItem::create($itemData);

                // Decrement stock
                if ($product) {
                    $product->decrementStock((int) $itemData['quantity']);
                }
            }

            // Recalculate totals
            $sale->load('items');
            $sale->calculateTotals();

            return $sale;
        });

        return redirect()
            ->route('admin.ventas.show', $sale)
            ->with('success', "Venta {$sale->sale_number} registrada exitosamente.");
    }

    /**
     * Display the specified sale with its items.
     */
    public function show(Sale $sale): Response
    {
        Gate::authorize('manage-sales');

        $sale->load([
            'client',
            'quotation',
            'items.product',
        ]);

        return Inertia::render('Sales/Show', [
            'sale'           => $sale,
            'statuses'       => SaleStatus::cases(),
            'paymentMethods' => PaymentMethod::cases(),
        ]);
    }

    /**
     * Register a payment for a sale.
     *
     * Applies the payment amount, updates the payment method,
     * and transitions the sale status to Paid or PartiallyPaid.
     */
    public function registerPayment(Request $request, Sale $sale)
    {
        Gate::authorize('register-payment');

        if ($sale->status === SaleStatus::Cancelled) {
            return back()->with('error', 'No se puede registrar pagos en una venta cancelada.');
        }

        if ($sale->is_fully_paid) {
            return back()->with('error', 'Esta venta ya se encuentra totalmente pagada.');
        }

        $validated = $request->validate([
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'in:' . implode(',', array_column(PaymentMethod::cases(), 'value'))],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $amount = (float) $validated['amount'];
        $remaining = $sale->remaining_amount;

        if ($amount > $remaining) {
            return back()->withErrors([
                'amount' => "El monto ingresado excede el saldo pendiente. Saldo: {$sale->formatted_remaining_amount}",
            ]);
        }

        $method = PaymentMethod::from($validated['payment_method']);

        DB::transaction(function () use ($sale, $amount, $method, $validated) {
            // Use the first payment as the primary method
            if (! $sale->payment_method) {
                $sale->payment_method = $method;
            }

            $paidAmount = (float) ($sale->paid_amount ?? 0) + $amount;
            $sale->paid_amount = round($paidAmount, 2);

            if ($paidAmount >= (float) $sale->total) {
                $sale->status = SaleStatus::Paid;
                $sale->paid_at = now();
            } else {
                $sale->status = SaleStatus::PartiallyPaid;
            }

            $sale->save();

            // Update payment method if explicitly set
            if ($method) {
                $sale->update(['payment_method' => $method]);
            }

            // Create payment record (if there's a payments table in the future)
            // Payment::create([...]);
        });

        return back()->with('success', "Pago de {$sale->formatted_paid_amount} registrado exitosamente.");
    }

    /**
     * Cancel a sale and restore product stock.
     *
     * Only admin can cancel sales. Uses the model's cancel() method
     * which handles stock restoration automatically.
     */
    public function cancel(Sale $sale)
    {
        Gate::authorize('cancel-sale');

        if ($sale->status === SaleStatus::Cancelled) {
            return back()->with('error', 'Esta venta ya se encuentra cancelada.');
        }

        if ($sale->is_fully_paid) {
            return back()->with('error', 'No se puede cancelar una venta que ya fue totalmente pagada. Contacte al administrador.');
        }

        DB::transaction(function () use ($sale) {
            $sale->cancel();
        });

        return redirect()
            ->route('admin.ventas.index')
            ->with('success', "Venta {$sale->sale_number} cancelada exitosamente. Stock restaurado.");
    }

    /**
     * Calculate the total for a single sale item.
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
