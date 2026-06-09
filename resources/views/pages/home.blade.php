@extends('layouts.public')

@section('title', 'AutoScan - Escaneo Vehicular y Reparación Computarizada')

@section('content')

{{-- Hero Section --}}
<section class="relative min-h-[600px] lg:min-h-[700px] flex items-center overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1e40af 100%);">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600/5 rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-0">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/20 backdrop-blur-sm rounded-full text-blue-200 text-sm font-medium mb-6">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Tecnologia de punta para tu vehiculo
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                Diagnostico electronico profesional para tu vehiculo
            </h1>
            <p class="mt-6 text-lg text-blue-100/80 leading-relaxed max-w-2xl">
                Escaneo vehicular computarizado, reparacion de modulos electronicos, reprogramacion de ECU y mucho mas. Servicio especializado con garantia.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-blue-500/25">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Registrarse
                </a>
                <a href="#productos" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-lg transition-colors border border-white/20">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                    </svg>
                    Ver Productos
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Products & Cart Section --}}
<section id="productos" class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Nuestros Productos y Servicios</h2>
            <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Explora nuestro catalogo de productos y servicios automotrices. Agrega al carrito y solicita tu cotizacion.</p>
        </div>

        {{-- Category filter tabs --}}
        <div class="flex flex-wrap justify-center gap-2 mb-8">
            <button onclick="filterCategory('all')" class="cat-btn active-cat px-4 py-2 text-sm font-medium rounded-full transition-colors bg-blue-600 text-white" data-cat="all">
                Todos ({{ $products->count() }})
            </button>
            @foreach($categories as $cat)
                @php $count = ($products_by_category[$cat->value] ?? collect())->count(); @endphp
                @if($count > 0)
                <button onclick="filterCategory('{{ $cat->value }}')" class="cat-btn px-4 py-2 text-sm font-medium rounded-full transition-colors bg-white text-gray-600 border border-gray-200 hover:bg-gray-100" data-cat="{{ $cat->value }}">
                    {{ $cat->label() }} ({{ $count }})
                </button>
                @endif
            @endforeach
        </div>

        {{-- Main layout: Products grid + Cart sidebar --}}
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Products Grid --}}
            <div class="flex-1">
                <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($products as $product)
                    <div class="product-card bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-blue-200 transition-all group" data-category="{{ $product->category?->value ?? 'other' }}">
                        {{-- Product image placeholder --}}
                        <div class="h-44 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center relative">
                            @if($product->image_path)
                                <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-blue-300 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                                    </svg>
                                    <span class="text-xs text-blue-400 font-medium">{{ $product->category?->label() ?? 'Producto' }}</span>
                                </div>
                            @endif
                            {{-- Stock badge --}}
                            <div class="absolute top-3 left-3">
                                @if($product->stock_quantity > 0 && $product->stock_quantity <= $product->min_stock_alert)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Stock bajo</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disponible</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Agotado</span>
                                @endif
                            </div>
                        </div>

                        {{-- Product info --}}
                        <div class="p-4">
                            <p class="text-xs text-blue-500 font-medium mb-1">{{ $product->category?->label() ?? 'General' }}</p>
                            <h3 class="text-sm font-semibold text-gray-900 mb-1 leading-snug line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $product->description ?? 'Sin descripcion' }}</p>

                            <div class="flex items-end justify-between">
                                <div>
                                    <span class="text-lg font-bold text-gray-900">${{ number_format((float)$product->price, 2) }}</span>
                                    <p class="text-xs text-gray-400">SKU: {{ $product->sku }}</p>
                                </div>

                                @if($product->stock_quantity > 0)
                                <button onclick="addToCart({{ $product->id }}, '{{ str_replace("'", "\'", $product->name) }}', {{ (float)$product->price }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Agregar
                                </button>
                                @else
                                <span class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-lg bg-gray-100 text-gray-400">Agotado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($products->count() === 0)
                    <div class="col-span-full text-center py-16">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                        </svg>
                        <p class="text-gray-400 font-medium">No hay productos disponibles en este momento.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Cart Sidebar --}}
            <div class="w-full lg:w-80 xl:w-96 flex-shrink-0">
                <div id="cartPanel" class="sticky top-24 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    {{-- Cart Header --}}
                    <div class="bg-blue-600 px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                            </svg>
                            <span class="text-white font-semibold">Mi Carrito</span>
                        </div>
                        <span id="cartCount" class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold rounded-full bg-white text-blue-600">0</span>
                    </div>

                    {{-- Cart Items --}}
                    <div id="cartItems" class="max-h-[400px] overflow-y-auto divide-y divide-gray-100">
                        <div id="cartEmpty" class="px-5 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                            </svg>
                            <p class="text-sm text-gray-400">Tu carrito esta vacio</p>
                            <p class="text-xs text-gray-300 mt-1">Agrega productos para comenzar</p>
                        </div>
                    </div>

                    {{-- Cart Footer --}}
                    <div id="cartFooter" class="hidden border-t border-gray-200 px-5 py-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Subtotal:</span>
                            <span id="cartSubtotal" class="text-lg font-bold text-gray-900">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-400">
                            <span>{{ count($products) }} productos disponibles</span>
                            <span id="cartItemsCount">0 items</span>
                        </div>
                        <button onclick="requestQuotation()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/>
                            </svg>
                            Solicitar Cotizacion
                        </button>
                        <button onclick="clearCart()" class="w-full py-2 text-xs font-medium text-gray-400 hover:text-red-500 transition-colors">
                            Vaciar carrito
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="py-16 lg:py-20" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">1500<span class="text-blue-200">+</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Vehiculos Diagnosticados</p>
            </div>
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">15<span class="text-blue-200">+</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Anos de Experiencia</p>
            </div>
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">8000<span class="text-blue-200">+</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Diagnosticos Realizados</p>
            </div>
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">98<span class="text-blue-200">%</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Satisfaccion del Cliente</p>
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Como Funciona?</h2>
            <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Un proceso simple y transparente para el diagnostico y reparacion de tu vehiculo.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">1. Elige Productos</h3>
                <p class="text-sm text-gray-500">Agrega los productos o servicios que necesitas al carrito.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">2. Solicita Cotizacion</h3>
                <p class="text-sm text-gray-500">Envia tu solicitud con los productos seleccionados y te cotizamos.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">3. Aprobacion</h3>
                <p class="text-sm text-gray-500">Revisa y aprueba la cotizacion desde tu panel de cliente.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">4. Reparacion</h3>
                <p class="text-sm text-gray-500">Nuestros tecnicos realizan el trabajo con garantia.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 lg:py-24" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1e40af 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold text-white">Lista tu vehiculo hoy?</h2>
        <p class="mt-4 text-lg text-blue-200/80 max-w-2xl mx-auto">Registrate gratis y agenda un diagnostico. Nosotros nos encargamos del resto.</p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-500 hover:bg-blue-600 text-white text-base font-semibold rounded-lg transition-colors shadow-lg shadow-blue-500/25">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Registrar mi Cuenta
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ─── Shopping Cart (localStorage) ───
const CART_KEY = 'autoscan_cart';

function getCart() {
    try {
        return JSON.parse(localStorage.getItem(CART_KEY)) || [];
    } catch { return []; }
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function addToCart(id, name, price) {
    const cart = getCart();
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ id, name, price, qty: 1 });
    }
    saveCart(cart);
    renderCart();

    // Brief animation on the button
    const btn = event.currentTarget;
    btn.classList.add('scale-95');
    setTimeout(() => btn.classList.remove('scale-95'), 150);
}

function removeFromCart(id) {
    const cart = getCart().filter(i => i.id !== id);
    saveCart(cart);
    renderCart();
}

function updateQty(id, delta) {
    const cart = getCart();
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    saveCart(cart);
    renderCart();
}

function clearCart() {
    saveCart([]);
    renderCart();
}

function getCartTotal() {
    return getCart().reduce((sum, i) => sum + (i.price * i.qty), 0);
}

function getCartItemsCount() {
    return getCart().reduce((sum, i) => sum + i.qty, 0);
}

function renderCart() {
    const cart = getCart();
    const itemsEl = document.getElementById('cartItems');
    const emptyEl = document.getElementById('cartEmpty');
    const footerEl = document.getElementById('cartFooter');
    const countEl = document.getElementById('cartCount');
    const subtotalEl = document.getElementById('cartSubtotal');
    const itemCountEl = document.getElementById('cartItemsCount');

    const totalItems = getCartItemsCount();
    countEl.textContent = totalItems;

    if (cart.length === 0) {
        emptyEl.classList.remove('hidden');
        footerEl.classList.add('hidden');
        // Remove all item rows but keep empty message
        itemsEl.querySelectorAll('.cart-item-row').forEach(el => el.remove());
        return;
    }

    emptyEl.classList.add('hidden');
    footerEl.classList.remove('hidden');

    // Remove old items
    itemsEl.querySelectorAll('.cart-item-row').forEach(el => el.remove());

    cart.forEach(item => {
        const row = document.createElement('div');
        row.className = 'cart-item-row px-5 py-3 flex items-start gap-3';
        row.innerHTML = `
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${item.name}</p>
                <p class="text-sm font-bold text-blue-600">$${item.price.toFixed(2)}</p>
            </div>
            <div class="flex items-center gap-1 flex-shrink-0">
                <button onclick="updateQty(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold transition-colors">-</button>
                <span class="w-8 text-center text-sm font-semibold text-gray-900">${item.qty}</span>
                <button onclick="updateQty(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold transition-colors">+</button>
            </div>
            <div class="text-right flex-shrink-0 w-16">
                <p class="text-sm font-semibold text-gray-900">$${(item.price * item.qty).toFixed(2)}</p>
            </div>
            <button onclick="removeFromCart(${item.id})" class="flex-shrink-0 text-gray-300 hover:text-red-500 transition-colors" title="Eliminar">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        `;
        itemsEl.appendChild(row);
    });

    subtotalEl.textContent = '$' + getCartTotal().toFixed(2);
    itemCountEl.textContent = totalItems + (totalItems === 1 ? ' item' : ' items');
}

function requestQuotation() {
    const cart = getCart();
    if (cart.length === 0) return;

    const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

    if (!isLoggedIn) {
        const confirmed = confirm('Necesitas una cuenta para solicitar cotizacion. Deseas registrarte?');
        if (confirmed) {
            window.location.href = '{{ route("register") }}';
        }
        return;
    }

    // Redirect to order creation page with cart info in localStorage
    // The order form will pick up cart data
    window.location.href = '{{ route("admin.ordenes.create") }}';
}

// ─── Category Filter ───
function filterCategory(cat) {
    const cards = document.querySelectorAll('.product-card');
    cards.forEach(card => {
        if (cat === 'all' || card.dataset.category === cat) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });

    // Update active tab
    document.querySelectorAll('.cat-btn').forEach(btn => {
        if (btn.dataset.cat === cat) {
            btn.className = 'cat-btn active-cat px-4 py-2 text-sm font-medium rounded-full transition-colors bg-blue-600 text-white';
        } else {
            btn.className = 'cat-btn px-4 py-2 text-sm font-medium rounded-full transition-colors bg-white text-gray-600 border border-gray-200 hover:bg-gray-100';
        }
    });
}

// ─── Init ───
document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endpush
