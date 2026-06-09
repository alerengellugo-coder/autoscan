@extends('layouts.app')

@section('title', 'Cotizaciones')
@section('page-title', 'Mis Cotizaciones')

@section('content')
<div class="space-y-6">

    {{-- Quotations Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                {{ $quotations->count() }} cotización{{ $quotations->count() !== 1 ? 'es' : '' }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium"># Cotización</th>
                        <th class="px-5 py-3 font-medium">Vehículo</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Válida Hasta</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($quotations as $quotation)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <a href="{{ route('client.quotations.show', $quotation) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                {{ $quotation->quotation_number }}
                            </a>
                        </td>
                        <td class="px-5 py-3 text-gray-700">
                            @if($quotation->vehicle)
                                {{ $quotation->vehicle->brand ?? '' }} {{ $quotation->vehicle->model ?? '' }}
                                <span class="text-gray-400">({{ $quotation->vehicle->plate ?? '' }})</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $qStatusColors = [
                                    'draft' => 'bg-gray-200 text-gray-700',
                                    'pending_client' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'expired' => 'bg-gray-100 text-gray-800',
                                ];
                                $qColorClass = $qStatusColors[$quotation->status->value] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $qColorClass }}">
                                {{ $quotation->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-medium text-gray-900">
                            ${{ number_format($quotation->total, 2) }}
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            @if($quotation->valid_until)
                                {{ $quotation->valid_until?->format('d/m/Y') ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ $quotation->created_at?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <a href="{{ route('client.quotations.show', $quotation) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs font-medium">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                            </svg>
                            <p class="text-sm">No tienes cotizaciones.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($quotations->hasPages())
    <div class="flex items-center justify-center gap-1">
        {{ $quotations->links('pagination::tailwind') }}
    </div>
    @endif

</div>
@endsection
