<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoScan') - Escaneo Vehicular y Reparación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .nav-scrolled {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .nav-scrolled .nav-link-text {
            color: #374151 !important;
        }
        .nav-scrolled .nav-logo-text {
            color: #111827 !important;
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">

    <!-- Top Navigation -->
    <nav id="publicNavbar" class="fixed top-0 left-0 right-0 z-50 bg-transparent transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <svg class="w-8 h-8 text-blue-500 group-hover:text-blue-400 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                    <span class="nav-logo-text text-xl font-bold text-white transition-colors">
                        Auto<span class="text-blue-400">Scan</span>
                    </span>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="nav-link-text text-sm font-medium text-white/90 hover:text-white transition-colors">
                        Inicio
                    </a>
                    <a href="{{ route('services') }}" class="nav-link-text text-sm font-medium text-white/90 hover:text-white transition-colors">
                        Servicios
                    </a>
                    <a href="{{ route('about') }}" class="nav-link-text text-sm font-medium text-white/90 hover:text-white transition-colors">
                        Nosotros
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link-text text-sm font-medium text-white/90 hover:text-white transition-colors">
                        Contacto
                    </a>
                </div>

                <!-- Auth buttons -->
                <div class="hidden md:flex items-center gap-3">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-white/90 hover:text-white transition-colors">
                            Panel
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white/90 hover:text-white transition-colors">
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link-text px-4 py-2 text-sm font-medium text-white/90 hover:text-white transition-colors">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-blue-500/25">
                            Registrarse
                        </a>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <button id="mobileMenuBtn" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg text-white hover:bg-white/10 transition-colors">
                    <svg id="menuIconOpen" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                    <svg id="menuIconClose" class="w-6 h-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-xl">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">
                    Inicio
                </a>
                <a href="{{ route('services') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">
                    Servicios
                </a>
                <a href="{{ route('about') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">
                    Nosotros
                </a>
                <a href="{{ route('contact') }}" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">
                    Contacto
                </a>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 space-y-2">
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="block w-full text-center px-4 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                        Ir al Panel
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-center px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                            Cerrar Sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors">
                        Registrarse
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <!-- Main footer content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- Brand column -->
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                        <svg class="w-8 h-8 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                        </svg>
                        <span class="text-xl font-bold text-white">Auto<span class="text-blue-400">Scan</span></span>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed mb-6">
                        Tu centro especializado en escaneo vehicular computarizado y reparación de equipos electrónicos. Tecnología de punta para tu vehículo.
                    </p>
                    <!-- Social links -->
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-blue-600 text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-blue-600 text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-800 hover:bg-blue-600 text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Services column -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Servicios</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('services') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Escaneo Vehicular
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Diagnóstico Computarizado
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Reparación de Módulos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Reprogramación ECU
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Elaboración de Llaves
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Company column -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Empresa</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('about') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Nosotros
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Contacto
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Términos y Condiciones
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                Política de Privacidad
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact column -->
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Contacto</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span class="text-sm text-gray-400">Av. Principal #123, Col. Centro, Ciudad de México</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                            </svg>
                            <a href="tel:+525512345678" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                +52 55 1234 5678
                            </a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <a href="mailto:contacto@autoscan.com" class="text-sm text-gray-400 hover:text-blue-400 transition-colors">
                                contacto@autoscan.com
                            </a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                            </svg>
                            <span class="text-sm text-gray-400">Lun - Vie: 8:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} AutoScan. Todos los derechos reservados.
                    </p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-400 transition-colors">
                            Términos
                        </a>
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-400 transition-colors">
                            Privacidad
                        </a>
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-400 transition-colors">
                            Cookies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('publicNavbar');
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                navbar.classList.add('nav-scrolled');
            } else {
                navbar.classList.remove('nav-scrolled');
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const iconOpen = document.getElementById('menuIconOpen');
            const iconClose = document.getElementById('menuIconClose');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
