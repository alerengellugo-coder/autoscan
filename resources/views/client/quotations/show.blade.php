@extends('layouts.app')

@section('title', 'Cotización #'.$quotation['quotation_number'])
@section('page-title', 'Detalle de Cotización')

@section('content')
<div class="space-y-6">

    {{-- Back button --}}
    <div>
        <a href="{{ route('client.quotations.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Cotizaciones
        </a>
    </div>

    {{-- Quotation Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Cotización {{ $quotation['quotation_number'] }}</h2>
                <p class="mt-1 text-sm text-gray-500">Fecha: {{ \Carbon\Carbon::parse($quotation['created_at'])->format('d/m/Y') }}</p>
                @if($quotation['valid_until'])
                <p class="text-sm text-gray-500">Válida hasta: {{ \Carbon\Carbon::parse($quotation['valid_until'])->format('d/m/Y') }}</p>
                @endif
            </div>
            @php
                $qStatusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'approved' => 'bg-green-100 text-green-800',
                    'rejected' => 'bg-red-100 text-red-800',
                    'cancelled' => 'bg-gray-100 text-gray-800',
                    'sent' => 'bg-blue-100 text-blue-800',
                    'converted' => 'bg-green-100 text-green-800',
                    'expired' => 'bg-gray-100 text-gray-800',
                ];
                $qColorClass = $qStatusColors[$quotation['status']] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $qColorClass }}">
                {{ $quotation['status_label'] ?? ucfirst($quotation['status']) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Client Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Cliente
            </h3>
            @if($quotation['client'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nombre</dt>
                    <dd class="font-medium text-gray-900">{{ $quotation['client']['name'] }}</dd>
                </div>
                @if($quotation['client']['email'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $quotation['client']['email'] }}</dd>
                </div>
                @endif
                @if($quotation['client']['phone'])
                <div class="flex justify-between">
                    <dt class="text-gray-500">Teléfono</dt>
                    <dd class="font-medium text-gray-900">{{ $quotation['client']['phone'] }}</dd>
                </div>
                @endif
            </dl>
            @else
            <p class="text-sm text-gray-400">Sin información.</p>
            @endif
        </div>

        {{-- Vehicle Info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/>
                    <circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                </svg>
                Vehículo
            </h3>
            @if($quotation['vehicle'])
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Marca / Modelo</dt>
                    <dd class="font-medium text-gray-900">{{ $quotation['vehicle']['brand'] }} {{ $quotation['vehicle']['model'] ?? '' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Año</dt>
                    <dd class="font-medium text-gray-900">{{ $quotation['vehicle']['year'] ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Placa</dt>
                    <dd class="font-medium text-gray-900">{{ $quotation['vehicle']['plate'] ?? '—' }}</dd>
                </div>
            </dl>
            @else
            <p class="text-sm text-gray-400">Sin vehículo asociado.</p>
            @endif
        </div>

        {{-- Total --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                </svg>
                Resumen
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium text-gray-900">${{ number_format($quotation['subtotal'] ?? $quotation['total'] * 0.84, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">IVA (16%)</span>
                    <span class="font-medium text-gray-900">${{ number_format(($quotation['total'] ?? 0) * 0.16, 2) }}</span>
                </div>
                <div class="pt-3 border-t border-gray-200 flex justify-between">
                    <span class="text-sm font-semibold text-gray-700">Total</span>
                    <span class="text-lg font-bold text-blue-600">${{ number_format($quotation['total'], 2) }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Detalle de Partidas</h3>
        </div>
        @if(isset($quotation['items']) && count($quotation['items']) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">Descripción</th>
                        <th class="px-5 py-3 font-medium text-center">Cantidad</th>
                        <th class="px-5 py-3 font-medium text-right">Precio Unitario</th>
                        <th class="px-5 py-3 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($quotation['items'] as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-gray-900">{{ $item['description'] ?? $item['name'] ?? '—' }}</td>
                        <td class="px-5 py-3 text-center text-gray-700">{{ $item['quantity'] ?? 1 }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">${{ number_format($item['unit_price'] ?? $item['price'] ?? 0, 2) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-gray-900">${{ number_format(($item['quantity'] ?? 1) * ($item['unit_price'] ?? $item['price'] ?? 0), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-8 text-center text-gray-400">
            <p class="text-sm">Sin partidas registradas.</p>
        </div>
        @endif
    </div>

    {{-- Action Buttons --}}
    @if(in_array($quotation['status'], ['pending', 'sent']))
    <div class="flex flex-wrap items-center gap-3">
        <form method="POST" action="{{ route('client.quotations.approve', $quotation['id']) }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors"
                onclick="return confirm('¿Estás seguro de aprobar esta cotización?')">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20,6 9,17 4,12"/>
                </svg>
                Aprobar Cotización
            </button>
        </form>
        <form method="POST" action="{{ route('client.quotations.reject', $quotation['id']) }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors"
                onclick="return confirm('¿Estás seguro de rechazar esta cotización?')">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Rechazar Cotización
            </button>
        </form>
    </div>
    @endif

</div>
@endsection
