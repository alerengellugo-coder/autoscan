@extends('layouts.app')

@section('title', 'Editar Vehículo')
@section('page-title', 'Editar Vehículo')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Back button --}}
    <div>
        <a href="{{ route('client.vehicles.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Mis Vehículos
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Editar Vehículo</h2>
            <p class="mt-1 text-sm text-gray-500">{{ $vehicle['brand'] }} {{ $vehicle['model'] }} — {{ $vehicle['plate'] }}</p>
        </div>
        <form method="POST" action="{{ route('client.vehicles.update', $vehicle['id']) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Brand --}}
            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Marca <span class="text-red-500">*</span></label>
                <input type="text" id="brand" name="brand" required
                    value="{{ old('brand', $vehicle['brand']) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('brand') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                    placeholder="Ej: Toyota">
                @error('brand')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Model --}}
            <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modelo <span class="text-red-500">*</span></label>
                <input type="text" id="model" name="model" required
                    value="{{ old('model', $vehicle['model']) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('model') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                    placeholder="Ej: Corolla">
                @error('model')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Year --}}
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Año <span class="text-red-500">*</span></label>
                    <input type="number" id="year" name="year" required min="1900" max="{{ date('Y') + 1 }}"
                        value="{{ old('year', $vehicle['year']) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('year') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                        placeholder="Ej: 2023">
                    @error('year')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Plate --}}
                <div>
                    <label for="plate" class="block text-sm font-medium text-gray-700 mb-1">Placa <span class="text-red-500">*</span></label>
                    <input type="text" id="plate" name="plate" required
                        value="{{ old('plate', $vehicle['plate']) }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('plate') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                        placeholder="Ej: ABC-123">
                    @error('plate')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Color --}}
            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="text" id="color" name="color"
                    value="{{ old('color', $vehicle['color']) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('color') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                    placeholder="Ej: Blanco">
                @error('color')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- VIN --}}
            <div>
                <label for="vin" class="block text-sm font-medium text-gray-700 mb-1">VIN (Número de Serie)</label>
                <input type="text" id="vin" name="vin"
                    value="{{ old('vin', $vehicle['vin']) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('vin') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                    placeholder="Ej: 1HGCM82633A004352">
                @error('vin')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mileage --}}
            <div>
                <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">Kilometraje</label>
                <input type="number" id="mileage" name="mileage" min="0"
                    value="{{ old('mileage', $vehicle['mileage']) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('mileage') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                    placeholder="Ej: 50000">
                @error('mileage')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Engine Type --}}
                <div>
                    <label for="engine_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Motor</label>
                    <select id="engine_type" name="engine_type"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors bg-white {{ $errors->has('engine_type') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}">
                        <option value="" {{ !$vehicle['engine_type'] && !old('engine_type') ? 'selected' : '' }}>Seleccionar...</option>
                        <option value="gasolina" {{ ($vehicle['engine_type'] ?? old('engine_type')) === 'gasolina' ? 'selected' : '' }}>Gasolina</option>
                        <option value="diesel" {{ ($vehicle['engine_type'] ?? old('engine_type')) === 'diesel' ? 'selected' : '' }}>Diésel</option>
                        <option value="electrico" {{ ($vehicle['engine_type'] ?? old('engine_type')) === 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                        <option value="hibrido" {{ ($vehicle['engine_type'] ?? old('engine_type')) === 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                    </select>
                    @error('engine_type')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Transmission --}}
                <div>
                    <label for="transmission" class="block text-sm font-medium text-gray-700 mb-1">Transmisión</label>
                    <select id="transmission" name="transmission"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors bg-white {{ $errors->has('transmission') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}">
                        <option value="" {{ !$vehicle['transmission'] && !old('transmission') ? 'selected' : '' }}>Seleccionar...</option>
                        <option value="automatica" {{ ($vehicle['transmission'] ?? old('transmission')) === 'automatica' ? 'selected' : '' }}>Automática</option>
                        <option value="manual" {{ ($vehicle['transmission'] ?? old('transmission')) === 'manual' ? 'selected' : '' }}>Manual</option>
                        <option value="cvt" {{ ($vehicle['transmission'] ?? old('transmission')) === 'cvt' ? 'selected' : '' }}>CVT</option>
                    </select>
                    @error('transmission')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('notes') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                    placeholder="Notas adicionales sobre el vehículo">{{ old('notes', $vehicle['notes']) }}</textarea>
                @error('notes')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('client.vehicles.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20,6 9,17 4,12"/>
                    </svg>
                    Actualizar Vehículo
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
