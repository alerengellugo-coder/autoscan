@extends('layouts.app')

@section('title', 'Mis Vehículos')
@section('page-title', 'Mis Vehículos')

@section('content')
<div class="space-y-6">

    {{-- Header with Add button --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <p class="text-sm text-gray-500">
            {{ $vehicles->count() }} vehículo{{ $vehicles->count() !== 1 ? 's' : '' }} registrado{{ $vehicles->count() !== 1 ? 's' : '' }}
        </p>
        <a href="{{ route('client.vehicles.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Agregar Vehículo
        </a>
    </div>

    {{-- Vehicle Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($vehicles as $vehicle)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-5 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white">{{ $vehicle['brand'] }} {{ $vehicle['model'] }}</h3>
                    @php
                        $statusColors = [
                            'active' => 'bg-green-400 text-green-900',
                            'inactive' => 'bg-gray-400 text-gray-900',
                            'in_service' => 'bg-yellow-400 text-yellow-900',
                        ];
                        $sColorClass = $statusColors[$vehicle['status']] ?? 'bg-gray-400 text-gray-900';
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $sColorClass }}">
                        {{ $vehicle['status_label'] }}
                    </span>
                </div>
                <p class="text-xs text-blue-100 mt-1">{{ $vehicle['year'] ?? '' }} · {{ $vehicle['plate'] }}</p>
            </div>
            {{-- Card Body --}}
            <div class="p-5">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-400">Color</p>
                        <p class="font-medium text-gray-900">{{ $vehicle['color'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Placa</p>
                        <p class="font-medium text-gray-900">{{ $vehicle['plate'] }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('client.vehicles.show', $vehicle['id']) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        Ver
                    </a>
                    <a href="{{ route('client.vehicles.edit', $vehicle['id']) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-lg transition-colors">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Editar
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
            </svg>
            <p class="text-base font-medium text-gray-500">No tienes vehículos registrados</p>
            <p class="text-sm mt-1">Agrega tu primer vehículo para comenzar.</p>
            <a href="{{ route('client.vehicles.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Agregar Vehículo
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($vehicles->hasPages())
    <div class="flex items-center justify-center gap-1">
        {{ $vehicles->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
