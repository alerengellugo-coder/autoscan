@extends('layouts.app')

@section('title', 'Cotización #' . $quotation->quotation_number)
@section('page-title', 'Detalle de Cotización')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.cotizaciones.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Cotizaciones
        </a>
    </div>

    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Cotización #{{ $quotation->quotation_number }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Creada el {{ $quotation->created_at?->format('d/m/Y') ?? '—' }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @switch($quotation->status?->value)
                    @case('draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-700">{{ $quotation->status_label ?? 'Borrador' }}</span>
                        @break
                    @case('pending_client')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">{{ $quotation->status_label ?? 'Pendiente' }}</span>
                        @break
                    @case('approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">{{ $quotation->status_label ?? 'Aprobada' }}</span>
                        @break
                    @case('rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">{{ $quotation->status_label ?? 'Rechazada' }}</span>
                        @break
                    @case('expired')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ $quotation->status_label ?? 'Expirada' }}</span>
                        @break
                    @default
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ $quotation->status_label ?? $quotation->status }}</span>
                        @break
                @endswitch

                @if($quotation->valid_until)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-50 text-gray-600 border border-gray-200">
                        Válida hasta: {{ $quotation->valid_until?->format('d/m/Y') ?? '—' }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Client & Vehicle Info --}}
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Client --}}
                <div>
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Cliente</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                            {{ strtoupper(substr($quotation->client->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-900">{{ $quotation->client->name ?? '—' }}</span>
                            @if($quotation->client->email ?? false)
                                <p class="text-xs text-gray-500">{{ $quotation->client->email }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Vehicle --}}
                <div>
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Vehículo</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-600 flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/><circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-gray-900">{{ $quotation->vehicle->brand ?? '' }} {{ $quotation->vehicle->model ?? '' }}</span>
                            <p class="text-xs text-gray-500 font-mono">{{ $quotation->vehicle->plate ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($quotation->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Notas</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $quotation->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">Producto</th>
                        <th class="px-5 py-3 font-medium">Descripción</th>
                        <th class="px-5 py-3 font-medium text-center">Cantidad</th>
                        <th class="px-5 py-3 font-medium text-right">Precio Unitario</th>
                        <th class="px-5 py-3 font-medium text-right">Descuento</th>
                        <th class="px-5 py-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($quotation->items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $item->name ?? ($item->product->name ?? '—') }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $item->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-center text-gray-700">{{ $item->quantity }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-5 py-3 text-right text-red-600">{{ $item->discount > 0 ? '-$' . number_format($item->discount, 2) : '—' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">No hay items en esta cotización.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="px-6 py-5 border-t border-gray-200">
            <div class="flex justify-end">
                <div class="w-full max-w-xs space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium text-gray-900">${{ number_format($quotation->subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Impuestos ({{ $quotation->tax_rate ?? 0 }}%):</span>
                        <span class="font-medium text-gray-900">${{ number_format($quotation->tax ?? 0, 2) }}</span>
                    </div>
                    @if($quotation->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Descuento:</span>
                        <span class="font-medium text-red-600">-${{ number_format($quotation->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-200 pt-2">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="font-bold text-blue-600 text-lg">${{ number_format($quotation->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('admin.cotizaciones.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver
        </a>

        <div class="flex items-center gap-3 flex-wrap">
            {{-- PDF --}}
            @if($quotation->id)
            <a href="{{ route('admin.cotizaciones.pdf', $quotation) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
                </svg>
                Descargar PDF
            </a>
            @endif

            {{-- Change Status --}}
            <button type="button"
                    onclick="document.getElementById('statusModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9,11 12,14 22,4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
                Cambiar Estado
            </button>

            {{-- Convert to Sale --}}
            @if($quotation->id && $quotation->status == 'approved')
            <form method="POST" action="{{ route('admin.cotizaciones.convert-to-sale', $quotation) }}" onsubmit="return confirm('¿Convertir esta cotización en venta?')">
                @csrf
                @method('POST')
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9,11 12,14 22,4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                    Convertir a Venta
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Change Status Modal --}}
    <div id="statusModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('statusModal').classList.add('hidden')"></div>
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
                <p class="text-sm text-gray-500 mt-1">Cotización <span class="font-mono font-semibold">#{{ $quotation->quotation_number }}</span></p>
            </div>
            <form method="POST" action="{{ route('admin.cotizaciones.update-status', $quotation) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="new_status" class="block text-sm font-medium text-gray-700 mb-1">Nuevo Estado</label>
                    <select id="new_status"
                            name="status"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar estado</option>
                        @foreach($status_options as $value => $label)
                            <option value="{{ $value }}" {{ $quotation->status == $value ? 'disabled' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
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
