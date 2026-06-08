@extends('layouts.app')

@section('title', 'Editar Orden')
@section('page-title', 'Editar Orden de Servicio')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.ordenes.show', $order) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a la Orden
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Editar Orden {{ $order->order_number }}</h2>
            <p class="text-sm text-gray-500 mt-1">Modifica los campos de la orden de servicio.</p>
        </div>

        <form method="PUT" action="{{ route('admin.ordenes.update', $order) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Service Type --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Servicio</label>
                    <select id="service_type"
                            name="service_type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar tipo</option>
                        @php
                            $serviceTypes = \App\Models\Enums\ServiceType::cases();
                        @endphp
                        @foreach($serviceTypes as $type)
                            <option value="{{ $type->value }}" {{ $order->service_type->value == $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">Tecnico</label>
                    <select id="technician_id"
                            name="technician_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sin asignar</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ ($order->technician_id == $technician->id) ? 'selected' : '' }}>
                                {{ $technician->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Priority --}}
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select id="priority"
                        name="priority"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @foreach($priority_options as $opt)
                        <option value="{{ $opt['value'] }}" {{ $order->priority->value == $opt['value'] ? 'selected' : '' }}>
                            {{ $opt['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripcion</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y">{{ old('description', $order->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Diagnosis --}}
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">Diagnostico</label>
                <textarea id="diagnosis"
                          name="diagnosis"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y">{{ old('diagnosis', $order->diagnosis) }}</textarea>
            </div>

            {{-- Estimated Cost + Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-1">Costo Estimado</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                        <input type="number"
                               id="estimated_cost"
                               name="estimated_cost"
                               value="{{ old('estimated_cost', $order->estimated_cost) }}"
                               min="0"
                               step="0.01"
                               class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>
                <div>
                    <label for="estimated_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Estimada de Entrega</label>
                    <input type="date"
                           id="estimated_completion_date"
                           name="estimated_completion_date"
                           value="{{ old('estimated_completion_date', $order->estimated_completion_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y">{{ old('notes', $order->notes) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.ordenes.show', $order) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
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
