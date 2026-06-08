@extends('layouts.app')

@section('title', 'Venta #' . $sale->sale_number)
@section('page-title', 'Detalle de Venta')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back Button --}}
    <div>
        <a href="{{ route('admin.ventas.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Ventas
        </a>
    </div>

    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Venta #{{ $sale->sale_number }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">Creada el {{ $sale->created_at->format('d/m/Y') }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Status Badge --}}
                @switch($sale->status?->value)
                    @case('pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">{{ $sale->status_label ?? 'Pendiente' }}</span>
                        @break
                    @case('paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">{{ $sale->status_label ?? 'Pagada' }}</span>
                        @break
                    @case('partially_paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $sale->status_label ?? 'Parcial' }}</span>
                        @break
                    @case('cancelled')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">{{ $sale->status_label ?? 'Cancelada' }}</span>
                        @break
                    @default
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ $sale->status_label ?? $sale->status }}</span>
                        @break
                @endswitch

                {{-- Payment Status Badge --}}
                @switch($sale->payment_status)
                    @case('paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Pagado</span>
                        @break
                    @case('partial')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Pago Parcial</span>
                        @break
                    @case('pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Pago Pendiente</span>
                        @break
                    @default
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-600">{{ $sale->payment_status }}</span>
                        @break
                @endswitch
            </div>
        </div>

        {{-- Client Info --}}
        <div class="p-6">
            <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Cliente</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr($sale->client->name ?? '?', 0, 1)) }}
                </div>
                <div>
                    <span class="text-sm font-semibold text-gray-900">{{ $sale->client->name ?? '—' }}</span>
                    @if($sale->client->email ?? false)
                        <p class="text-xs text-gray-500">{{ $sale->client->email }}</p>
                    @endif
                    @if($sale->client->phone ?? false)
                        <p class="text-xs text-gray-500">{{ $sale->client->phone }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Items de la Venta</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">Producto</th>
                        <th class="px-5 py-3 font-medium">Descripción</th>
                        <th class="px-5 py-3 font-medium text-center">Cantidad</th>
                        <th class="px-5 py-3 font-medium text-right">Precio Unitario</th>
                        <th class="px-5 py-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sale->items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-900">{{ $item->name ?? ($item->product->name ?? '—') }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $item->description ?? '—' }}</td>
                        <td class="px-5 py-3 text-center text-gray-700">{{ $item->quantity }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-gray-900">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">No hay items en esta venta.</td>
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
                        <span class="font-medium text-gray-900">${{ number_format($sale->subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Impuestos:</span>
                        <span class="font-medium text-gray-900">${{ number_format($sale->tax ?? 0, 2) }}</span>
                    </div>
                    @if($sale->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Descuento:</span>
                        <span class="font-medium text-red-600">-${{ number_format($sale->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-200 pt-2">
                        <div class="flex justify-between text-base">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="font-bold text-blue-600 text-lg">${{ number_format($sale->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payments History --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Historial de Pagos</h3>
            <button type="button"
                    onclick="document.getElementById('paymentModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Registrar Pago
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium">Monto</th>
                        <th class="px-5 py-3 font-medium">Método</th>
                        <th class="px-5 py-3 font-medium">Referencia</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sale->paymentRecords as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $payment->created_at }}</td>
                        <td class="px-5 py-3 font-semibold text-green-600">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-5 py-3 text-gray-700">
                            @switch($payment->method)
                                @case('cash')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Efectivo</span>
                                    @break
                                @case('card')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Tarjeta</span>
                                    @break
                                @case('transfer')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Transferencia</span>
                                    @break
                                @default
                                    <span class="text-xs text-gray-500">{{ $payment->method }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs font-mono">{{ $payment->reference ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">No se han registrado pagos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <a href="{{ route('admin.ventas.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver
        </a>
        <div class="flex items-center gap-3 flex-wrap">
            @if($sale->status !== 'cancelled')
            <form method="POST" action="{{ route('admin.ventas.cancelar', $sale) }}" onsubmit="return confirm('¿Estás seguro de cancelar esta venta? Esta acción no se puede deshacer.')">
                @csrf
                @method('POST')
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    Cancelar Venta
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Register Payment Modal --}}
    <div id="paymentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('paymentModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-gray-200 w-full max-w-md mx-4 z-10">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Registrar Pago</h3>
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-1">Venta <span class="font-mono font-semibold">#{{ $sale->sale_number }}</span> — Total: <span class="font-bold text-blue-600">${{ number_format($sale->total, 2) }}</span></p>
            </div>
            <form method="POST" action="{{ route('admin.ventas.register-payment', $sale) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="payment_amount" class="block text-sm font-medium text-gray-700 mb-1">Monto <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                        <input type="number"
                               id="payment_amount"
                               name="amount"
                               required
                               min="0.01"
                               step="0.01"
                               class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="0.00">
                    </div>
                    @error('amount')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Método de Pago <span class="text-red-500">*</span></label>
                    <select id="payment_method"
                            name="method"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar método</option>
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                        <option value="transfer">Transferencia</option>
                    </select>
                    @error('method')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-1">Referencia</label>
                    <input type="text"
                           id="payment_reference"
                           name="reference"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="No. de referencia (opcional)">
                    @error('reference')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        Registrar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
