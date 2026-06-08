@extends('layouts.app')

@section('title', 'Editar Cotización #' . $quotation->quotation_number)
@section('page-title', 'Editar Cotización')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-2">
        <a href="{{ route('admin.cotizaciones.show', $quotation) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Cotización #{{ $quotation->quotation_number }}
        </a>
    </div>

    <form method="POST" action="{{ route('admin.cotizaciones.update', $quotation) }}">
        @csrf
        @method('PUT')

        {{-- Quotation Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Datos de la Cotización</h2>
            </div>
            <div class="p-6 space-y-5">

                {{-- Client + Vehicle --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                        <select id="client_id" name="client_id" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach($clients as $clientId => $clientName)
                                <option value="{{ $clientId }}" {{ old('client_id', $quotation->client_id) == $clientId ? 'selected' : '' }}>
                                    {{ $clientName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">Vehículo</label>
                        <select id="vehicle_id" name="vehicle_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sin vehículo</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $quotation->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate }} — {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Valid Until + Tax/Discount --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">Válida Hasta</label>
                        <input type="date" id="valid_until" name="valid_until"
                               value="{{ old('valid_until', $quotation->valid_until ? $quotation->valid_until->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Impuesto (%)</label>
                        <input type="number" id="tax_rate" name="tax_rate"
                               value="{{ old('tax_rate', $quotation->tax_rate) }}"
                               min="0" max="100" step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Descuento ($)</label>
                        <input type="number" id="discount" name="discount"
                               value="{{ old('discount', $quotation->discount) }}"
                               min="0" step="0.01"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                              placeholder="Descripción de la cotización...">{{ old('description', $quotation->description) }}</textarea>
                </div>

                {{-- Notes --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                              placeholder="Notas adicionales...">{{ old('notes', $quotation->notes) }}</textarea>
                </div>

                {{-- Info --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700">
                        <strong>Nota:</strong> Los items de la cotización no se pueden modificar aquí. El total es: <strong>${{ number_format($quotation->total, 2) }}</strong>
                    </p>
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.cotizaciones.show', $quotation) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
