@extends('layouts.app')

@section('title', 'Órdenes de Servicio')
@section('page-title', 'Órdenes de Servicio')

@section('content')
<div class="space-y-6">

    {{-- Status Count Badges Row --}}
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.ordenes.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ empty($filters['status']) ? 'bg-blue-600 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
            <span class="font-bold">Todas</span>
            <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs {{ empty($filters['status']) ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $orders->total() }}</span>
        </a>
        @foreach($status_counts as $status => $count)
            <a href="{{ route('admin.ordenes.index', array_merge(request()->query(), ['status' => $status])) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ ($filters['status'] ?? '') == $status ? 'bg-blue-600 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                @switch($status)
                    @case('pending')
                        <span class="w-2.5 h-2.5 rounded-full {{ ($filters['status'] ?? '') == $status ? 'bg-yellow-200' : 'bg-yellow-400' }}"></span>
                        @break
                    @case('in_progress')
                        <span class="w-2.5 h-2.5 rounded-full {{ ($filters['status'] ?? '') == $status ? 'bg-blue-200' : 'bg-blue-400' }}"></span>
                        @break
                    @case('completed')
                        <span class="w-2.5 h-2.5 rounded-full {{ ($filters['status'] ?? '') == $status ? 'bg-green-200' : 'bg-green-400' }}"></span>
                        @break
                    @case('cancelled')
                        <span class="w-2.5 h-2.5 rounded-full {{ ($filters['status'] ?? '') == $status ? 'bg-red-200' : 'bg-red-400' }}"></span>
                        @break
                    @default
                        <span class="w-2.5 h-2.5 rounded-full {{ ($filters['status'] ?? '') == $status ? 'bg-gray-200' : 'bg-gray-400' }}"></span>
                        @break
                @endswitch
                <span>{{ ($status_options[$status] ?? ucfirst(str_replace('_', ' ', $status))) }}</span>
                <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs {{ ($filters['status'] ?? '') == $status ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.ordenes.index') }}" class="flex flex-col lg:flex-row gap-3 flex-wrap">
            <div class="flex-1 min-w-[180px]">
                <select name="status"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Todos los estados</option>
                    @foreach($status_options as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['status'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <select name="priority"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Todas las prioridades</option>
                    @foreach($priority_options as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['priority'] ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[180px]">
                <select name="technician"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Todos los técnicos</option>
                    @foreach($technician_options as $tech)
                        <option value="{{ $tech['value'] }}" {{ ($filters['technician'] ?? '') == $tech['value'] ? 'selected' : '' }}>
                            {{ $tech['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[140px]">
                <input type="date"
                       name="date_from"
                       value="{{ $filters['date_from'] ?? '' }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Desde">
            </div>
            <div class="flex-1 min-w-[140px]">
                <input type="date"
                       name="date_to"
                       value="{{ $filters['date_to'] ?? '' }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Hasta">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('admin.ordenes.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Lista de Órdenes</h2>
            <a href="{{ route('admin.ordenes.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nueva Orden
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">#Orden</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Cliente</th>
                        <th class="px-5 py-3 font-medium">Técnico</th>
                        <th class="px-5 py-3 font-medium">Tipo</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Prioridad</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.ordenes.show', $order) }}" class="font-mono font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-5 py-3">
                            <div>
                                <span class="font-medium text-gray-900">{{ $order->vehicle['plate'] ?? '—' }}</span>
                                <p class="text-xs text-gray-500">{{ $order->vehicle['brand'] ?? '' }} {{ $order->vehicle['model'] ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-700">{{ $order->client['name'] ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $order->technician['name'] ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $order->service_type_label ?? $order->service_type }}</td>
                        <td class="px-5 py-3">
                            @switch($order->status)
                                @case('pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $order->status_label ?? 'Pendiente' }}</span>
                                    @break
                                @case('in_progress')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $order->status_label ?? 'En Proceso' }}</span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $order->status_label ?? 'Completado' }}</span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $order->status_label ?? 'Cancelado' }}</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $order->status_label ?? $order->status }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-5 py-3">
                            @switch($order->priority)
                                @case('low')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ $order->priority_label ?? 'Baja' }}</span>
                                    @break
                                @case('normal')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $order->priority_label ?? 'Normal' }}</span>
                                    @break
                                @case('high')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">{{ $order->priority_label ?? 'Alta' }}</span>
                                    @break
                                @case('urgent')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ $order->priority_label ?? 'Urgente' }}</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $order->priority_label ?? $order->priority }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.ordenes.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-colors" title="Ver">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Ver
                                </a>
                                <a href="{{ route('admin.ordenes.edit', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-yellow-600 hover:text-white hover:bg-yellow-600 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            <p class="text-sm">No se encontraron órdenes.</p>
                            <a href="{{ route('admin.ordenes.create') }}" class="inline-flex items-center gap-1 mt-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Crear primera orden
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
