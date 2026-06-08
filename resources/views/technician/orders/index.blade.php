@extends('layouts.app')

@section('title', 'Órdenes de Servicio')
@section('page-title', 'Órdenes de Servicio')

@section('content')
<div class="space-y-6">

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex flex-wrap items-center gap-4">
            <h3 class="text-sm font-semibold text-gray-700 mr-2">Filtrar por estado:</h3>
            <a href="{{ route('technician.orders.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Todos
            </a>
            @foreach([
                'pending' => 'Pendiente',
                'in_progress' => 'En Progreso',
                'diagnosing' => 'Diagnóstico',
                'completed' => 'Completada',
                'delivered' => 'Entregada',
            ] as $value => $label)
            <a href="{{ route('technician.orders.index', ['status' => $value]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ request('status') === $value ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium"># Orden</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Cliente</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Prioridad</th>
                        <th class="px-5 py-3 font-medium">Técnico</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('technician.orders.show', $order['id']) }}" class="font-medium text-blue-600 hover:text-blue-700">
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
                            {{ $order['client']['name'] ?? '—' }}
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
                                {{ $order['priority_label'] ?? $order['priority'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            {{ $order['technician']['name'] ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('technician.orders.show', $order['id']) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs font-medium">
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
                        <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                            </svg>
                            <p class="text-sm">No se encontraron órdenes.</p>
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
