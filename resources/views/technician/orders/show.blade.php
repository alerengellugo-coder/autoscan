@extends('layouts.app')

@section('title', 'Orden #'.$order['order_number'])
@section('page-title', 'Detalle de Orden')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<div class="space-y-6">

    {{-- Back button --}}
    <div>
        <a href="{{ route('technician.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Órdenes
        </a>
    </div>

    {{-- Order Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Orden {{ $order['order_number'] }}</h2>
                <p class="mt-1 text-sm text-gray-500">Creada el {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center gap-3">
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
                    $priorityColors = [
                        'low' => 'bg-gray-100 text-gray-700',
                        'normal' => 'bg-blue-100 text-blue-700',
                        'medium' => 'bg-yellow-100 text-yellow-700',
                        'high' => 'bg-orange-100 text-orange-700',
                        'urgent' => 'bg-red-100 text-red-700',
                    ];
                    $pColorClass = $priorityColors[$order['priority']] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                    {{ $order['status_label'] }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $pColorClass }}">
                    {{ $order['priority_label'] ?? $order['priority'] }}
                </span>
            </div>
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
                Información del Vehículo
            </h3>
            @if($order['vehicle'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Placa</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['plate'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Marca</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['brand'] }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Modelo</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['model'] }}</dd>
                </div>
                @if($order['vehicle']['year'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Año</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['year'] }}</dd>
                </div>
                @endif
                @if($order['vehicle']['color'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Color</dt>
                    <dd class="font-medium text-gray-900">{{ $order['vehicle']['color'] }}</dd>
                </div>
                @endif
            </dl>
            @else
            <p class="text-sm text-gray-400">Vehículo no asignado.</p>
            @endif
        </div>

        {{-- Client Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Información del Cliente
            </h3>
            @if($order['client'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium text-gray-900">{{ $order['client']['name'] }}</dd>
                </div>
                @if($order['client']['email'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $order['client']['email'] }}</dd>
                </div>
                @endif
                @if($order['client']['phone'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Teléfono</dt>
                    <dd class="font-medium text-gray-900">{{ $order['client']['phone'] }}</dd>
                </div>
                @endif
            </dl>
            @else
            <p class="text-sm text-gray-400">Cliente no asignado.</p>
            @endif
        </div>

    </div>

    {{-- Reports --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14,2 14,8 20,8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
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
            <p class="text-sm">No hay reportes para esta orden.</p>
        </div>
        @endif
    </div>

    {{-- Create Report Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Agregar Reporte de Servicio
            </h3>
        </div>
        <form method="POST" action="{{ route('technician.orders.reports.store', $order['id']) }}" class="p-6 space-y-5">
            @csrf

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('description') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                    placeholder="Descripción del diagnóstico o trabajo realizado">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Work Performed --}}
            <div>
                <label for="work_performed" class="block text-sm font-medium text-gray-700 mb-1">Trabajo Realizado</label>
                <textarea id="work_performed" name="work_performed" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('work_performed') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                    placeholder="Detalle del trabajo ejecutado">{{ old('work_performed') }}</textarea>
                @error('work_performed')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Labor Hours --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="labor_hours" class="block text-sm font-medium text-gray-700 mb-1">Horas de Mano de Obra</label>
                    <input type="number" id="labor_hours" name="labor_hours" step="0.5" min="0"
                        value="{{ old('labor_hours') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('labor_hours') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                        placeholder="0.0">
                    @error('labor_hours')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Findings --}}
            <div>
                <label for="findings" class="block text-sm font-medium text-gray-700 mb-1">Hallazgos</label>
                <textarea id="findings" name="findings" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('findings') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                    placeholder="Hallazgos del diagnóstico">{{ old('findings') }}</textarea>
                @error('findings')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Recommendations --}}
            <div>
                <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-1">Recomendaciones</label>
                <textarea id="recommendations" name="recommendations" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('recommendations') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                    placeholder="Recomendaciones para el cliente">{{ old('recommendations') }}</textarea>
                @error('recommendations')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Parts Used (dynamic rows) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Repuestos Utilizados</label>
                <div id="parts-container" class="space-y-3">
                    <div class="parts-row flex flex-col sm:flex-row items-start sm:items-end gap-3">
                        <div class="flex-1 w-full">
                            <input type="text" name="parts_used[0][description]" value="{{ old('parts_used.0.description') }}" placeholder="Descripción del repuesto"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
                        </div>
                        <div class="w-full sm:w-24">
                            <input type="number" name="parts_used[0][quantity]" value="{{ old('parts_used.0.quantity', 1) }}" min="1" placeholder="Cant."
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
                        </div>
                        <button type="button" onclick="removePartsRow(this)" class="p-2.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addPartsRow()" class="mt-3 inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Agregar repuesto
                </button>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20,6 9,17 4,12"/>
                    </svg>
                    Guardar Reporte
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    let partsIndex = 1;

    function addPartsRow() {
        const container = document.getElementById('parts-container');
        const row = document.createElement('div');
        row.className = 'parts-row flex flex-col sm:flex-row items-start sm:items-end gap-3';
        row.innerHTML = `
            <div class="flex-1 w-full">
                <input type="text" name="parts_used[${partsIndex}][description]" placeholder="Descripción del repuesto"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
            </div>
            <div class="w-full sm:w-24">
                <input type="number" name="parts_used[${partsIndex}][quantity]" value="1" min="1" placeholder="Cant."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors">
            </div>
            <button type="button" onclick="removePartsRow(this)" class="p-2.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        `;
        container.appendChild(row);
        partsIndex++;
    }

    function removePartsRow(button) {
        const container = document.getElementById('parts-container');
        if (container.children.length > 1) {
            button.closest('.parts-row').remove();
        }
    }
</script>
@endpush
@endsection
