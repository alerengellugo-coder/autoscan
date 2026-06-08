<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoScan') - Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .toast-enter { animation: slideInRight 0.3s ease-out; }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <!-- Mobile sidebar overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-full w-64 bg-slate-900 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 h-16 border-b border-slate-700/50 flex-shrink-0">
            <svg class="w-8 h-8 text-blue-400 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
            <span class="text-xl font-bold tracking-tight">Auto<span class="text-blue-400">Scan</span></span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3">
            <ul class="space-y-1">
                @auth
                    @php
                        $user = Auth::user();
                        $vehicleRoute = $user->role === 'admin' ? 'admin.vehiculos.index' : 'client.vehicles.index';
                        $ordersRoute = match($user->role) { 'admin' => 'admin.ordenes.index', 'technician' => 'technician.orders.index', 'client' => 'client.orders.index', default => 'admin.ordenes.index' };
                        $productsRoute = $user->role === 'admin' ? 'admin.productos.index' : 'technician.products.catalog';
                        $quotationsRoute = $user->role === 'admin' ? 'admin.cotizaciones.index' : 'client.quotations.index';
                        $salesRoute = $user->role === 'admin' ? 'admin.ventas.index' : 'client.sales.index';
                        $reportsRoute = $user->role === 'admin' ? 'admin.reports.index' : 'technician.reports.index';
                    @endphp

                    {{-- All roles: Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    {{-- Vehicles: admin, client --}}
                    @if(in_array($user->role, ['admin', 'client']))
                    <li>
                        <a href="{{ route($vehicleRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.vehiculos.*') or request()->routeIs('client.vehicles.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 17h14"/><path d="M5 17a2 2 0 01-2-2V9a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v6a2 2 0 01-2 2"/><circle cx="9" cy="13" r="2"/><circle cx="15" cy="13" r="2"/>
                            </svg>
                            {{ $user->role === 'client' ? 'Mis Vehículos' : 'Vehículos' }}
                        </a>
                    </li>
                    @endif

                    {{-- Orders: admin, technician, client --}}
                    @if(in_array($user->role, ['admin', 'technician', 'client']))
                    <li>
                        <a href="{{ route($ordersRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.ordenes.*') or request()->routeIs('technician.orders.*') or request()->routeIs('client.orders.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/>
                            </svg>
                            {{ $user->role === 'client' ? 'Mis Órdenes' : 'Órdenes' }}
                        </a>
                    </li>
                    @endif

                    {{-- Products: admin, technician --}}
                    @if(in_array($user->role, ['admin', 'technician']))
                    <li>
                        <a href="{{ route($productsRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.productos.*') or request()->routeIs('technician.products.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27,6.96 12,12.01 20.73,6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>
                            </svg>
                            {{ $user->role === 'technician' ? 'Catálogo' : 'Productos' }}
                        </a>
                    </li>
                    @endif

                    {{-- Quotations: admin, client --}}
                    @if(in_array($user->role, ['admin', 'client']))
                    <li>
                        <a href="{{ route($quotationsRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.cotizaciones.*') or request()->routeIs('client.quotations.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                            </svg>
                            Cotizaciones
                        </a>
                    </li>
                    @endif

                    {{-- Sales: admin, client --}}
                    @if(in_array($user->role, ['admin', 'client']))
                    <li>
                        <a href="{{ route($salesRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.ventas.*') or request()->routeIs('client.sales.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/>
                            </svg>
                            {{ $user->role === 'client' ? 'Mis Compras' : 'Ventas' }}
                        </a>
                    </li>
                    @endif

                    {{-- Reports: admin, technician --}}
                    @if(in_array($user->role, ['admin', 'technician']))
                    <li>
                        <a href="{{ route($reportsRoute) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.reports.*') or request()->routeIs('technician.reports.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                            </svg>
                            Reportes
                        </a>
                    </li>
                    @endif

                    {{-- Users: admin only --}}
                    @if($user->role === 'admin')
                    <li>
                        <a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.usuarios.*') ? 'bg-blue-600/20 text-blue-400 border-l-3 border-blue-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            Usuarios
                        </a>
                    </li>
                    @endif
                @endauth
            </ul>
        </nav>

        <!-- Sidebar footer - user info (collapsed view) -->
        @auth
        <div class="border-t border-slate-700/50 px-4 py-3 flex-shrink-0 hidden lg:block">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
        @endauth
    </aside>

    <!-- Main wrapper -->
    <div class="lg:pl-64 min-h-screen flex flex-col">
        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6">
            <!-- Left: hamburger + breadcrumb area -->
            <div class="flex items-center gap-4">
                <!-- Mobile menu button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <!-- Page title / breadcrumb -->
                <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            </div>

            <!-- Right: actions -->
            <div class="flex items-center gap-2 sm:gap-4">
                @auth
                    <!-- Notifications -->
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                        </svg>
                        @if(Auth::user()->unreadNotifications()->count() > 0)
                            <span class="absolute -top-0.5 -right-0.5 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                {{ Auth::user()->unreadNotifications()->count() > 9 ? '9+' : Auth::user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </a>

                    <!-- User dropdown -->
                    <div class="relative" id="userDropdown">
                        <button onclick="toggleUserMenu()" class="flex items-center gap-2 sm:gap-3 p-1.5 sm:p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                            <svg class="hidden sm:block w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6,9 12,15 18,9"/>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700 capitalize">{{ Auth::user()->role }}</span>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    Mi Perfil
                                </a>
                                <a href="{{ route('notifications.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
                                    </svg>
                                    Notificaciones
                                    @if(Auth::user()->unreadNotifications()->count() > 0)
                                        <span class="ml-auto px-1.5 py-0.5 text-xs font-bold text-white bg-red-500 rounded-full">
                                            {{ Auth::user()->unreadNotifications()->count() }}
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="border-t border-gray-100 py-1">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/>
                                        </svg>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </header>

        <!-- Flash Messages / Toasts -->
        <div class="flex-shrink-0">
            @if(session('success'))
                <div class="mx-4 sm:mx-6 mt-4 toast-enter">
                    <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        <button onclick="this.closest('.toast-enter').remove()" class="ml-auto p-1 text-green-400 hover:text-green-600 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 sm:mx-6 mt-4 toast-enter">
                    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        <button onclick="this.closest('.toast-enter').remove()" class="ml-auto p-1 text-red-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mx-4 sm:mx-6 mt-4 toast-enter">
                    <div class="flex items-center gap-3 px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                        <button onclick="this.closest('.toast-enter').remove()" class="ml-auto p-1 text-yellow-400 hover:text-yellow-600 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="mx-4 sm:mx-6 mt-4 toast-enter">
                    <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                        <button onclick="this.closest('.toast-enter').remove()" class="ml-auto p-1 text-blue-400 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Main content -->
        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function (e) {
            const dropdown = document.getElementById('userDropdown');
            const menu = document.getElementById('userMenu');
            if (dropdown && menu && !dropdown.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Auto-dismiss flash messages after 5 seconds
        setTimeout(function () {
            document.querySelectorAll('.toast-enter').forEach(function (toast) {
                toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(function () {
                    toast.remove();
                }, 300);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
