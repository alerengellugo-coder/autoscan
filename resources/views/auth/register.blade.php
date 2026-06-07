@extends('layouts.auth')

@section('auth-title', 'Crear Cuenta')
@section('auth-subtitle', 'Regístrate para acceder a nuestros servicios')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="name" name="name" required autofocus
                value="{{ old('name') }}"
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('name') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                placeholder="Tu nombre completo">
        </div>
        @error('name')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
            </svg>
            <input type="email" id="email" name="email" required
                value="{{ old('email') }}"
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                placeholder="tu@email.com">
        </div>
        @error('email')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Phone --}}
    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
            </svg>
            <input type="tel" id="phone" name="phone"
                value="{{ old('phone') }}"
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('phone') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                placeholder="+52 55 1234 5678">
        </div>
        @error('phone')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            <input type="password" id="password" name="password" required
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : }}"
                placeholder="Mínimo 8 caracteres">
        </div>
        @error('password')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Password Confirmation --}}
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors"
                placeholder="Repite tu contraseña">
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
        Crear Cuenta
    </button>
</form>
@endsection

@section('auth-footer')
<p class="text-center text-sm text-gray-500">
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors">Inicia sesión</a>
</p>
@endsection
