<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    private function categoryOptions(): array
    {
        return collect(ProductCategory::cases())->map(fn ($c) => ['value' => $c->value, 'label' => $c->label()])->values()->all();
    }

    public function index(Request $request): Response
    {
        $query = Product::query();
        if ($request->filled('search')) $query->where('name', 'like', "%{$request->input('search')}%");
        if ($request->filled('category')) $query->where('category', $request->input('category'));
        if ($request->filled('stock_status')) {
            $status = $request->input('stock_status');
            if ($status === 'low') $query->whereRaw('stock_quantity <= min_stock_alert')->where('stock_quantity', '>', 0);
            elseif ($status === 'out') $query->where('stock_quantity', 0);
            elseif ($status === 'normal') $query->whereRaw('stock_quantity > min_stock_alert');
        }
        $sort = $request->input('sort', 'name');
        $dir = $request->input('direction', 'asc');
        $query->orderBy($sort, $dir);
        $products = $query->paginate($request->input('per_page', 15))->withQueryString();

        $totalProducts = Product::count();
        $lowStockCount = Product::whereRaw('stock_quantity <= min_stock_alert')->count();
        $totalValue = Product::sum('price') * Product::sum('stock_quantity');

        return Inertia::render('Admin/Products/Index', [
            'products'             => $products,
            'categories'           => $this->categoryOptions(),
            'total_products'       => $totalProducts,
            'low_stock_count'      => $lowStockCount,
            'total_inventory_value' => (float) $totalValue,
            'filters'              => $request->only('search', 'category', 'stock_status', 'sort', 'direction', 'per_page'),
        ]);
    }

    public function catalog(Request $request): Response
    {
        $query = Product::active();
        if ($request->filled('search')) $query->where('name', 'like', "%{$request->input('search')}%");
        if ($request->filled('category')) $query->where('category', $request->input('category'));
        $products = $query->orderBy('name')->paginate($request->input('per_page', 20))->withQueryString();
        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $this->categoryOptions(),
            'total_products' => Product::count(),
            'low_stock_count' => 0,
            'total_inventory_value' => 0,
            'filters' => $request->only('search', 'category', 'per_page'),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create', ['categories' => $this->categoryOptions()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products',
            'sku' => 'nullable|string|max:100|unique:products',
            'description' => 'nullable|string|max:2000',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
        ]);
        if (empty($validated['slug'])) $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        Product::create($validated);
        return redirect()->route('admin.productos.index')->with('success', 'Producto creado.');
    }

    public function show(Product $product): Response
    {
        return Inertia::render('Admin/Products/Show', ['product' => $product]);
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Edit', ['product' => $product, 'categories' => $this->categoryOptions()]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:0',
        ]);
        $product->update($validated);
        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        return back()->with('success', 'Producto desactivado.');
    }
}
