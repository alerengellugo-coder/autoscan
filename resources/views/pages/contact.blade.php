@extends('layouts.public')

@section('title', 'Contacto - AutoScan')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Contáctanos</h1>
        <p class="mt-4 text-lg text-blue-200/80 max-w-2xl mx-auto">Estamos aquí para ayudarte. Envíanos un mensaje y te responderemos lo antes posible.</p>
    </div>
</section>

{{-- Contact Section --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            {{-- Contact Form --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Envíanos un Mensaje</h2>
                <p class="text-sm text-gray-500 mb-8">Completa el formulario y nos pondremos en contacto contigo.</p>

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required
                            value="{{ old('name') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('name') ? 'border-red-400' : }}"
                            placeholder="Tu nombre completo">
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" required
                            value="{{ old('email') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('email') ? 'border-red-400' : }}"
                            placeholder="tu@email.com">
                        @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" id="phone" name="phone"
                            value="{{ old('phone') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('phone') ? 'border-red-400' : }}"
                            placeholder="+52 55 1234 5678">
                        @error('phone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje <span class="text-red-500">*</span></label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors {{ $errors->has('message') ? 'border-red-400' : }}"
                            placeholder="Cuéntanos en qué podemos ayudarte...">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22,2 15,22 11,13 2,9"/>
                        </svg>
                        Enviar Mensaje
                    </button>
                </form>
            </div>

            {{-- Contact Info + Map --}}
            <div class="space-y-8">

                {{-- Map Placeholder --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl h-64 lg:h-80 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-blue-300 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <p class="text-sm text-blue-400 font-medium">Mapa</p>
                        <p class="text-xs text-blue-300 mt-1">Av. Principal #123, Col. Centro</p>
                    </div>
                </div>

                {{-- Contact Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Address --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Dirección</h4>
                                <p class="text-xs text-gray-500 mt-1">Av. Principal #123<br>Col. Centro, CDMX</p>
                            </div>
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Teléfono</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    <a href="tel:+525512345678" class="hover:text-blue-600 transition-colors">+52 55 1234 5678</a><br>
                                    <a href="tel:+525587654321" class="hover:text-blue-600 transition-colors">+52 55 8765 4321</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Email</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    <a href="mailto:contacto@autoscan.com" class="hover:text-blue-600 transition-colors">contacto@autoscan.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Hours --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Horario</h4>
                                <p class="text-xs text-gray-500 mt-1">Lun - Vie: 8:00 - 18:00<br>Sáb: 9:00 - 14:00</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

@endsection
