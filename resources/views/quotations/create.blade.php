@extends('layouts.app')

@section('title', 'Crear Cotización')
@section('page-title', 'Crear Cotización')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="mb-2">
        <a href="{{ route('admin.cotizaciones.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Cotizaciones
        </a>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Por favor corrige los siguientes errores:</h3>
                <ul class="mt-2 text-sm text-red-600 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.cotizaciones.store') }}" id="quotationForm">
        @csrf

        {{-- Quotation Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Datos de la Cotización</h2>
                <p class="text-sm text-gray-500 mt-1">Selecciona el cliente, vehículo y opciones de la cotización.</p>
            </div>
            <div class="p-6 space-y-5">

                {{-- Client Search --}}
                <div>
                    <label for="client_search" class="block text-sm font-medium text-gray-700 mb-1">Buscar Cliente <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="text"
                               id="client_search"
                               name="client_search"
                               autocomplete="off"
                               placeholder="Buscar por nombre, email, teléfono o cédula..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <div id="client_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                    </div>
                    <div id="client_selected_display" class="hidden mt-2 flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span id="client_selected_name" class="text-sm font-medium text-blue-900"></span>
                        <span id="client_selected_info" class="text-xs text-blue-600"></span>
                        <button type="button" onclick="clearClientSelection()" class="ml-auto text-blue-400 hover:text-blue-600">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" id="client_id" name="client_id" value="">
                    @error('client_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Vehicle (filtered by client) --}}
                <div>
                    <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">Vehículo <span class="text-red-500">*</span></label>
                    <select id="vehicle_id"
                            name="vehicle_id"
                            required
                            disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Primero selecciona un cliente</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Los vehículos se cargan según el cliente seleccionado.</p>
                    @error('vehicle_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Service Order + Valid Until --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="service_order_id" class="block text-sm font-medium text-gray-700 mb-1">Orden de Servicio</label>
                        <select id="service_order_id"
                                name="service_order_id"
                                disabled
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Seleccionar orden (opcional)</option>
                        </select>
                        @error('service_order_id')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">Válida Hasta</label>
                        <input type="date"
                               id="valid_until"
                               name="valid_until"
                               value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('valid_until')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Notes --}}
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                    <textarea id="notes"
                              name="notes"
                              rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                              placeholder="Notas adicionales de la cotización...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tax Rate + Discount --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-1">Tasa de Impuesto (%)</label>
                        <input type="number"
                               id="tax_rate"
                               name="tax_rate"
                               value="{{ old('tax_rate', 16) }}"
                               min="0"
                               max="100"
                               step="0.01"
                               onchange="calculateTotals()"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('tax_rate')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Descuento</label>
                        <input type="number"
                               id="discount"
                               name="discount"
                               value="{{ old('discount', 0) }}"
                               min="0"
                               step="0.01"
                               onchange="calculateTotals()"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('discount')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Descuento</label>
                        <select id="discount_type"
                                name="discount_type"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="percentage" {{ old('discount_type', 'percentage') == 'percentage' ? 'selected' : '' }}>% Porcentaje</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>$ Monto Fijo</option>
                        </select>
                        @error('discount_type')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Items de la Cotización</h2>
                <button type="button"
                        onclick="addItemRow()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Agregar Item
                </button>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm" id="itemsTable">
                        <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 font-medium text-left w-10">#</th>
                                <th class="px-3 py-3 font-medium text-left">Producto</th>
                                <th class="px-3 py-3 font-medium text-left">Nombre</th>
                                <th class="px-3 py-3 font-medium text-left">Descripción</th>
                                <th class="px-3 py-3 font-medium text-center w-20">Cantidad</th>
                                <th class="px-3 py-3 font-medium text-right w-28">Precio Unitario</th>
                                <th class="px-3 py-3 font-medium text-right w-24">Descuento</th>
                                <th class="px-3 py-3 font-medium text-right w-28">Total</th>
                                <th class="px-3 py-3 font-medium text-center w-12"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="divide-y divide-gray-100">
                            {{-- Dynamic rows will be added here --}}
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div class="flex justify-end mt-6">
                    <div class="w-full max-w-xs space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium text-gray-900" id="subtotalDisplay">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Impuesto (<span id="taxRateDisplay">16</span>%):</span>
                            <span class="font-medium text-gray-900" id="taxDisplay">$0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Descuento:</span>
                            <span class="font-medium text-red-600" id="discountDisplay">-$0.00</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2">
                            <div class="flex justify-between text-base">
                                <span class="font-bold text-gray-900">Total:</span>
                                <span class="font-bold text-blue-600 text-lg" id="grandTotalDisplay">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="items_count" id="itemsCount" value="0">
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.cotizaciones.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Crear Cotización
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const productsData = @json($products);

    let rowCounter = 0;
    let searchTimeout = null;

    const clientSearch = document.getElementById('client_search');
    const clientDropdown = document.getElementById('client_dropdown');
    const clientHidden = document.getElementById('client_id');
    const clientDisplay = document.getElementById('client_selected_display');
    const clientNameEl = document.getElementById('client_selected_name');
    const clientInfoEl = document.getElementById('client_selected_info');
    const vehicleSelect = document.getElementById('vehicle_id');
    const soSelect = document.getElementById('service_order_id');

    // Close dropdown on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#client_search') && !e.target.closest('#client_dropdown')) {
            clientDropdown.classList.add('hidden');
        }
    });

    // Search clients on typing
    clientSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            clientDropdown.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(function() {
            fetch('/admin/clientes/buscar?q=' + encodeURIComponent(query), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.length) {
                    clientDropdown.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">No se encontraron clientes.</div>';
                } else {
                    clientDropdown.innerHTML = data.map(c => `
                        <button type="button" onclick="selectClient(${c.id}, '${c.name.replace(/'/g, "\\'")}', '${(c.email || '').replace(/'/g, "\\'")}', '${(c.phone || '').replace(/'/g, "\\'")}')" class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0">
                            <div class="font-medium text-sm text-gray-900">${c.name}</div>
                            <div class="text-xs text-gray-500 mt-0.5">${[c.email, c.phone].filter(Boolean).join(' · ')}</div>
                        </button>
                    `).join('');
                }
                clientDropdown.classList.remove('hidden');
            })
            .catch(() => {
                clientDropdown.innerHTML = '<div class="px-4 py-3 text-sm text-red-400">Error de búsqueda.</div>';
                clientDropdown.classList.remove('hidden');
            });
        }, 300);
    });

    function selectClient(id, name, email, phone) {
        clientHidden.value = id;
        clientSearch.value = '';
        clientDropdown.classList.add('hidden');
        clientNameEl.textContent = name;
        clientInfoEl.textContent = [email, phone].filter(Boolean).join(' · ');
        clientDisplay.classList.remove('hidden');

        // Load vehicles for this client
        loadClientVehicles(id);
        // Load service orders for this client
        loadClientServiceOrders(id);
    }

    function clearClientSelection() {
        clientHidden.value = '';
        clientSearch.value = '';
        clientDisplay.classList.add('hidden');
        vehicleSelect.innerHTML = '<option value="">Primero selecciona un cliente</option>';
        vehicleSelect.disabled = true;
        vehicleSelect.classList.add('bg-gray-100');
        soSelect.innerHTML = '<option value="">Seleccionar orden (opcional)</option>';
        soSelect.disabled = true;
        soSelect.classList.add('bg-gray-100');
    }

    function loadClientVehicles(clientId) {
        vehicleSelect.innerHTML = '<option value="">Cargando vehículos...</option>';
        vehicleSelect.disabled = true;

        fetch('/admin/clientes/' + clientId + '/vehiculos', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            vehicleSelect.disabled = false;
            vehicleSelect.classList.remove('bg-gray-100');
            if (!data.length) {
                vehicleSelect.innerHTML = '<option value="">Este cliente no tiene vehículos registrados</option>';
            } else {
                vehicleSelect.innerHTML = '<option value="">Seleccionar vehículo</option>' +
                    data.map(v => `<option value="${v.id}">${v.plate} — ${v.brand} ${v.model} (${v.year || 'N/A'})</option>`).join('');
            }
        })
        .catch(() => {
            vehicleSelect.innerHTML = '<option value="">Error al cargar vehículos</option>';
        });
    }

    function loadClientServiceOrders(clientId) {
        soSelect.innerHTML = '<option value="">Cargando órdenes...</option>';
        soSelect.disabled = true;

        fetch('/admin/clientes/' + clientId + '/ordenes', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            soSelect.disabled = false;
            soSelect.classList.remove('bg-gray-100');
            if (!data.length) {
                soSelect.innerHTML = '<option value="">Sin órdenes de servicio</option>';
            } else {
                soSelect.innerHTML = '<option value="">Seleccionar orden (opcional)</option>' +
                    data.map(o => `<option value="${o.id}">${o.order_number}</option>`).join('');
            }
        })
        .catch(() => {
            soSelect.innerHTML = '<option value="">Error al cargar órdenes</option>';
        });
    }

    // Prevent form submit if no client selected
    document.getElementById('quotationForm').addEventListener('submit', function(e) {
        if (!clientHidden.value) {
            e.preventDefault();
            clientSearch.focus();
            clientSearch.classList.add('border-red-400', 'ring-2', 'ring-red-200');
            setTimeout(() => clientSearch.classList.remove('border-red-400', 'ring-2', 'ring-red-200'), 3000);
        }
    });

    // ========== Items Table ==========

    function addItemRow() {
        rowCounter++;
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.id = 'item-row-' + rowCounter;
        tr.className = 'hover:bg-gray-50 transition-colors';
        tr.innerHTML = `
            <td class="px-3 py-2 text-gray-500 text-xs">${rowCounter}</td>
            <td class="px-3 py-2">
                <select name="items[${rowCounter}][product_id]"
                        onchange="onProductSelect(this, ${rowCounter})"
                        class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccionar...</option>
                    ${productsData.map(p => `<option value="${p.id}">${p.name} (Stock: ${p.stock_quantity})</option>`).join('')}
                </select>
                <input type="hidden" name="items[${rowCounter}][name]" id="item-name-${rowCounter}" value="">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="items[${rowCounter}][name]" id="item-display-name-${rowCounter}" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="—">
            </td>
            <td class="px-3 py-2">
                <input type="text" name="items[${rowCounter}][description]" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Descripción...">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="items[${rowCounter}][quantity]" value="1" min="1" step="1"
                       onchange="calculateRowTotal(${rowCounter})"
                       class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs text-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="items[${rowCounter}][unit_price]" id="item-price-${rowCounter}" value="0" min="0" step="0.01"
                       onchange="calculateRowTotal(${rowCounter})"
                       class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs text-right focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </td>
            <td class="px-3 py-2">
                <input type="number" name="items[${rowCounter}][discount]" id="item-discount-${rowCounter}" value="0" min="0" step="0.01"
                       onchange="calculateRowTotal(${rowCounter})"
                       class="w-full px-2 py-2 border border-gray-300 rounded-lg text-xs text-right focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </td>
            <td class="px-3 py-2 text-right">
                <span class="text-xs font-semibold text-gray-900" id="item-total-${rowCounter}">$0.00</span>
            </td>
            <td class="px-3 py-2 text-center">
                <button type="button" onclick="removeItemRow(${rowCounter})" class="p-1 text-red-400 hover:text-red-600 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                    </svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        document.getElementById('itemsCount').value = rowCounter;
        calculateTotals();
    }

    function onProductSelect(selectEl, rowId) {
        const productId = selectEl.value;
        const product = productsData.find(p => p.id == productId);
        const nameInput = document.getElementById('item-display-name-' + rowId);
        const priceInput = document.getElementById('item-price-' + rowId);

        if (product) {
            nameInput.value = product.name;
            priceInput.value = product.price;
        } else {
            nameInput.value = '';
            priceInput.value = '0';
        }
        calculateRowTotal(rowId);
    }

    function calculateRowTotal(rowId) {
        const qty = parseFloat(document.querySelector(`tr#item-row-${rowId} input[name="items[${rowId}][quantity]"]`)?.value || 0);
        const price = parseFloat(document.getElementById('item-price-' + rowId)?.value || 0);
        const discount = parseFloat(document.getElementById('item-discount-' + rowId)?.value || 0);
        const total = (qty * price) - discount;
        const totalEl = document.getElementById('item-total-' + rowId);
        if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        for (let i = 1; i <= rowCounter; i++) {
            const row = document.getElementById('item-row-' + i);
            if (!row) continue;
            const qty = parseFloat(row.querySelector(`input[name="items[${i}][quantity]"]`)?.value || 0);
            const price = parseFloat(row.querySelector(`input[name="items[${i}][unit_price]"]`)?.value || 0);
            const discount = parseFloat(row.querySelector(`input[name="items[${i}][discount]"]`)?.value || 0);
            subtotal += (qty * price) - discount;
        }

        const taxRate = parseFloat(document.getElementById('tax_rate')?.value || 16);
        const discountVal = parseFloat(document.getElementById('discount')?.value || 0);
        const discountType = document.getElementById('discount_type')?.value || 'percentage';

        document.getElementById('taxRateDisplay').textContent = taxRate;

        let actualDiscount = discountType === 'percentage' ? (subtotal * discountVal / 100) : discountVal;
        actualDiscount = Math.min(actualDiscount, subtotal);

        const tax = (subtotal - actualDiscount) * (taxRate / 100);
        const grandTotal = subtotal - actualDiscount + tax;

        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('taxDisplay').textContent = '$' + tax.toFixed(2);
        document.getElementById('discountDisplay').textContent = '-$' + actualDiscount.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent = '$' + grandTotal.toFixed(2);
    }

    function removeItemRow(rowId) {
        const row = document.getElementById('item-row-' + rowId);
        if (row) {
            row.remove();
            calculateTotals();
        }
    }

    // Initialize with one empty row
    document.addEventListener('DOMContentLoaded', function() {
        addItemRow();
    });
</script>
@endpush
@endsection
