@extends('layouts.auth')

@section('auth-title', 'Recuperar Contraseña')
@section('auth-subtitle', 'Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<form method="POST" action="{{ route('password.email') }}" class="space-y-5">
    @csrf

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" id="email" name="email" required autofocus
                value="{{ old('email') }}"
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                placeholder="tu@email.com">
        </div>
        @error('email')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit --}}
    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
        </svg>
        Enviar Enlace de Recuperación
    </button>
</form>
@endsection

@section('auth-footer')
<p class="text-center text-sm text-gray-500">
    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors">← Volver a Iniciar Sesión</a>
</p>
@endsection
