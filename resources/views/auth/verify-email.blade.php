@extends('layouts.auth')

@section('auth-title', 'Verificar Email')
@section('auth-subtitle', 'Confirma tu dirección de correo electrónico')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<div class="text-center">
    @if(session('status'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-700">{{ session('status') }}</p>
        </div>
    @endif

    <div class="mb-6">
        <svg class="w-16 h-16 mx-auto text-blue-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-600 mb-6">
            Antes de continuar, necesitamos verificar tu dirección de email.
            Te hemos enviado un enlace de verificación.
        </p>
    </div>

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
        @csrf
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition-colors duration-200">
            Reenviar Email de Verificación
        </button>
    </form>

    <div class="mt-4">
        <a href="{{ route('logout') }}" method="POST" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
            Cerrar Sesión
        </a>
        @csrf
    </div>
</div>
@endsection
