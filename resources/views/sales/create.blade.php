@extends('layouts.app')

@section('title', 'Crear Venta')
@section('page-title', 'Crear Venta')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-2">
        <a href="{{ route('admin.ventas.in@php $_t1 = route('admin.ventas.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Ventas
        </a>
    </div>

    <form method="POST" action="{{ route('admin.ventas.store') }}" id="saleForm">
        @csrf

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="border-b border-gray-200">
                <nav class="flex" role="tablist">
                    <button type="button"
                            id="tab-quotation"
                            onclick="switchTab('quotation')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors border-blue-500 text-blue-600"
                            role="tab"
                            aria-selected="true">
                        <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                        </svg>
                        Desde Cotización
                    </button>
                    <button type="button"
                            id="tab-manual"
                            onclick="switchTab('manual')"
                            class="px-6 py-4 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                            role="tab"
                            aria-selected="false">
                        <svg class="w-4 h-4 inline-block mr-1.5 -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Venta Manual
                    </button>
                </nav>
            </div>

            <div class="p-6 space-y-5">
                {{-- Tab 1: From Quotation --}}
                <div id="panel-quotation">
                    <div>
                        <label for="quotation_id" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Cotización Aprobada</label>
                        <select id="quotation_id"
                                name="quotation_id"
                                onchange="loadQuotationItems(this.value)"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Seleccionar cotización...</option>
                            @foreach($quotations as $quotation)
                                <option value="{{ $quotation->id }}" data-items="{{ json_encode($quotation->items) }}">
                                    {{ $quotation->quotation_number }} — {{ $quotation->client['name'] ?? '—' }} — ${{ number_format($quotation->total, 2) }}
                                </option>
                            @endforeach
                        </select>
                        @error('quotation_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Quotation Items (auto-filled) --}}
                    <div id="quotationItemsContainer" class="hidden mt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Items de la Cotización</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-left">Producto</th>
                                        <th class="px-4 py-2 font-medium text-left">Descripción</th>
                                        <th class="px-4 py-2 font-medium text-center">Cantidad</th>
                                        <th class="px-4 py-2 font-medium text-right">Precio</th>
                                        <th class="px-4 py-2 font-medium text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="quotationItemsBody" class="divide-y divide-gray-100">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Tab 2: Manual --}}
                <div id="panel-manual" class="hidden">
                    <div class="mb-5">
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                        <select id="client_id"
                                name="client_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Seleccionar cliente</option>
                            @foreach($clients as $clientId => $clientName)
                                <option value="{{ $clientId }}" {{ old('c@php $_t1 = old('client_id') == $clientId ? 'selected' : ''; @endphp{{ $_t1 }} $_t4 = $clientId }}" @php $_t0 = old('client_id') == $clientId ? "selected" : ""; @endphp{{ $_t4 }}                          @endforeach
                        </select>
                        @error('client_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Manual Items --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700">Items de la Venta</h3>
                            <button type="button"
                                    onclick="addManualItemRow()"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Agregar Item
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm" id="manualItemsTable">
                                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-left">Producto</th>
                                        <th class="px-3 py-2 font-medium text-left">Descripción</th>
                                        <th class="px-3 py-2 font-medium text-center w-20">Cantidad</th>
                                        <th class="px-3 py-2 font-medium text-right w-28">Precio</th>
                                        <th class="px-3 py-2 font-medium text-right w-28">Total</th>
                                        <th class="px-3 py-2 font-medium text-center w-12"></th>
                                    </tr>
                                </thead>
                                <tbody id="manualItemsBody" class="divide-y divide-gray-100">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <div class="text-sm">
                            <span class="text-gray-600">Total Manual: </span>
                            <span class="font-bold text-blue-600" id="manualTotalDisplay">$0.00</span>
                        </div>
                    </div>
                </div>

                {{-- Common Fields --}}
                <div class="border-t border-gray-200 pt-5 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tasa de Impuesto (%)</label>
                            <input type="number"
                                   id="tax_rate"
                                   name="tax_rate"
                                   value="{{ old('tax_rate', 16) }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('tax_rate')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Método de Pago <span class="text-red-500">*</span></label>
                            <select id="payment_method"
                                    name="payment_method"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="" @php $_t0 = old('payment_method') == '' ? 'selected' : ''; @endphp{{ $_t0 }}>Seleccionar método</opt@php $_t3 = old('payment_method') == '' ? "selected" : ""; @endphp{{ $_t3 }}"cash" @php $_t0 = old('payment_method') == 'cash' ? 'selected' : @php $_t2 = old('payment_method') == 'cash' ? 'selected' : ''; @endphp{ $_t0 } <option value="card" @php $_t1 = old('payment_method') @php $_t1 = old('payment_method') == 'card' ? 'selected' : ''; @endphp{ $_t1 }                         <option value="transfer" @php $_t0 = old('payment_method') == 'transfer' ? "selected" : ""; @endphp{{ $_t0 }}>Transferencia</option>
                            </select>
                            @error('payment_method')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                                  placeholder="Notas adicionales...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.ventas.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Crear Venta
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const productsData = @json($products);
    let manualRowCounter = 0;

    function switchTab(tab) {
        const tabQuotation = document.getElementById('tab-quotation');
        const tabManual = document.getElementById('tab-manual');
        const panelQuotation = document.getElementById('panel-quotation');
        const panelManual = document.getElementById('panel-manual');

        if (tab === 'quotation') {
            tabQuotation.className = 'px-6 py-4 text-sm font-medium border-b-2 transition-colors border-blue-500 text-blue-600';
            tabQuotation.setAttribute('aria-selected', 'true');
            tabManual.className = 'px-6 py-4 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
            tabManual.setAttribute('aria-selected', 'false');
            panelQuotation.classList.remove('hidden');
            panelManual.classList.add('hidden');
        } else {
            tabManual.className = 'px-6 py-4 text-sm font-medium border-b-2 transition-colors border-blue-500 text-blue-600';
            tabManual.setAttribute('aria-selected', 'true');
            tabQuotation.className = 'px-6 py-4 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
            tabQuotation.setAttribute('aria-selected', 'false');
            panelManual.classList.remove('hidden');
            panelQuotation.classList.add('hidden');
        }
    }

    function loadQuotationItems(quotationId) {
        const select = document.getElementById('quotation_id');
        const container = document.getElementById('quotationItemsContainer');
        const tbody = document.getElementById('quotationItemsBody');

        if (!quotationId) {
            container.classList.add('hidden');
            return;
        }

        const option = select.querySelector(`option[value="${quotationId}"]`);
        const items = JSON.parse(option.getAttribute('data-items') || '[]');

        tbody.innerHTML = '';
        items.forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';
            tr.innerHTML = `
                <td class="px-4 py-2 text-gray-900">${item.name || item.product?.name || '—'}</td>
                <td class="px-4 py-2 text-gray-500">${item.description || '—'}</td>
                <td class="px-4 py-2 text-center">${item.quantity}</td>
                <td class="px-4 py-2 text-right">$${parseFloat(item.unit_price).toFixed(2)}</td>
                <td class="px-4 py-2 text-right font-semibold">$${parseFloat(item.total).toFixed(2)}</td>
            `;
            tbody.appendChild(tr);
        });

        container.classList.remove('hidden');
    }

    function addManualItemRow() {
        manualRowCounter++;
        const tbody = document.getElementById('manualItemsBody');
        const tr = document.createElement('tr');
        tr.id = 'manual-row-' + manualRowCounter;
        tr.className = 'hover:bg-gray-50';
        tr.innerHTML = `
            <td class="px-3 py-2">
                <select name="manual_items[${manualRowCounter}][product_id]"
                        onchange="onManualProductSelect(this, ${manualRowCounter})"
                        class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Seleccionar...</option>
                    ${productsData.map(p => `<option value="${p.id}">${p.name}</option>`).join('')}
                </select>
            </td>
            <td class="px-3 py-2">
                <input type="text" name="manual_items[${manualRowCounter}][description]"
                       class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Descripción...">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="manual_items[${manualRowCounter}][quantity]" value="1" min="1" step="1"
                       onchange="calculateManualTotals()"
                       class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="manual_items[${manualRowCounter}][unit_price]" id="manual-price-${manualRowCounter}" value="0" min="0" step="0.01"
                       onchange="calculateManualTotals()"
                       class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs text-right focus:outline-none focus:ring-2 focus:ring-blue-500">
            </td>
            <td class="px-3 py-2 text-right">
                <span class="text-xs font-semibold text-gray-900" id="manual-row-total-${manualRowCounter}">$0.00</span>
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" onclick="removeManualRow(${manualRowCounter})" class="p-1 text-red-400 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function onManualProductSelect(selectEl, rowId) {
        const product = productsData.find(p => p.id == selectEl.value);
        const priceInput = document.getElementById('manual-price-' + rowId);
        if (product) {
            priceInput.value = product.price;
        } else {
            priceInput.value = '0';
        }
        calculateManualTotals();
    }

    function calculateManualTotals() {
        let total = 0;
        for (let i = 1; i <= manualRowCounter; i++) {
            const row = document.getElementById('manual-row-' + i);
            if (!row) continue;
            const qty = parseFloat(row.querySelector(`input[name="manual_items[${i}][quantity]"]`)?.value || 0);
            const price = parseFloat(row.querySelector(`input[name="manual_items[${i}][unit_price]"]`)?.value || 0);
            const rowTotal = qty * price;
            const totalEl = document.getElementById('manual-row-total-' + i);
            if (totalEl) totalEl.textContent = '$' + rowTotal.toFixed(2);
            total += rowTotal;
        }
        document.getElementById('manualTotalDisplay').textContent = '$' + total.toFixed(2);
    }

    function removeManualRow(rowId) {
        const row = document.getElementById('manual-row-' + rowId);
        if (row) {
            row.remove();
            calculateManualTotals();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        addManualItemRow();
    });
</script>
@endpush
@endsection
