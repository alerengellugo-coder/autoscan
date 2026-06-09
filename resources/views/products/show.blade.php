@extends('layouts.app')

@section('title', $product->name)
@section('page-title', 'Detalle de Producto')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.productos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Productos
        </a>
    </div>

    {{-- Product Detail Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Product Image --}}
        @if($product->image_path)
        <div class="h-64 bg-gray-100 flex items-center justify-center overflow-hidden">
            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
        </div>
        @endif

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $product->name }}</h2>
                @if($product->sku)
                    <p class="text-sm text-gray-500 mt-0.5 font-mono">SKU: {{ $product->sku }}</p>
                @endif
            </div>
            @if($product->is_active)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Activo</span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">Inactivo</span>
            @endif
        </div>

        {{-- Details --}}
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">

                {{-- Categoría --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Categoría</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $product->category->label() ?? $product->category }}</dd>
                </div>

                {{-- Precio --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Precio</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">${{ $product->price ? number_format($product->price, 2) : '0.00' }}</dd>
                </div>

                {{-- Costo --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Costo</dt>
                    <dd class="mt-1 text-sm text-gray-900">${{ $product->cost ? number_format($product->cost, 2) : '0.00' }}</dd>
                </div>

                {{-- Stock --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stock</dt>
                    <dd class="mt-1 flex items-center gap-2">
                        <span class="text-sm text-gray-900">{{ $product->stock_quantity }} {{ $product->unit ?? 'unidades' }}</span>
                        @if($product->stock_quantity <= 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Agotado</span>
                        @elseif($product->stock_quantity <= $product->min_stock_alert)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Stock bajo</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Disponible</span>
                        @endif
                    </dd>
                </div>

                {{-- Unidad --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Unidad</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $product->unit ?? '—' }}</dd>
                </div>

                {{-- Stock Mínimo Alerta --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Stock Mínimo de Alerta</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $product->min_stock_alert }} {{ $product->unit ?? 'unidades' }}</dd>
                </div>

            </div>

            {{-- Description --}}
            @if($product->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Descripción</dt>
                <dd class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $product->description }}</dd>
            </div>
            @endif
        </div>

        {{-- Action Buttons --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <a href="{{ route('admin.productos.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15,18 9,12 15,6"/>
                </svg>
                Volver
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.productos.edit', $product) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('admin.productos.destroy', $product) }}" onsubmit="return confirm('¿Estás seguro de que deseas desactivar este producto? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3,6 5,6 21,6"/>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
