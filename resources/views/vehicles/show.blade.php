@extends('layouts.app')

@section('title', $vehicle->brand . ' ' . $vehicle->model)
@section('page-title', 'Detalle de Vehículo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.vehiculos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Vehículos
        </a>
    </div>

    {{-- Vehicle Detail Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
                <p class="text-sm text-gray-500 mt-0.5 font-mono">Placa: {{ $vehicle->plate }} &middot; Año: {{ $vehicle->year }}</p>
            </div>
            @switch($vehicle->status)
                @case('active')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Activo</span>
                    @break
                @case('in_progress')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">En Proceso</span>
                    @break
                @case('completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Completado</span>
                    @break
                @case('pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                    @break
                @case('cancelled')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Cancelado</span>
                    @break
                @default
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ $vehicle->status }}</span>
                    @break
            @endswitch
        </div>

        {{-- Details --}}
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">

                {{-- Color --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Color</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->color ?? '—' }}</dd>
                </div>

                {{-- VIN --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">VIN</dt>
                    <dd class="mt-1 text-sm font-mono text-gray-900">{{ $vehicle->vin ?? '—' }}</dd>
                </div>

                {{-- Kilometraje --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kilometraje</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->mileage ? number_format($vehicle->mileage) . ' km' : '—' }}</dd>
                </div>

                {{-- Tipo de Motor --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipo de Motor</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @switch($vehicle->engine_type)
                            @case('gasoline') Gasolina @break
                            @case('diesel') Diésel @break
                            @case('electric') Eléctrico @break
                            @case('hybrid') Híbrido @break
                            @default {{ $vehicle->engine_type ?? '—' }} @break
                        @endswitch
                    </dd>
                </div>

                {{-- Transmisión --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Transmisión</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @switch($vehicle->transmission)
                            @case('automatic') Automática @break
                            @case('manual') Manual @break
                            @case('cvt') CVT @break
                            @default {{ $vehicle->transmission ?? '—' }} @break
                        @endswitch
                    </dd>
                </div>

                {{-- Fecha de Registro --}}
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha de Registro</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->created_at->format('d/m/Y') }}</dd>
                </div>

            </div>

            {{-- Notas --}}
            @if($vehicle->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Notas</dt>
                <dd class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $vehicle->notes }}</dd>
            </div>
            @endif
        </div>

        {{-- Owner Info --}}
        @if($vehicle->client)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($vehicle->client->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Propietario</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $vehicle->client->name }}</p>
                    @if($vehicle->client->email)
                        <p class="text-xs text-gray-500">{{ $vehicle->client->email }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <a href="{{ route('admin.vehiculos.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15,18 9,12 15,6"/>
                </svg>
                Volver
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.vehiculos.edit', $vehicle) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('admin.vehiculos.destroy', $vehicle) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este vehículo? Esta acción no se puede deshacer.');">
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
