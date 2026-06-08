@extends('layouts.app')

@section('title', 'Vehículo - '.$vehicle['plate'])
@section('page-title', 'Detalle de Vehículo')

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

    {{-- Vehicle Detail Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</h2>
                    <p class="text-blue-100 text-sm mt-1">{{ $vehicle['year'] ?? '' }} · {{ $vehicle['plate'] }}</p>
                </div>
                @php
                    $statusColors = [
                        'active' => 'bg-green-400 text-green-900',
                        'inactive' => 'bg-gray-400 text-gray-900',
                        'in_service' => 'bg-yellow-400 text-yellow-900',
                    ];
                    $sColorClass = $statusColors[$vehicle['status']] ?? 'bg-gray-400 text-gray-900';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $sColorClass }}">
                    {{ $vehicle['status_label'] ?? ucfirst($vehicle['status']) }}
                </span>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6">
            <div class="grid grid-cols-2 gap-y-5 gap-x-8">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Marca</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle['brand'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Modelo</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle['model'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Año</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle['year'] ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Placa</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle['plate'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Color</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle['color'] ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Kilometraje</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle['mileage'] ? number_format($vehicle['mileage']) . ' km' : '—' }}</p>
                </div>
                @if($vehicle['vin'])
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">VIN</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 font-mono">{{ $vehicle['vin'] }}</p>
                </div>
                @endif
                @if($vehicle['engine_type'])
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Tipo de Motor</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 capitalize">{{ $vehicle['engine_type'] }}</p>
                </div>
                @endif
                @if($vehicle['transmission'])
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Transmisión</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900 capitalize">{{ $vehicle['transmission'] }}</p>
                </div>
                @endif
            </div>

            @if($vehicle['notes'])
            <div class="mt-6 pt-5 border-t border-gray-200">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Notas</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $vehicle['notes'] }}</p>
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="mt-6 pt-5 border-t border-gray-200 flex items-center gap-3">
                <a href="{{ route('client.vehicles.edit', $vehicle['id']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('client.vehicles.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Volver
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
