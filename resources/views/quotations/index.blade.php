@extends('layouts.app')

@section('title', 'Cotizaciones')
@section('page-title', 'Cotizaciones')

@section('content')
<div class="space-y-6">

    {{-- Filter Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.cotizaciones.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label for="filter_status" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Estado</label>
                <select id="filter_status"
                        name="status"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="" {{ empty($filters['status'] ?? '') ? 'selected' : '' }}>Todos los estados</option>
                    @foreach($status_options as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['status'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('admin.cotizaciones.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- Quotations Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Lista de Cotizaciones</h2>
            <a href="{{ route('admin.cotizaciones.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nueva Cotización
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">#Cotización</th>
                        <th class="px-5 py-3 font-medium">Cliente</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Válida Hasta</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($quotations as $quotation)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.cotizaciones.show', $quotation) }}" class="font-mono font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                {{ $quotation->quotation_number }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">{{ $quotation->client['name'] ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <div>
                                <span class="font-medium text-gray-900">{{ $quotation->vehicle['plate'] ?? '—' }}</span>
                                <p class="text-xs text-gray-500">{{ $quotation->vehicle['brand'] ?? '' }} {{ $quotation->vehicle['model'] ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-900">${{ number_format($quotation->total, 2) }}</td>
                        <td class="px-5 py-3">
                            @switch($quotation->status?->value)
                                @case('draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700">{{ $quotation->status_label ?? 'Borrador' }}</span>
                                    @break
                                @case('pending_client')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $quotation->status_label ?? 'Pendiente' }}</span>
                                    @break
                                @case('approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $quotation->status_label ?? 'Aprobada' }}</span>
                                    @break
                                @case('rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $quotation->status_label ?? 'Rechazada' }}</span>
                                    @break
                                @case('expired')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $quotation->status_label ?? 'Expirada' }}</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $quotation->status_label ?? $quotation->status }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-5 py-3 text-gray-700 text-xs">
                            {{ $quotation->valid_until ? $quotation->valid_until->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $quotation->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.cotizaciones.show', $quotation) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-colors" title="Ver">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Ver
                                </a>
                                <a href="{{ route('admin.cotizaciones.pdf', $quotation) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-orange-600 hover:text-white hover:bg-orange-600 rounded-lg transition-colors" title="PDF">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
                                    </svg>
                                    PDF
                                </a>
                                @if($quotation->status == 'approved')
                                <form method="POST" action="{{ route('admin.cotizaciones.convert-to-sale', $quotation) }}" class="inline-flex" onsubmit="return confirm('¿Convertir esta cotización en venta?')">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-600 hover:text-white hover:bg-green-600 rounded-lg transition-colors" title="Convertir a Venta">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="9,11 12,14 22,4"/>
                                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                                        </svg>
                                        Convertir
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                            </svg>
                            <p class="text-sm">No se encontraron cotizaciones.</p>
                            <a href="{{ route('admin.cotizaciones.create') }}" class="inline-flex items-center gap-1 mt-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Crear primera cotización
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($quotations->hasPages())
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $quotations->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
