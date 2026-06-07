@extends('layouts.app')

@section('title', 'Crear Orden')
@section('page-title', 'Crear Orden de Servicio')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.ordenes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Órdenes
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Nueva Orden de Servicio</h2>
            <p class="text-sm text-gray-500 mt-1">Completa los campos para registrar una nueva orden de servicio.</p>
        </div>

        <form method="POST" action="{{ route('admin.ordenes.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Vehicle --}}
            <div>
                <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">Vehículo <span class="text-red-500">*</span></label>
                <select id="vehicle_id"
                        name="vehicle_id"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Seleccionar vehículo</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->plate }} — {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                        </option>
                    @endforeach
                </select>
                @error('vehicle_id')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Client (admin only) --}}
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                <select id="client_id"
                        name="client_id"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Seleccionar cliente</option>
                    @foreach($clients as $clientId => $clientName)
                        <option value="{{ $clientId }}" {{ old('client_id') == $clientId ? 'selected' : '' }}>
                            {{ $clientName }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Technician + Service Type --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">Técnico <span class="text-red-500">*</span></label>
                    <select id="technician_id"
                            name="technician_id"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar técnico</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                {{ $technician->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('technician_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Servicio <span class="text-red-500">*</span></label>
                    <select id="service_type"
                            name="service_type"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar tipo</option>
                        @foreach($service_types as $type)
                            <option value="{{ $type['value'] }}" {{ old('service_type') == $type['value'] ? 'selected' : '' }}>
                                {{ $type['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_type')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Priority --}}
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select id="priority"
                        name="priority"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @foreach($priorities as $priority)
                        <option value="{{ $priority['value'] }}" {{ old('priority', 'normal') == $priority['value'] ? 'selected' : '' }}>
                            {{ $priority['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          required
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Describe el problema o servicio solicitado por el cliente...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Diagnosis --}}
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                <textarea id="diagnosis"
                          name="diagnosis"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Diagnóstico inicial del técnico (opcional)...">{{ old('diagnosis') }}</textarea>
                @error('diagnosis')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Estimated Cost + Estimated Completion Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-1">Costo Estimado</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                        <input type="number"
                               id="estimated_cost"
                               name="estimated_cost"
                               value="{{ old('estimated_cost') }}"
                               min="0"
                               step="0.01"
                               class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="0.00">
                    </div>
                    @error('estimated_cost')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="estimated_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Estimada de Entrega</label>
                    <input type="date"
                           id="estimated_completion_date"
                           name="estimated_completion_date"
                           value="{{ old('estimated_completion_date') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('estimated_completion_date')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Notas adicionales...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.ordenes.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Crear Orden
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
