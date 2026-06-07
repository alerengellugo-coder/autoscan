@extends('layouts.auth')

@section('auth-title', 'Iniciar Sesión')
@section('auth-subtitle', 'Ingresa tus credenciales para acceder a tu panel')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<form method="POST" action="{{ route('login') }}" class="space-y-5">
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

    {{-- Password --}}
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
            </svg>
            <input type="password" id="password" name="password" required
                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-20 focus:outline-none transition-colors {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-red-500 focus:ring-opacity-20' : }}"
                placeholder="••••••••">
        </div>
        @error('password')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-opacity-20">
            <span class="text-sm text-gray-600">Recordarme</span>
        </label>
        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
            ¿Olvidaste tu contraseña?
        </a>
    </div>

    {{-- Submit --}}
    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
        Iniciar Sesión
    </button>
</form>
@endsection

@section('auth-footer')
<p class="text-center text-sm text-gray-500">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 transition-colors">Regístrate aquí</a>
</p>
@endsection
