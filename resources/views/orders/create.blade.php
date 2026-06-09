@extends('layouts.app')

@section('title', 'Crear Orden')
@section('page-title', 'Crear Orden de Servicio')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.ordenes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Órdenes
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Nueva Orden de Servicio</h2>
            <p class="text-sm text-gray-500 mt-1">Completa los campos para registrar una nueva orden de servicio.</p>
        </div>

        <form method="POST" action="{{ route('admin.ordenes.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Client Search (admin only) --}}
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

            {{-- Technician + Service Type --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="technician_id" class="block text-sm font-medium text-gray-700 mb-1">Técnico <span class="text-red-500">*</span></label>
                    <select id="technician_id"
                            name="technician_id"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar técnico</option>
                        @foreach($technicians as $technician)
                            <option value="{{ $technician->id }}" {{ old('technician_id') == $technician->id ? 'selected' : '' }}>
                                {{ $technician->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('technician_id')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Servicio <span class="text-red-500">*</span></label>
                    <select id="service_type"
                            name="service_type"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar tipo</option>
                        @foreach($service_types as $type)
                            <option value="{{ $type['value'] }}" {{ old('service_type') == $type['value'] ? 'selected' : '' }}>
                                {{ $type['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_type')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Priority --}}
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridad</label>
                <select id="priority"
                        name="priority"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @foreach($priorities as $priority)
                        <option value="{{ $priority['value'] }}" {{ old('priority', 'normal') == $priority['value'] ? 'selected' : '' }}>
                            {{ $priority['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          required
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Describe el problema o servicio solicitado por el cliente...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Diagnosis --}}
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">Diagnóstico</label>
                <textarea id="diagnosis"
                          name="diagnosis"
                          rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Diagnóstico inicial del técnico (opcional)...">{{ old('diagnosis') }}</textarea>
                @error('diagnosis')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Estimated Cost + Estimated Completion Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-1">Costo Estimado</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                        <input type="number"
                               id="estimated_cost"
                               name="estimated_cost"
                               value="{{ old('estimated_cost') }}"
                               min="0"
                               step="0.01"
                               class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="0.00">
                    </div>
                    @error('estimated_cost')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="estimated_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Estimada de Entrega</label>
                    <input type="date"
                           id="estimated_completion_date"
                           name="estimated_completion_date"
                           value="{{ old('estimated_completion_date') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('estimated_completion_date')
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
                          placeholder="Notas adicionales...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.ordenes.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Crear Orden
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    let searchTimeout = null;

    const clientSearch = document.getElementById('client_search');
    const clientDropdown = document.getElementById('client_dropdown');
    const clientHidden = document.getElementById('client_id');
    const clientDisplay = document.getElementById('client_selected_display');
    const clientNameEl = document.getElementById('client_selected_name');
    const clientInfoEl = document.getElementById('client_selected_info');
    const vehicleSelect = document.getElementById('vehicle_id');

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
    }

    function clearClientSelection() {
        clientHidden.value = '';
        clientSearch.value = '';
        clientDisplay.classList.add('hidden');
        vehicleSelect.innerHTML = '<option value="">Primero selecciona un cliente</option>';
        vehicleSelect.disabled = true;
        vehicleSelect.classList.add('bg-gray-100');
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

    // Prevent form submit if no client selected
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!clientHidden.value) {
            e.preventDefault();
            clientSearch.focus();
            clientSearch.classList.add('border-red-400', 'ring-2', 'ring-red-200');
            setTimeout(() => clientSearch.classList.remove('border-red-400', 'ring-2', 'ring-red-200'), 3000);
        }
    });
</script>
@endpush
@endsection
