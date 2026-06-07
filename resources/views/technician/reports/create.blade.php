@extends('layouts.app')

@section('title', 'Crear Reporte')
@section('page-title', 'Crear Reporte de Servicio')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('technician.reports.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Reportes
        </a>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Nuevo Reporte de Servicio</h2>
            <p class="text-sm text-gray-500 mt-1">Complete la información del diagnóstico y reparación.</p>
        </div>

        <form method="POST" action="{{ route('technician.orders.reports.store', 0) }}" id="reportForm" class="p-6 space-y-5">
            @csrf

            {{-- Order Selection --}}
            <div>
                <label for="service_order_id" class="block text-sm font-medium text-gray-700 mb-1">Orden de Servicio <span class="text-red-500">*</span></label>
                <select id="service_order_id"
                        name="service_order_id"
                        required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Seleccionar orden</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">
                            #{{ $order->order_number }} - {{ $order->vehicle ? ($order->vehicle->brand . ' ' . $order->vehicle->model . ' (' . $order->vehicle->plate . ')') : 'Sin vehículo' }}
                        </option>
                    @endforeach
                </select>
                @error('service_order_id')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Report Date --}}
            <div>
                <label for="report_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha del Reporte <span class="text-red-500">*</span></label>
                <input type="date"
                       id="report_date"
                       name="report_date"
                       required
                       value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                @error('report_date')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Diagnostic Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico <span class="text-red-500">*</span></label>
                <textarea id="description"
                          name="description"
                          required
                          rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                          placeholder="Describa el diagnóstico realizado..."></textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Repair Description --}}
            <div>
                <label for="work_performed" class="block text-sm font-medium text-gray-700 mb-1">Reparación Realizada</label>
                <textarea id="work_performed"
                          name="work_performed"
                          rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                          placeholder="Describa los trabajos de reparación realizados..."></textarea>
                @error('work_performed')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Labor Hours --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="labor_hours" class="block text-sm font-medium text-gray-700 mb-1">Horas de Mano de Obra</label>
                    <input type="number"
                           id="labor_hours"
                           name="labor_hours"
                           min="0"
                           step="0.5"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="0.0">
                    @error('labor_hours')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Parts Used --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Repuestos Utilizados</label>
                <div id="partsUsed" class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <input type="text" name="parts_used[0][name]" placeholder="Nombre del repuesto" class="sm:col-span-2 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <input type="number" name="parts_used[0][quantity]" placeholder="Cant." min="0.01" step="0.01" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addPart()" class="mt-2 inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Agregar repuesto
                </button>
                @error('parts_used')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Findings --}}
            <div>
                <label for="findings" class="block text-sm font-medium text-gray-700 mb-1">Hallazgos Adicionales</label>
                <textarea id="findings"
                          name="findings"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                          placeholder="Hallazgos adicionales encontrados durante la revisión..."></textarea>
                @error('findings')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Recommendations --}}
            <div>
                <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-1">Recomendaciones</label>
                <textarea id="recommendations"
                          name="recommendations"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                          placeholder="Recomendaciones para el cliente..."></textarea>
                @error('recommendations')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                <textarea id="notes"
                          name="notes"
                          rows="2"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                          placeholder="Notas internas..."></textarea>
                @error('notes')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('technician.reports.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Guardar Reporte
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let partIndex = 1;
    function addPart() {
        const container = document.getElementById('partsUsed');
        const row = document.createElement('div');
        row.className = 'flex items-start gap-3';
        row.innerHTML = `
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" name="parts_used[${partIndex}][name]" placeholder="Nombre del repuesto" class="sm:col-span-2 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <input type="number" name="parts_used[${partIndex}][quantity]" placeholder="Cant." min="0.01" step="0.01" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            <button type="button" onclick="this.closest('div').remove()" class="p-2 text-red-500 hover:text-red-700 transition-colors mt-0.5">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        `;
        container.appendChild(row);
        partIndex++;
    }

    // Set form action dynamically based on selected order
    document.getElementById('service_order_id').addEventListener('change', function() {
        const form = document.getElementById('reportForm');
        const orderId = this.value;
        if (orderId) {
            form.action = `/tecnico/ordenes/${orderId}/reports`;
        }
    });
</script>
@endpush
