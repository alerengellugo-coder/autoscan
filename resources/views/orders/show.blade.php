@extends('layouts.app')

@section('title', 'Orden #' . $order->order_number)
@section('page-title', 'Detalle de Orden')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.ordenes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Órdenes
        </a>
    </div>

    {{-- Order Header Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Orden #{{ $order->order_number }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Creada el {{ $order->created_at?->format('d/m/Y') ?? '—' }} @ {{ $order->created_at?->format('H:i') ?? '' }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Status Badge --}}
                @switch($order->status)
                    @case('pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Pendiente</span>
                        @break
                    @case('in_progress')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">En Proceso</span>
                        @break
                    @case('completed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Completado</span>
                        @break
                    @case('cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Cancelado</span>
                        @break
                    @default
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ $order->status }}</span>
                        @break
                @endswitch

                {{-- Priority Badge --}}
                @switch($order->priority)
                    @case('low')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">Prioridad: Baja</span>
                        @break
                    @case('normal')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">Prioridad: Normal</span>
                        @break
                    @case('high')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-700">Prioridad: Alta</span>
                        @break
                    @case('urgent')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">Prioridad: Urgente</span>
                        @break
                @endswitch
            </div>
        </div>

        <div class="p-6">
            {{-- Description --}}
            @if($order->description)
            <div class="mb-4">
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Descripción</h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $order->description }}</p>
            </div>
            @endif

            {{-- Diagnosis --}}
            @if($order->diagnosis)
            <div class="mb-4">
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Diagnóstico</h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $order->diagnosis }}</p>
            </div>
            @endif

            {{-- Meta Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 pt-4 border-t border-gray-200">
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipo de Servicio</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $order->service_type }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Costo Estimado</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">${{ $order->estimated_cost ? number_format($order->estimated_cost, 2) : '0.00' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha Est. de Entrega</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->estimated_completion_date ? $order->estimated_completion_date->format('d/m/Y') : '—' }}</dd>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->notes)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Notas</h3>
                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Vehicle & Client Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Vehicle Card --}}
        @if($order->vehicle)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/><circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                </svg>
                Información del Vehículo
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Placa</span>
                    <span class="text-sm font-mono font-semibold text-gray-900">{{ $order->vehicle->plate }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Vehículo</span>
                    <span class="text-sm text-gray-900">{{ $order->vehicle->brand }} {{ $order->vehicle->model }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Año</span>
                    <span class="text-sm text-gray-900">{{ $order->vehicle->year }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">Color</span>
                    <span class="text-sm text-gray-900">{{ $order->vehicle->color ?? '—' }}</span>
                </div>
                @if($order->vehicle->vin)
                <div class="flex justify-between">
                    <span class="text-xs text-gray-500">VIN</span>
                    <span class="text-xs text-gray-900 font-mono">{{ $order->vehicle->vin }}</span>
                </div>
                @endif
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.vehiculos.show', $order->vehicle) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">Ver vehículo completo →</a>
                </div>
            </div>
        </div>
        @endif

        {{-- Client Card --}}
        @if($order->client)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                </svg>
                Información del Cliente
            </h3>
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($order->client->name, 0, 1)) }}
                </div>
                <div class="space-y-2 min-w-0">
                    <div>
                        <span class="text-sm font-semibold text-gray-900">{{ $order->client->name }}</span>
                    </div>
                    @if($order->client->email)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                        </svg>
                        <span class="text-sm text-gray-600 truncate">{{ $order->client->email }}</span>
                    </div>
                    @endif
                    @if($order->client->phone)
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $order->client->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Technician Card --}}
    @if($order->technician)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
            </svg>
            Técnico Asignado
        </h3>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                {{ strtoupper(substr($order->technician->name, 0, 1)) }}
            </div>
            <span class="text-sm font-medium text-gray-900">{{ $order->technician->name }}</span>
        </div>
    </div>
    @endif

    {{-- Status Timeline --}}
    @if(!empty($status_timeline) && count($status_timeline) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Historial de Estado</h3>
        </div>
        <div class="p-6">
            <div class="relative">
                @foreach($status_timeline as $index => $entry)
                @php $entryStatus = $entry['status'] ?? 'pending'; @endphp
                <div class="relative flex gap-4 {{ !$loop->last ? 'pb-8' : '' }}">
                    {{-- Timeline line --}}
                    @if(!$loop->last)
                    <div class="absolute left-[15px] top-8 w-0.5 h-full bg-gray-200"></div>
                    @endif

                    {{-- Dot --}}
                    <div class="relative z-10 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mt-0.5
                        @switch($entryStatus)
                            @case('pending') bg-yellow-100 border-2 border-yellow-400 @break
                            @case('diagnosing') bg-purple-100 border-2 border-purple-400 @break
                            @case('in_progress') bg-blue-100 border-2 border-blue-400 @break
                            @case('waiting_parts') bg-orange-100 border-2 border-orange-400 @break
                            @case('quality_check') bg-indigo-100 border-2 border-indigo-400 @break
                            @case('completed') bg-green-100 border-2 border-green-400 @break
                            @case('delivered') bg-emerald-100 border-2 border-emerald-400 @break
                            @case('cancelled') bg-red-100 border-2 border-red-400 @break
                            @default bg-gray-100 border-2 border-gray-400 @break
                        @endswitch
                    ">
                        @if($loop->first)
                            <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20,6 9,17 4,12"/>
                            </svg>
                        @else
                            <div class="w-2 h-2 rounded-full
                                @switch($entryStatus)
                                    @case('pending') bg-yellow-400 @break
                                    @case('diagnosing') bg-purple-400 @break
                                    @case('in_progress') bg-blue-400 @break
                                    @case('waiting_parts') bg-orange-400 @break
                                    @case('quality_check') bg-indigo-400 @break
                                    @case('completed') bg-green-400 @break
                                    @case('delivered') bg-emerald-400 @break
                                    @case('cancelled') bg-red-400 @break
                                    @default bg-gray-400 @break
                                @endswitch
                            "></div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $entry['label'] ?? ucfirst(str_replace('_', ' ', $entryStatus)) }}
                            </p>
                            <p class="text-xs text-gray-500 flex-shrink-0 ml-4">
                                {{ $entry['date'] ?? '—' }}
                            </p>
                        </div>
                        @if(!empty($entry['user_name']))
                            <p class="text-xs text-gray-400 mt-1">Por: {{ $entry['user_name'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Reports List --}}
    @if($reports && $reports->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Reportes ({{ $reports->count() }})</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($reports as $report)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('admin.reportes.show', $report) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition-colors">
                            Reporte #{{ $report->id }}
                        </a>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $report->created_at?->format('d/m/Y H:i') ?? '—' }}
                            @if($report->technician)
                                &middot; {{ $report->technician->name }}
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('admin.reportes.show', $report) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Ver
                    </a>
                </div>
                @if($report->summary)
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $report->summary }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.ordenes.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver
        </a>
        <div class="flex items-center gap-3">
            {{-- Change Status Button --}}
            <button type="button"
                    onclick="document.getElementById('statusModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9,11 12,14 22,4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
                Cambiar Estado
            </button>
        </div>
    </div>

    {{-- Change Status Modal --}}
    <div id="statusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('statusModal').classList.add('hidden')"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-xl shadow-xl border border-gray-200 w-full max-w-md mx-4 z-10">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Cambiar Estado</h3>
                    <button type="button" onclick="document.getElementById('statusModal').classList.add('hidden')" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-1">Orden <span class="font-mono font-semibold">#{{ $order->order_number }}</span></p>
            </div>

            <form method="POST" action="{{ route('admin.ordenes.update-status', $order) }}" class="p-6 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="new_status" class="block text-sm font-medium text-gray-700 mb-1">Nuevo Estado</label>
                    <select id="new_status"
                            name="status"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar estado</option>
                        @foreach($status_options as $value => $label)
                            <option value="{{ $value }}" {{ $order->status == $value ? 'disabled' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="status_notes" class="block text-sm font-medium text-gray-700 mb-1">Notas del cambio (opcional)</label>
                    <textarea id="status_notes"
                              name="notes"
                              rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                              placeholder="Motivo del cambio de estado..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('statusModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Actualizar Estado
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
