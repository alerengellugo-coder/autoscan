@extends('layouts.public')

@section('title', 'Nosotros - AutoScan')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">Sobre Nosotros</h1>
        <p class="mt-4 text-lg text-blue-200/80 max-w-2xl mx-auto">Más de 15 años de experiencia en diagnóstico y reparación electrónica automotriz.</p>
    </div>
</section>

{{-- Our Story --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 lg:p-12 flex items-center justify-center min-h-[300px]">
                <svg class="w-32 h-32 text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Nuestra Historia</h2>
                <p class="mt-4 text-gray-600 leading-relaxed">AutoScan nació de la pasión por la tecnología automotriz y la necesidad de ofrecer un servicio especializado y honesto. Desde nuestros inicios, nos hemos comprometido a utilizar la tecnología más avanzada para brindar diagnósticos precisos y reparaciones de calidad.</p>
                <p class="mt-4 text-gray-600 leading-relaxed">Hoy contamos con un equipo de técnicos certificados y un taller equipado con las herramientas más sofisticadas del mercado, todo para garantizar la mejor atención para tu vehículo.</p>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Values --}}
<section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Misión y Valores</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Misión</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Brindar soluciones tecnológicas confiables en diagnóstico y reparación electrónica automotriz, con transparencia y honestidad.</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22,4 12,14.01 9,11.01"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Visión</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Ser el centro de diagnóstico electrónico automotriz líder en la región, reconocido por nuestra excelencia y calidad de servicio.</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Valores</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Honestidad, profesionalismo, innovación tecnológica, compromiso con el cliente y mejora continua.</p>
            </div>
        </div>
    </div>
</section>

{{-- Team --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900">Nuestro Equipo</h2>
            <p class="mt-4 text-lg text-gray-500">Profesionales certificados con años de experiencia.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Team Member 1 --}}
            <div class="text-center">
                <div class="w-24 h-24 rounded-full bg-blue-500 flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">JR</div>
                <h3 class="text-base font-semibold text-gray-900">Juan Rodríguez</h3>
                <p class="text-sm text-blue-600 font-medium">Director General</p>
                <p class="mt-2 text-xs text-gray-500">15+ años de experiencia en electrónica automotriz</p>
            </div>
            {{-- Team Member 2 --}}
            <div class="text-center">
                <div class="w-24 h-24 rounded-full bg-green-500 flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">AP</div>
                <h3 class="text-base font-semibold text-gray-900">Ana Pérez</h3>
                <p class="text-sm text-blue-600 font-medium">Técnica Especialista</p>
                <p class="mt-2 text-xs text-gray-500">Certificada en diagnóstico OBD-II y redes CAN</p>
            </div>
            {{-- Team Member 3 --}}
            <div class="text-center">
                <div class="w-24 h-24 rounded-full bg-purple-500 flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">MH</div>
                <h3 class="text-base font-semibold text-gray-900">Miguel Hernández</h3>
                <p class="text-sm text-blue-600 font-medium">Técnico en Reparación</p>
                <p class="mt-2 text-xs text-gray-500">Especialista en reballing y microsoldadura</p>
            </div>
            {{-- Team Member 4 --}}
            <div class="text-center">
                <div class="w-24 h-24 rounded-full bg-orange-500 flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold">LV</div>
                <h3 class="text-base font-semibold text-gray-900">Laura Vargas</h3>
                <p class="text-sm text-blue-600 font-medium">Atención al Cliente</p>
                <p class="mt-2 text-xs text-gray-500">Gestión de órdenes y atención personalizada</p>
            </div>
        </div>
    </div>
</section>

@endsection
