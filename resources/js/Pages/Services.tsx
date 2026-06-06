import React from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import {
    CpuChipIcon,
    EyeIcon,
    WrenchScrewdriverIcon,
    BoltIcon,
    TruckIcon,
    SparklesIcon,
    ArrowRightIcon,
    CheckCircleIcon,
} from '@heroicons/react/24/outline';

const services = [
    {
        title: 'Diagnóstico Electrónico',
        description: 'Detección precisa de fallas electrónicas con equipos de última generación. Analizamos todos los módulos de control de tu vehículo.',
        features: ['Escaneo de módulos', 'Análisis de sensores', 'Detección de fallas', 'Reporte detallado'],
        icon: CpuChipIcon,
        gradient: 'from-blue-500 to-cyan-500',
        price: 'Desde $500',
    },
    {
        title: 'Escaneo OBD-II',
        description: 'Lectura y análisis de códigos de falla OBD-II para identificar problemas mecánicos y electrónicos en tiempo real.',
        features: ['Lectura de códigos', 'Datos en vivo', 'Pruebas especiales', 'Borrado de códigos'],
        icon: EyeIcon,
        gradient: 'from-purple-500 to-indigo-500',
        price: 'Desde $300',
    },
    {
        title: 'Mantenimiento Preventivo',
        description: 'Programas de mantenimiento personalizados para extender la vida útil de tu vehículo y prevenir fallas costosas.',
        features: ['Cambio de aceite', 'Revisión de frenos', 'Filtros y fluidos', 'Inspección general'],
        icon: WrenchScrewdriverIcon,
        gradient: 'from-orange-500 to-amber-500',
        price: 'Desde $800',
    },
    {
        title: 'Reparación Eléctrica',
        description: 'Solución especializada de problemas eléctricos, incluyendo sistemas de arranque, carga, iluminación y más.',
        features: ['Sistema de arranque', 'Alternadores', 'Instalaciones', 'Diagnóstico de cortos'],
        icon: BoltIcon,
        gradient: 'from-yellow-500 to-orange-500',
        price: 'Cotización personalizada',
    },
    {
        title: 'Mecánica General',
        description: 'Servicios integrales de mecánica: motor, transmisión, suspensión, frenos, dirección y sistemas de refrigeración.',
        features: ['Motor', 'Transmisión', 'Suspensión', 'Frenos'],
        icon: TruckIcon,
        gradient: 'from-emerald-500 to-teal-500',
        price: 'Cotización personalizada',
    },
    {
        title: 'Carrocería y Pintura',
        description: 'Reparación de carrocería, alineación, pintura profesional y acabados de alta calidad para tu vehículo.',
        features: ['Alineación de chapa', 'Pintura profesional', 'Acabados', 'Pulido y encerado'],
        icon: SparklesIcon,
        gradient: 'from-rose-500 to-pink-500',
        price: 'Cotización personalizada',
    },
];

export default function Services() {
    return (
        <>
            <Head title="Servicios - AutoScan" />
            <PublicLayout>
                {/* Hero */}
                <section className="bg-gradient-to-br from-dark-900 to-primary-900 py-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 className="text-4xl sm:text-5xl font-extrabold text-white">
                            Nuestros Servicios
                        </h1>
                        <p className="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">
                            Soluciones integrales con tecnología de punta para el
                            cuidado y mantenimiento de tu vehículo.
                        </p>
                    </div>
                </section>

                {/* Services list */}
                <section className="py-20 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="space-y-16">
                            {services.map((service, index) => (
                                <div
                                    key={service.title}
                                    className={`flex flex-col lg:flex-row items-center gap-12 ${
                                        index % 2 === 1 ? 'lg:flex-row-reverse' : ''
                                    }`}
                                >
                                    {/* Icon area */}
                                    <div className="flex-shrink-0 w-full lg:w-1/2">
                                        <div
                                            className={`flex items-center justify-center h-64 rounded-2xl bg-gradient-to-br ${service.gradient} relative overflow-hidden`}
                                        >
                                            <div className="absolute inset-0 opacity-10" style={{ backgroundImage: 'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0)', backgroundSize: '20px 20px' }} />
                                            <service.icon className="h-24 w-24 text-white/90" />
                                        </div>
                                    </div>

                                    {/* Content */}
                                    <div className="w-full lg:w-1/2">
                                        <h2 className="text-2xl font-bold text-gray-900">
                                            {service.title}
                                        </h2>
                                        <p className="mt-3 text-gray-600 leading-relaxed">
                                            {service.description}
                                        </p>

                                        <div className="mt-6 grid grid-cols-2 gap-3">
                                            {service.features.map((feature) => (
                                                <div
                                                    key={feature}
                                                    className="flex items-center gap-2"
                                                >
                                                    <CheckCircleIcon className="h-5 w-5 text-green-500 shrink-0" />
                                                    <span className="text-sm text-gray-700">{feature}</span>
                                                </div>
                                            ))}
                                        </div>

                                        <div className="mt-6 flex items-center justify-between">
                                            <span className="text-lg font-bold text-primary-600">
                                                {service.price}
                                            </span>
                                            <Link
                                                href="/contacto"
                                                className="inline-flex items-center gap-1 text-primary-600 font-medium hover:text-primary-700 text-sm"
                                            >
                                                Solicitar cotización
                                                <ArrowRightIcon className="w-4 h-4" />
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA */}
                <section className="py-16 bg-white">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h2 className="text-3xl font-bold text-gray-900">
                            ¿No encuentras el servicio que necesitas?
                        </h2>
                        <p className="mt-4 text-gray-600">
                            Contáctanos y te ofreceremos una solución
                            personalizada para tu vehículo.
                        </p>
                        <Link
                            href="/contacto"
                            className="mt-8 inline-flex items-center gap-2 btn-primary py-3 px-8 text-base"
                        >
                            Contactar Ahora
                            <ArrowRightIcon className="w-5 h-5" />
                        </Link>
                    </div>
                </section>
            </PublicLayout>
        </>
    );
}
