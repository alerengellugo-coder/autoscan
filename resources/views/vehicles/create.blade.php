@extends('layouts.app')

@section('title', 'Crear Vehículo')
@section('page-title', 'Crear Vehículo')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.vehiculos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Vehículos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Nuevo Vehículo</h2>
            <p class="text-sm text-gray-500 mt-1">Completa los campos para registrar un nuevo vehículo en el sistema.</p>
        </div>

        <form method="POST" action="{{ route('admin.vehiculos.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Cliente --}}
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

            {{-- Marca --}}
            <div>
                <label for="brand_select" class="block text-sm font-medium text-gray-700 mb-1">Marca <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <select id="brand_select"
                            name="brand"
                            required
                            class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar marca</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand }}" {{ old('brand') == $brand ? 'selected' : '' }}>
                                {{ $brand }}
                            </option>
                        @endforeach
                        <option value="__custom__">Otra (especificar abajo)</option>
                    </select>
                    <input type="text"
                           id="brand_custom"
                           name="brand_custom"
                           value="{{ old('brand_custom') }}"
                           placeholder="Otra marca..."
                           class="w-48 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                @error('brand')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Modelo + Año --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modelo <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="model"
                           name="model"
                           value="{{ old('model') }}"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: Corolla, Civic, F-150">
                    @error('model')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Año <span class="text-red-500">*</span></label>
                    <input type="number"
                           id="year"
                           name="year"
                           value="{{ old('year') }}"
                           required
                           min="1900"
                           max="{{ date('Y') + 1 }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: 2024">
                    @error('year')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Placa + Color --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="plate" class="block text-sm font-medium text-gray-700 mb-1">Placa <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="plate"
                           name="plate"
                           value="{{ old('plate') }}"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors uppercase"
                           placeholder="Ej: ABC-123">
                    @error('plate')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text"
                           id="color"
                           name="color"
                           value="{{ old('color') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: Blanco, Negro, Rojo">
                    @error('color')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- VIN --}}
            <div>
                <label for="vin" class="block text-sm font-medium text-gray-700 mb-1">VIN (Número de Identificación del Vehículo)</label>
                <input type="text"
                       id="vin"
                       name="vin"
                       value="{{ old('vin') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors uppercase font-mono"
                       placeholder="Ej: 1HGBH41JXMN109186">
                @error('vin')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Kilometraje + Tipo de Motor --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">Kilometraje</label>
                    <input type="number"
                           id="mileage"
                           name="mileage"
                           value="{{ old('mileage') }}"
                           min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: 50000">
                    @error('mileage')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="engine_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Motor</label>
                    <select id="engine_type"
                            name="engine_type"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar tipo</option>
                        <option value="gasoline" {{ old('engine_type') == 'gasoline' ? 'selected' : '' }}>Gasolina</option>
                        <option value="diesel" {{ old('engine_type') == 'diesel' ? 'selected' : '' }}>Diésel</option>
                        <option value="electric" {{ old('engine_type') == 'electric' ? 'selected' : '' }}>Eléctrico</option>
                        <option value="hybrid" {{ old('engine_type') == 'hybrid' ? 'selected' : '' }}>Híbrido</option>
                    </select>
                    @error('engine_type')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Transmisión --}}
            <div>
                <label for="transmission" class="block text-sm font-medium text-gray-700 mb-1">Transmisión</label>
                <select id="transmission"
                        name="transmission"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Seleccionar transmisión</option>
                    <option value="automatic" {{ old('transmission') == 'automatic' ? 'selected' : '' }}>Automática</option>
                    <option value="manual" {{ old('transmission') == 'manual' ? 'selected' : '' }}>Manual</option>
                    <option value="cvt" {{ old('transmission') == 'cvt' ? 'selected' : '' }}>CVT</option>
                </select>
                @error('transmission')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Notas --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea id="notes"
                          name="notes"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Observaciones adicionales sobre el vehículo...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.vehiculos.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Crear Vehículo
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    const brandSelect = document.getElementById('brand_select');
    const brandCustom = document.getElementById('brand_custom');

    brandSelect.addEventListener('change', function() {
        if (this.value === '__custom__') {
            brandCustom.disabled = false;
            brandCustom.focus();
            brandCustom.required = true;
            brandSelect.required = false;
        } else {
            brandCustom.disabled = true;
            brandCustom.value = '';
            brandCustom.required = false;
            brandSelect.required = true;
        }
    });

    // If custom brand was previously entered, keep it enabled
    @if(old('brand_custom'))
        brandSelect.value = '__custom__';
        brandCustom.disabled = false;
    @endif
</script>
@endpush
@endsection
