@extends('layouts.app')

@section('title', 'Reporte #' . $report->id)
@section('page-title', 'Detalle de Reporte')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Reportes
        </a>
    </div>

    {{-- Report Header Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Reporte #{{ $report->id }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Fecha: {{ $report->report_date ? $report->report_date->format('d/m/Y') : $report->created_at->format('d/m/Y') }}
                    @if($report->created_at)
                        &middot; Creado: {{ $report->created_at->format('d/m/Y H:i') }}
                    @endif
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ number_format($report->labor_hours ?? 0, 1) }} horas de trabajo
            </span>
        </div>

        {{-- Description & Work Performed --}}
        <div class="p-6 space-y-4">
            <div>
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Descripción</h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $report->description ?? 'Sin descripción' }}</p>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Trabajo Realizado</h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $report->work_performed ?? 'Sin detalle de trabajo' }}</p>
            </div>
        </div>
    </div>

    {{-- Related Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Service Order & Vehicle Card --}}
        @if($report->serviceOrder)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
                </svg>
                Orden de Servicio
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Orden</span>
                    <a href="{{ route('admin.ordenes.show', $report->serviceOrder) }}" class="text-sm font-mono font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        {{ $report->serviceOrder->order_number }}
                    </a>
                </div>
                @if($report->serviceOrder->vehicle)
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Vehículo</h4>
                    <div class="space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Placa</span>
                            <span class="text-sm font-mono font-semibold text-gray-900">{{ $report->serviceOrder->vehicle->plate }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Vehículo</span>
                            <span class="text-sm text-gray-900">{{ $report->serviceOrder->vehicle->brand }} {{ $report->serviceOrder->vehicle->model }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-500">Año</span>
                            <span class="text-sm text-gray-900">{{ $report->serviceOrder->vehicle->year ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Technician Card --}}
        @if($report->technician)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                </svg>
                Técnico
            </h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($report->technician->name, 0, 1)) }}
                </div>
                <span class="text-sm font-medium text-gray-900">{{ $report->technician->name }}</span>
            </div>
        </div>
        @endif

    </div>

    {{-- Back Button --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Reportes
        </a>
    </div>

</div>
@endsection
