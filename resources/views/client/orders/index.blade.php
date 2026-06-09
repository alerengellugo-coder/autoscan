@extends('layouts.app')

@section('title', 'Mis Órdenes')
@section('page-title', 'Mis Órdenes de Servicio')

@section('content')
<div class="space-y-6">

    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                {{ $orders->count() }} orden{{ $orders->count() !== 1 ? 'es' : '' }} encontrada{{ $orders->count() !== 1 ? 's' : '' }}
            </h2>
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
                        <th class="px-5 py-3 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('client.orders.show', $order['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                {{ $order['order_number'] }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            @if($order['vehicle'])
                                {{ $order['vehicle']['brand'] ?? '' }} {{ $order['vehicle']['model'] ?? '' }}
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
                            {{ $order['technician']['name'] ?? 'Sin asignar' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ isset($order['created_at']) && $order['created_at'] ? \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('client.orders.show', $order['id']) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs font-medium">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
                            </svg>
                            <p class="text-sm">No tienes órdenes de servicio.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="flex items-center justify-center gap-1">
        {{ $orders->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
