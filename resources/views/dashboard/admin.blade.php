@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        {{-- Total Órdenes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Órdenes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_orders']) }}</p>
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

        {{-- Completadas este Mes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Completadas este Mes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['completed_this_month']) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22,4 12,14.01 9,11.01"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Ingresos del Mes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Ingresos del Mes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['monthly_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Órdenes Recientes</h2>
            <a href="{{ route('admin.ordenes.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
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
                    @forelse($recent_orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.ordenes.show', $order['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
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
                                    'diagnosing' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $colorClass = $statusColors[$order['status']->value ?? $order['status']] ?? 'bg-gray-100 text-gray-800';
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
                                $pColorClass = $priorityColors[$order['priority']->value ?? $order['priority']] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pColorClass }}">
                                {{ $order['priority_label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $order['client']['name'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                            No hay órdenes recientes.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Low Stock Alert --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    Alerta de Stock Bajo
                </h2>
                <a href="{{ route('admin.productos.index', ['stock_status' => 'low']) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    Ver todos →
                </a>
            </div>
            @if($low_stock_products->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($low_stock_products as $product)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="min-w-0">
                        <a href="{{ route('admin.productos.show', $product['id']) }}" class="text-sm font-medium text-gray-900 truncate hover:text-blue-600">
                            {{ $product['name'] }}
                        </a>
                    </div>
                    <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                        <span class="text-sm text-gray-500">Mín: {{ $product['min_stock'] }}</span>
                        @if($product['stock'] <= 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Agotado
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                {{ $product['stock'] }} unidades
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-5 py-8 text-center text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-2 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22,4 12,14.01 9,11.01"/>
                </svg>
                <p class="text-sm">Todo el inventario está en buen estado.</p>
            </div>
            @endif
        </div>

        {{-- Recent Quotations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Cotizaciones Recientes</h2>
                <a href="{{ route('admin.cotizaciones.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    Ver todas →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 font-medium"># Cotización</th>
                            <th class="px-5 py-3 font-medium">Estado</th>
                            <th class="px-5 py-3 font-medium">Total</th>
                            <th class="px-5 py-3 font-medium">Cliente</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recent_quotations as $quotation)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.cotizaciones.show', $quotation['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                    {{ $quotation['quotation_number'] }}
                                </a>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $qStatusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'sent' => 'bg-blue-100 text-blue-800',
                                        'converted' => 'bg-green-100 text-green-800',
                                    ];
                                    $qColorClass = $qStatusColors[$quotation['status']->value ?? $quotation['status']] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $qColorClass }}">
                                    {{ $quotation['status_label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-900">
                                ${{ number_format($quotation['total'], 2) }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $quotation['client']['name'] ?? '—' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-400">
                                No hay cotizaciones recientes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
