@extends('layouts.app')

@section('title', 'Mis Compras')
@section('page-title', 'Mis Compras')

@section('content')
<div class="space-y-6">

    {{-- Sales Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                {{ $sales->count() }} compra{{ $sales->count() !== 1 ? 's' : '' }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium"># Venta</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Estado de Pago</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <span class="font-medium text-gray-900">{{ $sale['sale_number'] }}</span>
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-900">
                            ${{ number_format($sale['total'], 2) }}
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $saleStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-orange-100 text-orange-800',
                                ];
                                $sColorClass = $saleStatusColors[$sale['status']] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sColorClass }}">
                                {{ $sale['status_label'] ?? ucfirst($sale['status']) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $payStatusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'partial' => 'bg-orange-100 text-orange-800',
                                    'overdue' => 'bg-red-100 text-red-800',
                                ];
                                $pColorClass = $payStatusColors[$sale['payment_status']] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pColorClass }}">
                                {{ $sale['payment_status_label'] ?? ucfirst(str_replace('_', ' ', $sale['payment_status'])) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ \Carbon\Carbon::parse($sale['created_at'])->format('d/m/Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                            </svg>
                            <p class="text-sm">No tienes compras registradas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($sales->hasPages())
    <div class="flex items-center justify-center gap-1">
        {{ $sales->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
