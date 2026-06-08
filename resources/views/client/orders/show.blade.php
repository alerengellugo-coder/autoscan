@extends('layouts.app')

@section('title', 'Orden #'.$order['order_number'])
@section('page-title', 'Detalle de Orden')

@section('content')
<div class="space-y-6">

    {{-- Back button --}}
    <div>
        <a href="{{ route('client.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Mis Órdenes
        </a>
    </div>

    {{-- Order Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Orden {{ $order['order_number'] }}</h2>
                <p class="mt-1 text-sm text-gray-500">Creada el {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</p>
                @if($order['service_type'])
                <p class="mt-1 text-sm text-gray-500">Tipo de Servicio: <span class="font-medium text-gray-700">{{ $order['service_type'] }}</span></p>
                @endif
            </div>
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'in_progress' => 'bg-blue-100 text-blue-800',
                    'diagnosing' => 'bg-indigo-100 text-indigo-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'delivered' => 'bg-emerald-100 text-emerald-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                ];
                $colorClass = $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                {{ $order['status_label'] }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Vehicle Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
                    <circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                </svg>
                Vehículo
            </h3>
            @if($order['vehicle'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Marca / Modelo</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['brand'] }} {{ $order['vehicle']['model'] ?? '' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Año</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['year'] ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Placa</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['plate'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Color</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['color'] ?? '—' }}</dd>
                </div>
            </dl>
            @else
            <p class="text-sm text-gray-400">Sin información de vehículo.</p>
            @endif
        </div>

        {{-- Technician Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Técnico Asignado
            </h3>
            @if($order['technician'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium text-gray-900">{{ $order['technician']['name'] }}</dd>
                </div>
                @if($order['technician']['email'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $order['technician']['email'] }}</dd>
                </div>
                @endif
                @if($order['technician']['phone'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Teléfono</dt>
                    <dd class="font-medium text-gray-900">{{ $order['technician']['phone'] }}</dd>
                </div>
                @endif
            </dl>
            @else
            <p class="text-sm text-gray-400">Técnico no asignado aún.</p>
            @endif
        </div>

    </div>

    {{-- Status Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Estado de la Orden</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center gap-0">
                @php
                    $statuses = ['pending', 'in_progress', 'diagnosing', 'completed', 'delivered'];
                    $statusLabels = [
                        'pending' => 'Pendiente',
                        'in_progress' => 'En Progreso',
                        'diagnosing' => 'Diagnóstico',
                        'completed' => 'Completada',
                        'delivered' => 'Entregada',
                    ];
                    $currentIdx = array_search($order['status'], $statuses);
                    if ($currentIdx === false) $currentIdx = 0;
                @endphp
                @foreach($statuses as $idx => $status)
                <div class="flex-1 flex flex-col items-center">
                    <div class="flex items-center w-full">
                        @if($idx > 0)
                        <div class="flex-1 h-1 {{ $idx <= $currentIdx ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                        @endif
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $idx <= $currentIdx ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($idx < $currentIdx)
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20,6 9,17 4,12"/>
                            </svg>
                            @elseif($idx === $currentIdx)
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                            </svg>
                            @else
                            <span class="text-xs font-bold">{{ $idx + 1 }}</span>
                            @endif
                        </div>
                        @if($idx < count($statuses) - 1)
                        <div class="flex-1 h-1 {{ $idx < $currentIdx ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                    <p class="mt-2 text-xs font-medium {{ $idx <= $currentIdx ? 'text-blue-600' : 'text-gray-400' }}">
                        {{ $statusLabels[$status] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Reports --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Reportes de Servicio
            </h3>
        </div>
        @if(isset($order['reports']) && count($order['reports']) > 0)
        <div class="divide-y divide-gray-100">
            @foreach($order['reports'] as $report)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $report['description'] ?? 'Reporte' }}</p>
                        @if($report['work_performed'])
                        <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $report['work_performed'] }}</p>
                        @endif
                    </div>
                    <div class="ml-4 flex-shrink-0 text-right">
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($report['report_date'])->format('d/m/Y') }}</p>
                        @if($report['labor_hours'])
                        <p class="text-xs text-gray-500">{{ number_format($report['labor_hours'], 1) }} hrs</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-8 text-center text-gray-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
            </svg>
            <p class="text-sm">No hay reportes para esta orden aún.</p>
        </div>
        @endif
    </div>

</div>
@endsection
