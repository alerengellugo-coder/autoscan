@extends('layouts.app')

@section('title', 'Reportes de Servicio')
@section('page-title', 'Reportes de Servicio')

@section('content')
<div class="space-y-6">

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <form method="GET" action="{{ route('technician.reports.index') }}" class="flex flex-wrap items-center gap-4">
            <div>
                <label for="search" class="sr-only">Buscar</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Buscar reportes..."
                        class="w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
                </div>
            </div>
            <div>
                <label for="date_from" class="sr-only">Desde</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
            </div>
            <div>
                <label for="date_to" class="sr-only">Hasta</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Filtrar
            </button>
            <a href="{{ route('technician.reports.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-colors">
                Limpiar
            </a>
        </form>
    </div>

    {{-- Reports Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">#</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium">Descripción</th>
                        <th class="px-5 py-3 font-medium">Trabajo Realizado</th>
                        <th class="px-5 py-3 font-medium">Horas</th>
                        <th class="px-5 py-3 font-medium">Orden</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Técnico</th>
                        <th class="px-5 py-3 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500">{{ $report['id'] }}</td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($report['report_date'])->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3 text-gray-900 max-w-xs truncate">
                            {{ $report['description'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-700 max-w-xs truncate">
                            {{ $report['work_performed'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $report['labor_hours'] ? number_format($report['labor_hours'], 1) : '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('technician.orders.show', $report['service_order']['id'] ?? $report['serviceOrder']['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                {{ $report['service_order']['order_number'] ?? $report['serviceOrder']['order_number'] ?? '—' }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $report['service_order']['vehicle']['plate'] ?? $report['serviceOrder']['vehicle']['plate'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $report['technician']['name'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('technician.reports.show', $report['id']) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs font-medium">
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
    </div>

    {{-- Pagination --}}
    @if($reports->hasPages())
    <div class="flex items-center justify-center gap-1">
        {{ $reports->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
