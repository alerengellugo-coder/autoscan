@extends('layouts.public')

@section('title', 'Servicios - AutoScan')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Nuestros Servicios</h1>
        <p class="mt-4 text-lg text-blue-200/80 max-w-2xl mx-auto">Soluciones profesionales para el diagnóstico, reparación y mantenimiento de sistemas electrónicos automotrices.</p>
    </div>
</section>

{{-- Service 1: Escaneo Vehicular --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-4">Servicio Principal</div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Escaneo Vehicular Computarizado</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">Utilizamos equipos de diagnóstico de última generación para escanear todos los sistemas electrónicos de tu vehículo. Nuestro escaneo abarca:</p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg>
                        <span class="text-sm text-gray-600">Motor y transmisión (OBD-II)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg>
                        <span class="text-sm text-gray-600">Sistema de frenos ABS</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg>
                        <span class="text-sm text-gray-600">Airbags y sistemas de seguridad</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg>
                        <span class="text-sm text-gray-600">Sistema de climatización</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg>
                        <span class="text-sm text-gray-600">Tablero, instrumentos y red CAN</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">Agendar Escaneo</a>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center">
                <svg class="w-32 h-32 text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                </svg>
            </div>
        </div>
    </div>
</section>

{{-- Service 2: Reparación --}}
<section class="py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center">
                <svg class="w-32 h-32 text-green-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                </svg>
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Reparación de Módulos</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">Reparamos módulos electrónicos a nivel de componente, evitando el costo de reemplazo completo. Nuestros servicios incluyen:</p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Reparación de ECU / ECM / BCM</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Reparación de TCM (módulo de transmisión)</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Reparación de módulos de airbag</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Reballing y microsoldadura SMD</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Diagnóstico y reparación de tableros</span></li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">Solicitar Reparación</a>
            </div>
        </div>
    </div>
</section>

{{-- Service 3: Diagnóstico Eléctrico --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Diagnóstico Eléctrico Avanzado</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">Identificamos fallas eléctricas complejas con equipos especializados. Incluye análisis de:</p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Cortos circuitos y fallas de aislamiento</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Análisis de consumo de corriente</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Pruebas de sensores y actuadores</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Diagnóstico de red CAN / LIN / FlexRay</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Osciloscopio y multímetro profesional</span></li>
                </ul>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center">
                <svg class="w-32 h-32 text-yellow-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
        </div>
    </div>
</section>

{{-- Service 4: Sistemas Eléctricos --}}
<section class="py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center">
                <svg class="w-32 h-32 text-purple-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Sistemas Eléctricos</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">Reparación integral de los sistemas eléctricos de tu vehículo:</p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Sistema de iluminación completo</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Sistema de arranque y carga</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Climatización y A/C electrónico</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Cerraduras, vidrios y espejos eléctricos</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Sistema de audio y multimedia</span></li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Service 5: Electrónica --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Electrónica Automotriz</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">Servicios de electrónica avanzada a nivel de componente:</p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Reprogramación de ECU</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Clonación de inmovilizadores</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Elaboración y programación de llaves</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Eliminación de DTC y adaptaciones</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-indigo-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Actualización de software de módulos</span></li>
                </ul>
            </div>
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center">
                <svg class="w-32 h-32 text-indigo-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/></svg>
            </div>
        </div>
    </div>
</section>

{{-- Service 6: Mantenimiento --}}
<section class="py-16 lg:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1 bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center">
                <svg class="w-32 h-32 text-orange-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Mantenimiento Electrónico</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">Mantenimiento preventivo y correctivo para prolongar la vida de tus sistemas electrónicos:</p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Revisión periódica de sistemas</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Limpieza de conectores y terminales</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Verificación de batería y alternador</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Actualización de software de fábrica</span></li>
                    <li class="flex items-start gap-3"><svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20,6 9,17 4,12"/></svg><span class="text-sm text-gray-600">Programa de mantenimiento preventivo</span></li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 inline-flex items-center gap-2 px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg transition-colors">Agendar Mantenimiento</a>
            </div>
        </div>
    </div>
</section>

@endsection
