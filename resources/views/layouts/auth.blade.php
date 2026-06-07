<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoScan') - Autenticación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 25%, #bfdbfe 50%, #93c5fd 75%, #60a5fa 100%);
        }
        .auth-card-shadow {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08), 0 8px 20px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="auth-bg min-h-screen flex items-center justify-center px-4 py-8 sm:py-12 antialiased">

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                <svg class="w-10 h-10 text-blue-500 group-hover:text-blue-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                <span class="text-2xl font-bold text-gray-900">
                    Auto<span class="text-blue-500">Scan</span>
                </span>
            </a>
            <p class="mt-2 text-sm text-gray-600">
                Escaneo Vehicular & Reparación Computarizada
            </p>
        </div>

        <!-- Auth Card -->
        <div class="bg-white rounded-2xl auth-card-shadow border border-gray-100">
            <div class="px-8 py-8 sm:px-10 sm:py-10">

                <!-- Card header -->
                <div class="text-center mb-8">
                    <h2 class="text-xl font-bold text-gray-900">
                        @yield('auth-title', 'Bienvenido')
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        @yield('auth-subtitle', 'Ingresa tus credenciales para continuar')
                    </p>
                </div>

                <!-- Flash messages -->
                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-lg">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-lg">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
                        </svg>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                @endif

                <!-- Form content -->
                @yield('content')

            </div>

            <!-- Card footer (optional override) -->
            @hasSection('auth-footer')
                <div class="px-8 py-6 sm:px-10 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                    @yield('auth-footer')
                </div>
            @endif
        </div>

        <!-- Bottom links -->
        <div class="mt-6 text-center">
            @section('auth-bottom-links')
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} AutoScan. Todos los derechos reservados.
                </p>
            @show
        </div>

    </div>

    @stack('scripts')
</body>
</html>
