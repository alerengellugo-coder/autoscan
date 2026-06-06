import React, { useEffect, useRef, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '../../Layouts/PublicLayout';
import {
    BoltIcon,
    CpuChipIcon,
    WrenchScrewdriverIcon,
    ShieldCheckIcon,
    TruckIcon,
    SparklesIcon,
    ArrowRightIcon,
    CheckCircleIcon,
    StarIcon,
    ClockIcon,
    UsersIcon,
    EyeIcon,
    DocumentTextIcon,
    PlayIcon,
} from '@heroicons/react/24/outline';
import { PageProps } from '../../types';

const services = [
    {
        title: 'Diagnóstico Electrónico',
        description:
            'Detección precisa de fallas electrónicas con equipos de última generación. Escaneamos todos los módulos de tu vehículo.',
        icon: CpuChipIcon,
        gradient: 'from-blue-500 to-cyan-500',
    },
    {
        title: 'Escaneo OBD-II',
        description:
            'Lectura y análisis de códigos de falla OBD-II para identificar problemas mecánicos y electrónicos en tiempo real.',
        icon: EyeIcon,
        gradient: 'from-purple-500 to-indigo-500',
    },
    {
        title: 'Mantenimiento Preventivo',
        description:
            'Programas de mantenimiento personalizados para extender la vida útil de tu vehículo y prevenir fallas costosas.',
        icon: WrenchScrewdriverIcon,
        gradient: 'from-orange-500 to-amber-500',
    },
    {
        title: 'Reparación Eléctrica',
        description:
            'Solución especializada de problemas eléctricos, incluyendo sistemas de arranque, carga, iluminación y más.',
        icon: BoltIcon,
        gradient: 'from-yellow-500 to-orange-500',
    },
    {
        title: 'Mecánica General',
        description:
            'Servicios integrales de mecánica: motor, transmisión, suspensión, frenos, dirección y sistemas de refrigeración.',
        icon: TruckIcon,
        gradient: 'from-emerald-500 to-teal-500',
    },
    {
        title: 'Carrocería y Pintura',
        description:
            'Reparación de carrocería, alineación, pintura profesional y acabados de alta calidad para tu vehículo.',
        icon: SparklesIcon,
        gradient: 'from-rose-500 to-pink-500',
    },
];

const testimonials = [
    {
        name: 'Carlos Mendoza',
        role: 'Empresario',
        text: 'Excelente servicio. Detectaron un problema eléctrico que otros talleres no pudieron encontrar. Muy profesionales.',
        rating: 5,
        vehicle: 'Toyota Camry 2022',
    },
    {
        name: 'María García',
        role: 'Profesora',
        text: 'El escaneo OBD-II fue rápido y preciso. Me explicaron todo con detalle y el presupuesto fue transparente.',
        rating: 5,
        vehicle: 'Honda CR-V 2021',
    },
    {
        name: 'Roberto Sánchez',
        role: 'Ingeniero',
        text: 'Llevo 3 años como cliente y siempre han dado el mejor servicio. La plataforma digital me permite seguir el estado de mi vehículo.',
        rating: 5,
        vehicle: 'Ford F-150 2023',
    },
];

function useCountUp(end: number, duration: number = 2000) {
    const [count, setCount] = useState(0);
    const ref = useRef<HTMLDivElement>(null);
    const [started, setStarted] = useState(false);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting && !started) {
                    setStarted(true);
                }
            },
            { threshold: 0.3 }
        );
        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect();
    }, [started]);

    useEffect(() => {
        if (!started) return;
        let startTime: number | null = null;
        const step = (timestamp: number) => {
            if (!startTime) startTime = timestamp;
            const progress = Math.min(
                (timestamp - startTime) / duration,
                1
            );
            setCount(Math.floor(progress * end));
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    }, [started, end, duration]);

    return { count, ref };
}

export default function Home() {
    const stat1 = useCountUp(1000);
    const stat2 = useCountUp(15);
    const stat3 = useCountUp(5000);
    const stat4 = useCountUp(98);

    return (
        <>
            <Head title="AutoScan - Diagnóstico Profesional para tu Vehículo" />
            <PublicLayout>
                {/* ===== HERO SECTION ===== */}
                <section className="relative overflow-hidden bg-gradient-to-br from-dark-900 via-primary-900 to-dark-900">
                    {/* Background decorations */}
                    <div className="absolute inset-0">
                        <div className="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-primary-500/10 blur-3xl" />
                        <div
                            className="absolute bottom-0 right-1/4 w-96 h-96 rounded-full bg-secondary-500/10 blur-3xl"
                        />
                        <div
                            className="absolute inset-0 opacity-5"
                            style={{
                                backgroundImage:
                                    'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0)',
                                backgroundSize: '32px 32px',
                            }}
                        />
                        {/* Floating geometric shapes */}
                        <div className="absolute top-20 right-20 w-20 h-20 border border-primary-400/20 rounded-lg rotate-45 animate-spin-slow" />
                        <div className="absolute bottom-32 left-32 w-16 h-16 border border-secondary-400/20 rounded-full animate-spin-slow" />
                        <div className="absolute top-40 left-1/3 w-3 h-3 bg-primary-400/40 rounded-full animate-pulse" />
                        <div className="absolute bottom-48 right-1/3 w-2 h-2 bg-secondary-400/40 rounded-full animate-pulse" />
                    </div>

                    <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
                        <div className="grid lg:grid-cols-2 gap-12 items-center">
                            {/* Left content */}
                            <div className="text-center lg:text-left animate-slide-up">
                                <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-500/20 border border-primary-400/30 mb-6">
                                    <span className="relative flex h-2 w-2">
                                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                        <span className="relative inline-flex rounded-full h-2 w-2 bg-primary-400"></span>
                                    </span>
                                    <span className="text-sm font-medium text-primary-300">
                                        Tecnología de vanguardia
                                    </span>
                                </div>

                                <h1 className="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                                    Diagnóstico{' '}
                                    <span className="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-400">
                                        Profesional
                                    </span>{' '}
                                    para tu Vehículo
                                </h1>

                                <p className="mt-6 text-lg text-gray-300 max-w-xl mx-auto lg:mx-0">
                                    Escaneo electrónico avanzado, diagnóstico
                                    computarizado y mantenimiento integral con
                                    tecnología de última generación. Tu vehículo
                                    en las mejores manos.
                                </p>

                                <div className="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                    <Link
                                        href="/register"
                                        className="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold py-3.5 px-8 rounded-xl shadow-lg shadow-primary-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-primary-500/30 hover:-translate-y-0.5"
                                    >
                                        Comenzar Ahora
                                        <ArrowRightIcon className="h-5 w-5" />
                                    </Link>
                                    <Link
                                        href="/servicios"
                                        className="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-3.5 px-8 rounded-xl border border-white/20 backdrop-blur-sm transition-all duration-300"
                                    >
                                        <PlayIcon className="h-5 w-5" />
                                        Ver Servicios
                                    </Link>
                                </div>

                                {/* Trust indicators */}
                                <div className="mt-12 flex items-center gap-8 justify-center lg:justify-start">
                                    <div className="flex -space-x-2">
                                        {[1, 2, 3, 4].map((i) => (
                                            <div
                                                key={i}
                                                className="w-10 h-10 rounded-full border-2 border-dark-900 bg-gradient-to-br from-primary-400 to-secondary-400 flex items-center justify-center text-xs font-bold text-white"
                                            >
                                                {['CM', 'MG', 'RS', 'AL'][i - 1]}
                                            </div>
                                        ))}
                                    </div>
                                    <div>
                                        <div className="flex items-center gap-1">
                                            {[1, 2, 3, 4, 5].map((i) => (
                                                <StarIcon
                                                    key={i}
                                                    className="h-4 w-4 text-yellow-400"
                                                    fill="currentColor"
                                                />
                                            ))}
                                        </div>
                                        <p className="text-xs text-gray-400 mt-0.5">
                                            +500 clientes satisfechos
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {/* Right - Car illustration */}
                            <div className="hidden lg:flex justify-center animate-fade-in">
                                <div className="relative">
                                    {/* Glow effect */}
                                    <div className="absolute inset-0 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-full blur-3xl scale-110" />

                                    {/* SVG illustration */}
                                    <svg
                                        className="relative w-[480px] h-[320px]"
                                        viewBox="0 0 480 320"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        {/* Ground line */}
                                        <line
                                            x1="40"
                                            y1="260"
                                            x2="440"
                                            y2="260"
                                            stroke="rgba(255,255,255,0.1)"
                                            strokeWidth="1"
                                            strokeDasharray="8 6"
                                        />

                                        {/* Car body */}
                                        <path
                                            d="M120 230 L135 170 L180 130 L310 130 L350 170 L360 190 L390 200 L390 230 L120 230Z"
                                            stroke="url(#carGradient)"
                                            strokeWidth="2.5"
                                            fill="rgba(14,165,233,0.08)"
                                        />
                                        {/* Windows */}
                                        <path
                                            d="M188 140 L190 130 L310 130 L340 160 L188 160Z"
                                            stroke="rgba(14,165,233,0.4)"
                                            strokeWidth="1.5"
                                            fill="rgba(14,165,233,0.05)"
                                        />
                                        <line
                                            x1="245"
                                            y1="130"
                                            x2="243"
                                            y2="160"
                                            stroke="rgba(14,165,233,0.3)"
                                            strokeWidth="1"
                                        />
                                        {/* Headlights */}
                                        <rect
                                            x="374"
                                            y="200"
                                            width="16"
                                            height="12"
                                            rx="3"
                                            fill="rgba(249,115,22,0.5)"
                                        />
                                        <rect
                                            x="120"
                                            y="200"
                                            width="12"
                                            height="12"
                                            rx="3"
                                            fill="rgba(249,115,22,0.3)"
                                        />
                                        {/* Wheels */}
                                        <circle
                                            cx="175"
                                            cy="240"
                                            r="22"
                                            stroke="rgba(255,255,255,0.4)"
                                            strokeWidth="2"
                                        />
                                        <circle
                                            cx="175"
                                            cy="240"
                                            r="12"
                                            stroke="rgba(255,255,255,0.2)"
                                            strokeWidth="1.5"
                                        />
                                        <circle
                                            cx="340"
                                            cy="240"
                                            r="22"
                                            stroke="rgba(255,255,255,0.4)"
                                            strokeWidth="2"
                                        />
                                        <circle
                                            cx="340"
                                            cy="240"
                                            r="12"
                                            stroke="rgba(255,255,255,0.2)"
                                            strokeWidth="1.5"
                                        />

                                        {/* Scan beam */}
                                        <rect
                                            x="200"
                                            y="80"
                                            width="80"
                                            height="50"
                                            rx="8"
                                            stroke="rgba(14,165,233,0.3)"
                                            strokeWidth="1"
                                            fill="rgba(14,165,233,0.05)"
                                            strokeDasharray="4 3"
                                        />
                                        {/* Scan lines */}
                                        <line
                                            x1="230"
                                            y1="50"
                                            x2="230"
                                            y2="85"
                                            stroke="rgba(14,165,233,0.6)"
                                            strokeWidth="2"
                                            strokeDasharray="6 4"
                                        />
                                        <line
                                            x1="240"
                                            y1="42"
                                            x2="240"
                                            y2="85"
                                            stroke="rgba(14,165,233,0.8)"
                                            strokeWidth="2.5"
                                        />
                                        <line
                                            x1="250"
                                            y1="50"
                                            x2="250"
                                            y2="85"
                                            stroke="rgba(14,165,233,0.6)"
                                            strokeWidth="2"
                                            strokeDasharray="6 4"
                                        />

                                        {/* Data points */}
                                        <circle cx="210" cy="90" r="3" fill="rgba(249,115,22,0.7)">
                                            <animate attributeName="opacity" values="0.4;1;0.4" dur="2s" repeatCount="indefinite" />
                                        </circle>
                                        <circle cx="270" cy="100" r="3" fill="rgba(14,165,233,0.7)">
                                            <animate attributeName="opacity" values="0.4;1;0.4" dur="2s" begin="0.5s" repeatCount="indefinite" />
                                        </circle>
                                        <circle cx="320" cy="85" r="3" fill="rgba(52,211,153,0.7)">
                                            <animate attributeName="opacity" values="0.4;1;0.4" dur="2s" begin="1s" repeatCount="indefinite" />
                                        </circle>

                                        {/* Connection lines */}
                                        <line
                                            x1="213"
                                            y1="92"
                                            x2="230"
                                            y2="100"
                                            stroke="rgba(249,115,22,0.3)"
                                            strokeWidth="0.5"
                                        />
                                        <line
                                            x1="267"
                                            y1="102"
                                            x2="240"
                                            y2="110"
                                            stroke="rgba(14,165,233,0.3)"
                                            strokeWidth="0.5"
                                        />

                                        <defs>
                                            <linearGradient
                                                id="carGradient"
                                                x1="120"
                                                y1="130"
                                                x2="390"
                                                y2="230"
                                            >
                                                <stop offset="0%" stopColor="#38bdf8" />
                                                <stop offset="100%" stopColor="#f97316" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Wave divider */}
                    <div className="absolute bottom-0 left-0 right-0">
                        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0 80L60 68C120 56 240 32 360 24C480 16 600 24 720 32C840 40 960 48 1080 44C1200 40 1320 24 1380 16L1440 8V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z"
                                fill="#f9fafb"
                            />
                        </svg>
                    </div>
                </section>

                {/* ===== SERVICES SECTION ===== */}
                <section className="py-20 lg:py-28 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {/* Section header */}
                        <div className="text-center max-w-3xl mx-auto mb-16">
                            <span className="text-sm font-semibold text-primary-600 uppercase tracking-wider">
                                Nuestros Servicios
                            </span>
                            <h2 className="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">
                                Soluciones Integrales para tu{' '}
                                <span className="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500">
                                    Vehículo
                                </span>
                            </h2>
                            <p className="mt-4 text-lg text-gray-600">
                                Ofrecemos una amplia gama de servicios
                                automotrices con tecnología de punta y
                                profesionales certificados.
                            </p>
                        </div>

                        {/* Services grid */}
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                            {services.map((service, index) => (
                                <div
                                    key={service.title}
                                    className="group relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-card-hover hover:-translate-y-1 transition-all duration-300"
                                    style={{
                                        animationDelay: `${index * 100}ms`,
                                    }}
                                >
                                    {/* Icon */}
                                    <div
                                        className={`inline-flex items-center justify-center w-14 h-14 rounded-xl bg-gradient-to-br ${service.gradient} shadow-lg mb-6`}
                                    >
                                        <service.icon className="w-7 h-7 text-white" />
                                    </div>

                                    <h3 className="text-xl font-bold text-gray-900 mb-3">
                                        {service.title}
                                    </h3>
                                    <p className="text-gray-600 text-sm leading-relaxed">
                                        {service.description}
                                    </p>

                                    {/* Hover arrow */}
                                    <div className="mt-6 flex items-center text-primary-600 font-medium text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        Más información
                                        <ArrowRightIcon className="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                                    </div>

                                    {/* Corner decoration */}
                                    <div className="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-primary-50 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ===== WHY CHOOSE US SECTION ===== */}
                <section className="py-20 lg:py-28 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid lg:grid-cols-2 gap-16 items-center">
                            {/* Left - Stats */}
                            <div className="grid grid-cols-2 gap-6">
                                <div ref={stat1.ref} className="card text-center py-8 hover:shadow-card-hover transition-shadow">
                                    <div className="text-4xl font-extrabold text-primary-600">
                                        {stat1.count.toLocaleString()}+
                                    </div>
                                    <p className="mt-2 text-sm text-gray-500 font-medium">
                                        Vehículos Atendidos
                                    </p>
                                </div>
                                <div ref={stat2.ref} className="card text-center py-8 hover:shadow-card-hover transition-shadow">
                                    <div className="text-4xl font-extrabold text-secondary-600">
                                        {stat2.count}+
                                    </div>
                                    <p className="mt-2 text-sm text-gray-500 font-medium">
                                        Años de Experiencia
                                    </p>
                                </div>
                                <div ref={stat3.ref} className="card text-center py-8 hover:shadow-card-hover transition-shadow">
                                    <div className="text-4xl font-extrabold text-emerald-600">
                                        {stat3.count.toLocaleString()}+
                                    </div>
                                    <p className="mt-2 text-sm text-gray-500 font-medium">
                                        Diagnósticos Realizados
                                    </p>
                                </div>
                                <div ref={stat4.ref} className="card text-center py-8 hover:shadow-card-hover transition-shadow">
                                    <div className="text-4xl font-extrabold text-purple-600">
                                        {stat4.count}%
                                    </div>
                                    <p className="mt-2 text-sm text-gray-500 font-medium">
                                        Clientes Satisfechos
                                    </p>
                                </div>
                            </div>

                            {/* Right - Content */}
                            <div>
                                <span className="text-sm font-semibold text-primary-600 uppercase tracking-wider">
                                    ¿Por Qué Elegirnos?
                                </span>
                                <h2 className="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">
                                    Experiencia y Tecnología a tu Servicio
                                </h2>
                                <p className="mt-4 text-gray-600 leading-relaxed">
                                    Combinamos años de experiencia en el sector
                                    automotriz con la tecnología más avanzada
                                    para ofrecerte diagnósticos precisos y
                                    soluciones efectivas.
                                </p>

                                <div className="mt-8 space-y-5">
                                    {[
                                        {
                                            icon: ShieldCheckIcon,
                                            title: 'Técnicos Certificados',
                                            desc: 'Personal altamente capacitado con certificaciones internacionales.',
                                        },
                                        {
                                            icon: CpuChipIcon,
                                            title: 'Equipos de Última Generación',
                                            desc: 'Escáneres y herramientas de diagnóstico de la más alta tecnología.',
                                        },
                                        {
                                            icon: DocumentTextIcon,
                                            title: 'Reportes Detallados',
                                            desc: 'Informes completos con fotos, hallazgos y recomendaciones.',
                                        },
                                        {
                                            icon: ClockIcon,
                                            title: 'Atención Ágil',
                                            desc: 'Procesos optimizados para entregarte tu vehículo en el menor tiempo.',
                                        },
                                    ].map((item) => (
                                        <div
                                            key={item.title}
                                            className="flex items-start gap-4"
                                        >
                                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-100">
                                                <item.icon className="h-5 w-5 text-primary-600" />
                                            </div>
                                            <div>
                                                <h3 className="font-semibold text-gray-900">
                                                    {item.title}
                                                </h3>
                                                <p className="mt-1 text-sm text-gray-500">
                                                    {item.desc}
                                                </p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* ===== TESTIMONIALS SECTION ===== */}
                <section className="py-20 lg:py-28 bg-gradient-to-b from-gray-50 to-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-3xl mx-auto mb-16">
                            <span className="text-sm font-semibold text-primary-600 uppercase tracking-wider">
                                Testimonios
                            </span>
                            <h2 className="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">
                                Lo que Dicen Nuestros Clientes
                            </h2>
                        </div>

                        <div className="grid md:grid-cols-3 gap-8">
                            {testimonials.map((t) => (
                                <div
                                    key={t.name}
                                    className="card hover:shadow-card-hover transition-shadow"
                                >
                                    {/* Stars */}
                                    <div className="flex gap-1 mb-4">
                                        {[...Array(t.rating)].map((_, i) => (
                                            <StarIcon
                                                key={i}
                                                className="h-5 w-5 text-yellow-400"
                                                fill="currentColor"
                                            />
                                        ))}
                                    </div>

                                    <p className="text-gray-600 text-sm leading-relaxed italic">
                                        "{t.text}"
                                    </p>

                                    <div className="mt-6 pt-4 border-t border-gray-100 flex items-center gap-3">
                                        <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 text-sm font-bold text-white">
                                            {t.name
                                                .split(' ')
                                                .map((n) => n[0])
                                                .join('')}
                                        </div>
                                        <div>
                                            <p className="text-sm font-semibold text-gray-900">
                                                {t.name}
                                            </p>
                                            <p className="text-xs text-gray-500">
                                                {t.role} · {t.vehicle}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ===== CTA SECTION ===== */}
                <section className="py-20 lg:py-28">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-700 to-dark-800 px-8 py-16 sm:px-16 sm:py-20">
                            {/* Background decorations */}
                            <div className="absolute top-0 right-0 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl" />
                            <div className="absolute bottom-0 left-0 w-64 h-64 bg-primary-400/10 rounded-full blur-3xl" />
                            <div
                                className="absolute inset-0 opacity-5"
                                style={{
                                    backgroundImage:
                                        'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0)',
                                    backgroundSize: '24px 24px',
                                }}
                            />

                            <div className="relative z-10 text-center max-w-2xl mx-auto">
                                <h2 className="text-3xl sm:text-4xl font-bold text-white">
                                    Registra tu Vehículo Hoy
                                </h2>
                                <p className="mt-4 text-lg text-primary-100">
                                    Únete a miles de clientes que confían en
                                    nosotros para el cuidado y mantenimiento de
                                    sus vehículos.
                                </p>
                                <div className="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link
                                        href="/register"
                                        className="inline-flex items-center justify-center gap-2 bg-white text-primary-700 font-semibold py-3.5 px-8 rounded-xl shadow-lg hover:bg-gray-50 transition-all duration-300 hover:-translate-y-0.5"
                                    >
                                        Crear Cuenta Gratis
                                        <ArrowRightIcon className="h-5 w-5" />
                                    </Link>
                                    <Link
                                        href="/contacto"
                                        className="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold py-3.5 px-8 rounded-xl border border-white/20 transition-all duration-300"
                                    >
                                        Contactar
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </PublicLayout>
        </>
    );
}
