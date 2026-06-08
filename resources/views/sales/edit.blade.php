@extends('layouts.app')

@section('title', 'Editar Venta #' . $sale->sale_number)
@section('page-title', 'Editar Venta')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="mb-6">
        <a href="{{ route('admin.ventas.show', $sale) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Venta #{{ $sale->sale_number }}
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Venta #{{ $sale->sale_number }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">Cliente: {{ $sale->client->name ?? '—' }}</p>
        </div>

        <form method="POST" action="{{ route('admin.ventas.update', $sale) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Descripción de la venta...">{{ old('description', $sale->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tasa de Impuesto (%)</label>
                    <input type="number" id="tax_rate" name="tax_rate"
                           value="{{ old('tax_rate', $sale->tax_rate) }}"
                           min="0" max="100" step="0.01"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Descuento ($)</label>
                    <input type="number" id="discount" name="discount"
                           value="{{ old('discount', $sale->discount) }}"
                           min="0" step="0.01"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea id="notes" name="notes" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Notas adicionales...">{{ old('notes', $sale->notes) }}</textarea>
            </div>

            {{-- Info: Total cannot be changed directly --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">
                    <strong>Nota:</strong> El total y los items de la venta no se pueden modificar aquí. Para cambiar items, cancela esta venta y crea una nueva.
                </p>
                <p class="text-sm text-blue-600 mt-1">
                    Total actual: <strong>${{ number_format($sale->total, 2) }}</strong> — Pagado: <strong>${{ number_format($sale->paid_amount ?? 0, 2) }}</strong>
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.ventas.show', $sale) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
