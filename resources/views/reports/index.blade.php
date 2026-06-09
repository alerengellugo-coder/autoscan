@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes')

@section('content')
<div class="space-y-6">

    {{-- Filter Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col lg:flex-row gap-3 flex-wrap">
            <div class="flex-1 min-w-[180px]">
                <label for="filter_date_from" class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date"
                       id="filter_date_from"
                       name="date_from"
                       value="{{ $filters['date_from'] ?? '' }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div class="flex-1 min-w-[180px]">
                <label for="filter_date_to" class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date"
                       id="filter_date_to"
                       name="date_to"
                       value="{{ $filters['date_to'] ?? '' }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('admin.reports.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- Reports Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Lista de Reportes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium">Descripción</th>
                        <th class="px-5 py-3 font-medium">Trabajo Realizado</th>
                        <th class="px-5 py-3 font-medium text-center">Horas</th>
                        <th class="px-5 py-3 font-medium">Orden</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Técnico</th>
                        <th class="px-5 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-mono font-semibold text-gray-900">{{ $report->id }}</td>
                        <td class="px-5 py-3 text-gray-700 text-xs">
                            {{ $report->report_date?->format('d/m/Y') ?? ($report->created_at?->format('d/m/Y') ?? '—') }}
                        </td>
                        <td class="px-5 py-3 text-gray-700 max-w-[200px] truncate">{{ $report->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-600 max-w-[250px] truncate">{{ $report->work_performed ?? '—' }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $report->labor_hours ?? 0 }}h
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @if($report->serviceOrder)
                                <a href="{{ route('admin.ordenes.show', $report->serviceOrder) }}" class="font-mono font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                    {{ $report->serviceOrder->order_number }}
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($report->serviceOrder && $report->serviceOrder->vehicle)
                                <div>
                                    <span class="font-medium text-gray-900">{{ $report->serviceOrder->vehicle->plate }}</span>
                                    <p class="text-xs text-gray-500">{{ $report->serviceOrder->vehicle->brand }} {{ $report->serviceOrder->vehicle->model }}</p>
                                </div>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-700">{{ $report->technician->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-colors" title="Ver">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                            <p class="text-sm">No se encontraron reportes.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($reports->hasPages())
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $reports->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
