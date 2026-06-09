@extends('layouts.app')

@section('title', 'Dashboard Técnico')
@section('page-title', 'Dashboard Técnico')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        {{-- Órdenes Asignadas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Órdenes Asignadas</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['assigned_orders']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Órdenes Activas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Órdenes Activas</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['active_orders']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completadas Hoy --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Completadas Hoy</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['completed_today']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22,4 12,14.01 9,11.01"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Diagnósticos Pendientes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Diagnósticos Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending_diagnostics']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Orders Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Órdenes Activas</h2>
            <a href="{{ route('technician.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                Ver todas →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium"># Orden</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Prioridad</th>
                        <th class="px-5 py-3 font-medium">Cliente</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($active_orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('technician.orders.show', $order['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                {{ $order['order_number'] }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            @if($order['vehicle'])
                                {{ $order['vehicle']['brand'] }} {{ $order['vehicle']['model'] }}
                                <span class="text-gray-400">({{ $order['vehicle']['plate'] }})</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $order['status_label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-700',
                                    'normal' => 'bg-blue-100 text-blue-700',
                                    'medium' => 'bg-yellow-100 text-yellow-700',
                                    'high' => 'bg-orange-100 text-orange-700',
                                    'urgent' => 'bg-red-100 text-red-700',
                                ];
                                $pColorClass = $priorityColors[$order['priority']] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pColorClass }}">
                                {{ $order['priority_label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $order['client']['name'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ isset($order['created_at']) && $order['created_at'] ? \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                            No hay órdenes activas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Reports List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Reportes Recientes</h2>
            <a href="{{ route('technician.reports.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                Ver todos →
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recent_reports as $report)
            <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('technician.reports.show', $report['id']) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">
                            {{ $report['description'] ?? 'Reporte sin descripción' }}
                        </a>
                        <div class="mt-1 flex items-center gap-4 text-xs text-gray-500">
                            <span>
                                {{ $report['service_order']['order_number'] ?? '—' }}
                                —
                                {{ $report['service_order']['vehicle']['plate'] ?? '' }}
                            </span>
                            @if($report['labor_hours'])
                                <span>{{ number_format($report['labor_hours'], 1) }} hrs</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4 flex-shrink-0">
                        <span class="text-xs text-gray-400">
                            {{ isset($report['created_at']) && $report['created_at'] ? \Carbon\Carbon::parse($report['created_at'])->format('d/m/Y') : '—' }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400">
                No hay reportes recientes.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
