@extends('layouts.public')

@section('title', 'AutoScan - Escaneo Vehicular y Reparación Computarizada')

@section('content')

{{-- Hero Section --}}
<section class="relative min-h-[600px] lg:min-h-[700px] flex items-center overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #1e40af 100%);">
    {{-- Decorative elements --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600/5 rounded-full"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-0">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/20 backdrop-blur-sm rounded-full text-blue-200 text-sm font-medium mb-6">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                Tecnología de punta para tu vehículo
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                Diagnóstico electrónico profesional para tu vehículo
            </h1>
            <p class="mt-6 text-lg text-blue-100/80 leading-relaxed max-w-2xl">
                Escaneo vehicular computarizado, reparación de módulos electrónicos, reprogramación de ECU y mucho más. Servicio especializado con garantía.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-blue-500/25">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    Registrarse
                </a>
                <a href="{{ route('services') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-lg transition-colors border border-white/20">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/>
                    </svg>
                    Ver Servicios
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Services Section --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Nuestros Servicios</h2>
            <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Ofrecemos soluciones integrales para el diagnóstico y reparación de sistemas electrónicos automotrices.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            {{-- Escaneo --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Escaneo Vehicular</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Diagnóstico computarizado completo con equipos de última generación para detectar fallas en todos los sistemas.</p>
            </div>
            {{-- Reparación --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mb-4 group-hover:bg-green-500 transition-colors">
                    <svg class="w-6 h-6 text-green-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Reparación</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Reparación de módulos electrónicos, ECU, BCM, ECM y otros componentes del sistema vehicular.</p>
            </div>
            {{-- Diagnóstico Eléctrico --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mb-4 group-hover:bg-yellow-500 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Diagnóstico Eléctrico</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Identificación precisa de problemas eléctricos con equipos especializados y técnicas avanzadas.</p>
            </div>
            {{-- Sistemas Eléctricos --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mb-4 group-hover:bg-purple-500 transition-colors">
                    <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Sistemas Eléctricos</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Reparación de sistemas de iluminación, arranque, carga, climatización y accesorios eléctricos.</p>
            </div>
            {{-- Electrónica --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mb-4 group-hover:bg-indigo-500 transition-colors">
                    <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Electrónica</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Reparación a nivel de componente de tarjetas electrónicas, sensores y actuadores automotrices.</p>
            </div>
            {{-- Mantenimiento --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-orange-100 flex items-center justify-center mb-4 group-hover:bg-orange-500 transition-colors">
                    <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mantenimiento</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Mantenimiento preventivo y correctivo de sistemas electrónicos para prevenir fallas futuras.</p>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="py-16 lg:py-20" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">1500<span class="text-blue-200">+</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Vehículos Diagnosticados</p>
            </div>
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">15<span class="text-blue-200">+</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Años de Experiencia</p>
            </div>
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">8000<span class="text-blue-200">+</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Diagnósticos Realizados</p>
            </div>
            <div class="text-center">
                <p class="text-4xl lg:text-5xl font-bold text-white">98<span class="text-blue-200">%</span></p>
                <p class="mt-2 text-blue-200 text-sm font-medium">Satisfacción del Cliente</p>
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">¿Cómo Funciona?</h2>
            <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">Un proceso simple y transparente para el diagnóstico y reparación de tu vehículo.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            {{-- Step 1 --}}
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold text-blue-600">1</span>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">Registro</h3>
                <p class="text-sm text-gray-500">Registra tu cuenta y agrega tu vehículo al sistema.</p>
            </div>
            {{-- Step 2 --}}
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold text-blue-600">2</span>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">Diagnóstico</h3>
                <p class="text-sm text-gray-500">Realizamos un escaneo completo con equipos especializados.</p>
            </div>
            {{-- Step 3 --}}
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold text-blue-600">3</span>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">Presupuesto</h3>
                <p class="text-sm text-gray-500">Recibes una cotización detallada con costos transparentes.</p>
            </div>
            {{-- Step 4 --}}
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-xl font-bold text-blue-600">4</span>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">Reparación</h3>
                <p class="text-sm text-gray-500">Nuestros técnicos realizan la reparación con garantía.</p>
            </div>
            {{-- Step 5 --}}
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20,6 9,17 4,12"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-2">Entrega</h3>
                <p class="text-sm text-gray-500">Recibe tu vehículo listo con reporte detallado.</p>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Lo Que Dicen Nuestros Clientes</h2>
            <p class="mt-4 text-lg text-gray-500">Miles de clientes satisfechos confían en nosotros.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Testimonial 1 --}}
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">"Excelente servicio. Diagnosticaron un problema que otros talleres no pudieron encontrar. Mi auto quedó como nuevo. Totalmente recomendados."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold">CM</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Carlos Mendoza</p>
                        <p class="text-xs text-gray-500">Toyota Corolla 2021</p>
                    </div>
                </div>
            </div>
            {{-- Testimonial 2 --}}
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">"Muy profesionales. La plataforma me permite ver el estado de mi orden en tiempo real. El diagnóstico fue preciso y el presupuesto sin sorpresas."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-bold">MG</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">María García</p>
                        <p class="text-xs text-gray-500">Honda Civic 2022</p>
                    </div>
                </div>
            </div>
            {{-- Testimonial 3 --}}
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mb-4">"Repararon la ECU de mi camioneta en tiempo récord. El servicio fue rápido, eficiente y con garantía. Ahora son mi taller de confianza."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white text-sm font-bold">RL</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Roberto López</p>
                        <p class="text-xs text-gray-500">Ford Ranger 2020</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 lg:py-24" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #1e40af 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold text-white">¿Lista tu vehículo hoy?</h2>
        <p class="mt-4 text-lg text-blue-200/80 max-w-2xl mx-auto">Regístrate gratis y agenda un diagnóstico. Nosotros nos encargamos del resto.</p>
        <div class="mt-8">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-500 hover:bg-blue-600 text-white text-base font-semibold rounded-lg transition-colors shadow-lg shadow-blue-500/25">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Registrar mi Cuenta
            </a>
        </div>
    </div>
</section>

{{-- Trusted Brands Bar --}}
<section class="py-12 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm font-medium text-gray-400 mb-8 uppercase tracking-wider">Marcas que confían en nosotros</p>
        <div class="flex flex-wrap items-center justify-center gap-8 lg:gap-16">
            <span class="text-2xl font-bold text-gray-300 hover:text-gray-400 transition-colors">Toyota</span>
            <span class="text-2xl font-bold text-gray-300 hover:text-gray-400 transition-colors">Honda</span>
            <span class="text-2xl font-bold text-gray-300 hover:text-gray-400 transition-colors">Ford</span>
            <span class="text-2xl font-bold text-gray-300 hover:text-gray-400 transition-colors">Chevrolet</span>
            <span class="text-2xl font-bold text-gray-300 hover:text-gray-400 transition-colors">Nissan</span>
            <span class="text-2xl font-bold text-gray-300 hover:text-gray-400 transition-colors">Volkswagen</span>
        </div>
    </div>
</section>

@endsection
