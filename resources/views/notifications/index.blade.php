@extends('layouts.app')

@section('title', 'Notificaciones')
@section('page-title', 'Notificaciones')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header Actions --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            @if(isset($notifications) && $notifications->total() > 0)
                {{ $notifications->total() }} notificaci{{ $notifications->total() == 1 ? 'ón' : 'ones' }}
            @else
                Sin notificaciones
            @endif
        </p>
        @if(isset($notifications) && $notifications->filter(fn($n) => !$n->read_at)->count() > 0)
        <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline-flex">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9,11 12,14 22,4"/>
                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                </svg>
                Marcar todas como leídas
            </button>
        </form>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="space-y-3">
        @forelse($notifications as $notification)
        <div class="bg-white rounded-xl shadow-sm border {{ !$notification->read_at ? 'border-l-4 border-l-blue-500' : 'border-gray-200' }} overflow-hidden hover:shadow-md transition-shadow">

            <div class="px-5 py-4 flex items-start gap-4">

                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @switch($notification->type)
                        @case('order_status')
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/>
                                </svg>
                            </div>
                            @break
                        @case('quotation_status')
                        @case('quotation_approved')
                        @case('quotation_rejected')
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                                </svg>
                            </div>
                            @break
                        @case('sale_completed')
                        @case('payment_received')
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>
                                </svg>
                            </div>
                            @break
                        @case('report_created')
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                            </div>
                            @break
                        @case('low_stock')
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                            </div>
                            @break
                        @case('user_assigned')
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                </svg>
                            </div>
                            @break
                        @default
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                                </svg>
                            </div>
                            @break
                    @endswitch
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm {{ !$notification->read_at ? 'font-semibold text-gray-900' : 'font-medium text-gray-700' }}">
                        {{ $notification->data['title'] ?? $notification->data['message'] ?? ucfirst(str_replace('_', ' ', $notification->type)) }}
                    </p>
                    @if($notification->data['message'] ?? ($notification->data['body'] ?? null))
                    <p class="text-sm text-gray-500 mt-1">{{ $notification->data['message'] ?? $notification->data['body'] }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1.5">
                        {{ $notification->created_at->format('d/m/Y H:i') }}
                        @if(!$notification->read_at)
                            <span class="inline-flex items-center ml-2 px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-700">Nueva</span>
                        @endif
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex-shrink-0 flex items-center gap-1">
                    @if(!$notification->read_at)
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}" class="inline-flex">
                        @csrf
                        <button type="submit" class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors" title="Marcar como leída">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20,6 9,17 4,12"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="inline-flex" onsubmit="return confirm('¿Eliminar esta notificación?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-5 py-16 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
            </svg>
            <p class="text-lg font-medium text-gray-500">Sin notificaciones</p>
            <p class="text-sm text-gray-400 mt-1">Las notificaciones nuevas aparecerán aquí.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(isset($notifications) && $notifications->hasPages())
    <div class="flex justify-center">
        {{ $notifications->appends(request()->query())->links() }}
    </div>
    @endif

</div>
@endsection
