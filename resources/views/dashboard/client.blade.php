@extends('layouts.app')

@section('title', 'Mi Panel')
@section('page-title', 'Mi Panel')

@section('content')
<div class="space-y-6">

    {{-- My Vehicles --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
                    <circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                </svg>
                Mis Vehículos
            </h2>
            <a href="{{ route('client.vehicles.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Agregar Vehículo
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @forelse($vehicles as $vehicle)
            <a href="{{ route('client.vehicles.show', $vehicle['id']) }}" class="block bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-blue-200 transition-all group">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
                            <circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $vehicle['full_name'] ?? ($vehicle['brand'] . ' ' . $vehicle['model']) }}</p>
                        <p class="text-xs text-gray-500">{{ $vehicle['plate'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        {{ $vehicle['brand'] }}
                    </span>
                    <span>{{ $vehicle['model'] }}</span>
                    @if($vehicle['year'])
                    <span>{{ $vehicle['year'] }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
                </svg>
                <p class="text-sm">No tienes vehículos registrados.</p>
                <a href="{{ route('client.vehicles.create') }}" class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                    Agregar mi primer vehículo
                </a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Active Orders --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                </svg>
                Órdenes Activas
            </h2>
            <a href="{{ route('client.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                Ver todas →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium"># Orden</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Tipo de Servicio</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Técnico</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($active_orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('client.orders.show', $order['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                {{ $order['order_number'] }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            @if($order['vehicle'])
                                {{ $order['vehicle']['brand'] }} {{ $order['vehicle']['model'] ?? '' }}
                                <span class="text-gray-400">({{ $order['vehicle']['plate'] ?? '' }})</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $order['service_type'] ?? '—' }}
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
                                $colorClass = $statusColors[$order['status']?->value] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                {{ $order['status_label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $order['technician']['name'] ?? '—' }}
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

    {{-- Recent Notifications --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
                Notificaciones Recientes
            </h2>
            <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                Ver todas →
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
            <div class="px-5 py-4 hover:bg-gray-50 transition-colors {{ !$notification['read_at'] ? 'bg-blue-50/30' : '' }}">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex-shrink-0">
                        @if(!$notification['read_at'])
                        <span class="block w-2 h-2 rounded-full bg-blue-500"></span>
                        @else
                        <span class="block w-2 h-2 rounded-full bg-gray-300"></span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-gray-900">
                            {{ $notification['data']['message'] ?? $notification['data']['body'] ?? 'Notificación' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-gray-400">
                No hay notificaciones recientes.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
