@extends('layouts.app')

@section('title', 'Reporte #'.$report['id'])
@section('page-title', 'Detalle de Reporte')

@section('content')
<div class="space-y-6">

    {{-- Back button --}}
    <div>
        <a href="{{ route('technician.reports.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Reportes
        </a>
    </div>

    {{-- Report Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Reporte #{{ $report['id'] }}</h2>
                <p class="mt-1 text-sm text-gray-500">Fecha: {{ \Carbon\Carbon::parse($report['report_date'])->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($report['labor_hours'])
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ number_format($report['labor_hours'], 1) }} horas de trabajo
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Service Order Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                </svg>
                Orden de Servicio
            </h3>
            @if($report['service_order'] ?? $report['serviceOrder'])
            @php
                $so = $report['service_order'] ?? $report['serviceOrder'];
            @endphp
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Número</dt>
                    <dd class="font-medium text-blue-600">
                        <a href="{{ route('technician.orders.show', $so['id']) }}">{{ $so['order_number'] }}</a>
                    </dd>
                </div>
            </dl>
            @else
            <p class="text-sm text-gray-400">Sin orden asociada.</p>
            @endif
        </div>

        {{-- Vehicle Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
                    <circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                </svg>
                Vehículo
            </h3>
            @php
                $vehicle = ($report['service_order']['vehicle'] ?? null) ?? ($report['serviceOrder']['vehicle'] ?? null);
            @endphp
            @if($vehicle)
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Placa</dt>
                    <dd class="font-medium text-gray-900">{{ $vehicle['plate'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Marca</dt>
                    <dd class="font-medium text-gray-900">{{ $vehicle['brand'] ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Modelo</dt>
                    <dd class="font-medium text-gray-900">{{ $vehicle['model'] ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Año</dt>
                    <dd class="font-medium text-gray-900">{{ $vehicle['year'] ?? '—' }}</dd>
                </div>
            </dl>
            @else
            <p class="text-sm text-gray-400">Sin vehículo asociado.</p>
            @endif
        </div>

        {{-- Technician Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Técnico
            </h3>
            @if($report['technician'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium text-gray-900">{{ $report['technician']['name'] }}</dd>
                </div>
                @if($report['technician']['email'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $report['technician']['email'] }}</dd>
                </div>
                @endif
            </dl>
            @else
            <p class="text-sm text-gray-400">Técnico no disponible.</p>
            @endif
        </div>

    </div>

    {{-- Report Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Detalle del Reporte</h3>
        </div>
        <div class="p-6 space-y-6">

            {{-- Description --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Descripción</h4>
                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-lg p-4">
                    {{ $report['description'] ?? 'Sin descripción.' }}
                </p>
            </div>

            {{-- Work Performed --}}
            @if($report['work_performed'])
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Trabajo Realizado</h4>
                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-lg p-4">
                    {{ $report['work_performed'] }}
                </p>
            </div>
            @endif

            {{-- Findings --}}
            @if($report['findings'])
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Hallazgos</h4>
                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-lg p-4">
                    {{ $report['findings'] }}
                </p>
            </div>
            @endif

            {{-- Recommendations --}}
            @if($report['recommendations'])
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Recomendaciones</h4>
                <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 rounded-lg p-4">
                    {{ $report['recommendations'] }}
                </p>
            </div>
            @endif

            {{-- Parts Used --}}
            @if(isset($report['parts_used']) && count($report['parts_used']) > 0)
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Repuestos Utilizados</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 font-medium text-left">Descripción</th>
                                <th class="px-4 py-2 font-medium text-right">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($report['parts_used'] as $part)
                            <tr>
                                <td class="px-4 py-2 text-gray-700">{{ $part['description'] ?? $part }}</td>
                                <td class="px-4 py-2 text-gray-700 text-right">{{ $part['quantity'] ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection
