<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: ProductController
 *
 * Manages the product catalog including creation, editing, soft-deletion
 * (deactivation), and a public read-only catalog for technicians and clients.
 */
class ProductController extends Controller
{
    /**
     * Display a listing of products (admin view).
     *
     * Supports search and category filtering. Includes inactive products.
     */
    public function index(Request $request): Response
    {
        $query = Product::query();

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->category($request->input('category'));
        }

        // Apply stock status filter
        if ($request->filled('stock_status')) {
            switch ($request->input('stock_status')) {
                case 'low':
                    $query->lowStock();
                    break;
                case 'out':
                    $query->outOfStock();
                    break;
                case 'available':
                    $query->where('stock', '>', 0)->whereColumn('stock', '>', 'min_stock');
                    break;
            }
        }

        // Apply sorting
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');

        if ($sortField === 'price') {
            $query->orderByPrice($sortDirection);
        } elseif ($sortField === 'stock') {
            $query->orderByStock($sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $products = $query->paginate($request->input('per_page', 15))
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products'          => $products,
            'filters'           => $request->only('search', 'category', 'stock_status', 'sort', 'direction', 'per_page'),
            'categories'        => ProductCategory::cases(),
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        Gate::authorize('manage-products');

        return Inertia::render('Products/Create', [
            'categories' => ProductCategory::cases(),
        ]);
    }

    /**
     * Store a newly created product in storage.
     *
     * Validates the SKU uniqueness and all required fields.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-products');

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category'    => ['required', 'string', 'in:' . implode(',', array_column(ProductCategory::cases(), 'value'))],
            'price'       => ['required', 'numeric', 'min:0'],
            'cost'        => ['nullable', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'min_stock'   => ['nullable', 'integer', 'min:0'],
            'unit'        => ['nullable', 'string', 'max:50'],
            'is_active'   => ['boolean'],
            'barcode'     => ['nullable', 'string', 'max:100', 'unique:products,barcode'],
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;

        $product = Product::create($validated);

        return redirect()
            ->route('admin.productos.show', $product)
            ->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): Response
    {
        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): Response
    {
        Gate::authorize('manage-products');

        return Inertia::render('Products/Edit', [
            'product'    => $product,
            'categories' => ProductCategory::cases(),
        ]);
    }

    /**
     * Update the specified product in storage.
     *
     * Validates the SKU uniqueness (ignoring the current product).
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('manage-products');

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string', 'max:2000'],
            'category'    => ['required', 'string', 'in:' . implode(',', array_column(ProductCategory::cases(), 'value'))],
            'price'       => ['required', 'numeric', 'min:0'],
            'cost'        => ['nullable', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'min_stock'   => ['nullable', 'integer', 'min:0'],
            'unit'        => ['nullable', 'string', 'max:50'],
            'is_active'   => ['boolean'],
            'barcode'     => ['nullable', 'string', 'max:100', 'unique:products,barcode,' . $product->id],
        ]);

        $product->update($validated);

        return redirect()
            ->route('admin.productos.show', $product)
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Soft-delete (deactivate) the specified product.
     *
     * Sets is_active to false instead of permanently deleting the record.
     * Hard deletion is reserved for admin-level cleanup only.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('manage-products');

        // Soft delete by deactivating
        $product->update(['is_active' => false]);

        return redirect()
            ->route('admin.productos.index')
            ->with('success', "Producto '{$product->name}' desactivado exitosamente.");
    }

    /**
     * Display the public product catalog.
     *
     * Read-only view available to technicians and clients.
     * Shows only active products with basic information.
     */
    public function catalog(Request $request): Response
    {
        $query = Product::query()->active();

        // Apply search filter
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Apply category filter
        if ($request->filled('category')) {
            $query->category($request->input('category'));
        }

        $products = $query->orderBy('name')
            ->paginate($request->input('per_page', 20))
            ->withQueryString();

        return Inertia::render('Products/Catalog', [
            'products'   => $products,
            'filters'    => $request->only('search', 'category', 'per_page'),
            'categories' => ProductCategory::cases(),
        ]);
    }
}
