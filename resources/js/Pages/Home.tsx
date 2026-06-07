import React, { useEffect, useRef, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import {
    CpuChipIcon,
    WrenchScrewdriverIcon,
    BoltIcon,
    ShieldCheckIcon,
    ClockIcon,
    DocumentTextIcon,
    ArrowRightIcon,
    StarIcon,
    CheckCircleIcon,
    EyeIcon,
    CubeIcon,
    TruckIcon,
    ChevronRightIcon,
} from '@heroicons/react/24/outline';

/* ─── Data ─── */

const services = [
    {
        title: 'Escaneo de Computadora',
        description: 'Diagnostico completo de la ECU y todos los modulos electronicos del vehiculo. Lectura de codigos DTC, datos en vivo y pruebas avanzadas.',
        icon: CpuChipIcon,
        color: 'blue',
    },
    {
        title: 'Reparacion de Modulos',
        description: 'Reparacion de ECU, BCM, TCM, ABS y demas modulos electronicos. Flasheo de software y recalibracion de componentes.',
        icon: BoltIcon,
        color: 'violet',
    },
    {
        title: 'Diagnostico Electrico',
        description: 'Deteccion de cortocircuitos, fallas en sistemas de arranque, carga, iluminacion, sensores y actuadores electronicos.',
        icon: EyeIcon,
        color: 'blue',
    },
    {
        title: 'Electronica Automotriz',
        description: 'Instalacion de sistemas electricos, alarmas, GPS, estereos, camaras de retroceso y accesorios electronicos premium.',
        icon: CubeIcon,
        color: 'violet',
    },
    {
        title: 'Sistemas Hibridos/Electricos',
        description: 'Diagnostico y reparacion de baterias de alto voltaje, motores electricos, inversores y sistemas de gestion de energia.',
        icon: ShieldCheckIcon,
        color: 'blue',
    },
    {
        title: 'Mantenimiento Preventivo',
        description: 'Programas de mantenimiento con diagnostico electronico periodico para prevenir fallas costosas y maximizar rendimiento.',
        icon: WrenchScrewdriverIcon,
        color: 'violet',
    },
];

const processSteps = [
    { step: '01', title: 'Check-in Digital', desc: 'Registras tu vehiculo y describes el problema a traves de nuestra plataforma.', icon: TruckIcon },
    { step: '02', title: 'Escaneo y Diagnostico', desc: 'Nuestros tecnicos realizan un escaneo completo con equipos de ultima generacion.', icon: CpuChipIcon },
    { step: '03', title: 'Presupuesto Aprobado', desc: 'Recibes un presupuesto detallado por email. Aprobas cuando estes listo.', icon: DocumentTextIcon },
    { step: '04', title: 'Reparacion', desc: 'Reparamos tu vehiculo y te notificamos cada avance del proceso.', icon: WrenchScrewdriverIcon },
    { step: '05', title: 'Entrega', desc: 'Verificamos todo, haces check-out y tu vehiculo queda listo como nuevo.', icon: CheckCircleIcon },
];

const testimonials = [
    { name: 'Carlos Mendoza', role: 'Empresario', text: 'Escanearon la computadora de mi Camry y encontraron un problema que 3 talleres no pudieron detectar. Servicio excepcional.', rating: 5, vehicle: 'Toyota Camry 2023' },
    { name: 'Maria Garcia', role: 'Profesora', text: 'Mi Honda tenia un problema electrico raro. En AutoScan lo diagnosticaron en 30 minutos y el presupuesto fue transparente.', rating: 5, vehicle: 'Honda CR-V 2022' },
    { name: 'Roberto Sanchez', role: 'Ingeniero', text: 'Llevo 3 anos como cliente. El sistema de notificaciones me permite seguir cada paso de la reparacion en tiempo real.', rating: 5, vehicle: 'Ford F-150 2024' },
];

/* ─── Hooks ─── */

function useCountUp(end: number, duration = 2000) {
    const [count, setCount] = useState(0);
    const ref = useRef<HTMLDivElement>(null);
    const [started, setStarted] = useState(false);

    useEffect(() => {
        const observer = new IntersectionObserver(([entry]) => {
            if (entry.isIntersecting && !started) setStarted(true);
        }, { threshold: 0.3 });
        if (ref.current) observer.observe(ref.current);
        return () => observer.disconnect();
    }, [started]);

    useEffect(() => {
        if (!started) return;
        let startTime: number | null = null;
        const step = (timestamp: number) => {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            setCount(Math.floor(progress * end));
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }, [started, end, duration]);

    return { count, ref };
}

/* ─── Component ─── */

export default function Home() {
    const stat1 = useCountUp(1500);
    const stat2 = useCountUp(15);
    const stat3 = useCountUp(8000);
    const stat4 = useCountUp(98);

    return (
        <>
            <Head title="AutoScan - Escaneo y Diagnostico Electronico Automotriz" />
            <PublicLayout>

                {/* ═══ HERO ═══ */}
                <section className="relative min-h-[90vh] flex items-center overflow-hidden bg-dark-950">
                    {/* Background image */}
                    <div className="absolute inset-0">
                        <img src="/images/hero-bg.jpg" alt="" className="w-full h-full object-cover opacity-40" />
                        <div className="absolute inset-0 bg-gradient-to-b from-dark-950/80 via-dark-950/60 to-dark-950" />
                        <div className="absolute inset-0 bg-gradient-to-r from-dark-950 via-transparent to-dark-950" />
                    </div>

                    {/* Animated grid overlay - more visible */}
                    <div className="absolute inset-0 opacity-[0.08]" style={{
                        backgroundImage: 'linear-gradient(rgba(59,130,246,1) 1px, transparent 1px), linear-gradient(90deg, rgba(59,130,246,1) 1px, transparent 1px)',
                        backgroundSize: '60px 60px'
                    }} />

                    {/* Glowing orbs - BRIGHTER */}
                    <div className="absolute top-1/4 left-1/6 w-[600px] h-[600px] rounded-full bg-blue-500/25 blur-[140px] animate-float" />
                    <div className="absolute bottom-1/4 right-1/6 w-[500px] h-[500px] rounded-full bg-violet-500/20 blur-[120px] animate-float" style={{ animationDelay: '1.5s' }} />
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] rounded-full bg-cyan-500/20 blur-[100px] animate-pulse-slow" />
                    {/* Extra accent orb */}
                    <div className="absolute top-1/3 right-1/3 w-[300px] h-[300px] rounded-full bg-blue-400/15 blur-[100px] animate-pulse-slow" style={{ animationDelay: '3s' }} />

                    <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                        <div className="max-w-3xl">
                            {/* Pill badge - brighter glow */}
                            <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/25 border border-blue-400/40 mb-8 animate-slide-up shadow-lg shadow-blue-500/10">
                                <span className="relative flex h-2.5 w-2.5">
                                    <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75" />
                                    <span className="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-300" />
                                </span>
                                <span className="text-sm font-medium text-blue-200">
                                    Especialistas en Electronica Automotriz
                                </span>
                            </div>

                            <h1 className="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white leading-[1.08] animate-slide-up">
                                Escaneamos tu{' '}
                                <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-violet-400 glow-text">
                                    Computadora
                                </span>
                                <br />
                                y reparamos tu{' '}
                                <span className="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-cyan-300 glow-text">
                                    Vehiculo
                                </span>
                            </h1>

                            <p className="mt-6 text-lg sm:text-xl text-dark-200 max-w-2xl leading-relaxed animate-slide-up" style={{ animationDelay: '100ms' }}>
                                Diagnostico electronico avanzado, reparacion de modulos (ECU/BCM/TCM),
                                escaneo OBD-II y mantenimiento preventivo. Todo con seguimiento digital en tiempo real.
                            </p>

                            <div className="mt-10 flex flex-col sm:flex-row gap-4 animate-slide-up" style={{ animationDelay: '200ms' }}>
                                <Link href="/register" className="btn-primary btn-lg bg-gradient-to-r from-blue-500 via-blue-600 to-violet-600 hover:from-blue-600 hover:via-blue-700 hover:to-violet-700 shadow-glow hover:shadow-glow-accent">
                                    Registrar mi Vehiculo
                                    <ArrowRightIcon className="h-5 w-5" />
                                </Link>
                                <Link href="/servicios" className="inline-flex items-center justify-center gap-2 bg-white/15 hover:bg-white/25 text-white font-semibold py-3.5 px-8 rounded-xl border border-white/30 backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/20">
                                    Ver Servicios
                                </Link>
                            </div>

                            {/* Quick stats */}
                            <div className="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-6 animate-slide-up" style={{ animationDelay: '300ms' }}>
                                {[
                                    { value: '15+', label: 'Anos de Experiencia' },
                                    { value: '1,500+', label: 'Vehiculos Atendidos' },
                                    { value: '8,000+', label: 'Diagnosticos Realizados' },
                                    { value: '98%', label: 'Satisfaccion' },
                                ].map((stat) => (
                                    <div key={stat.label} className="text-center sm:text-left">
                                        <div className="text-2xl sm:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300 glow-text">{stat.value}</div>
                                        <div className="text-sm text-dark-300 mt-0.5">{stat.label}</div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Bottom gradient */}
                    <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-dark-50 to-transparent" />
                </section>

                {/* ═══ SERVICES ═══ */}
                <section className="py-24 bg-dark-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-3xl mx-auto mb-16">
                            <span className="text-sm font-bold text-primary-600 uppercase tracking-widest">Nuestros Servicios</span>
                            <h2 className="mt-4 text-3xl sm:text-4xl font-extrabold text-dark-900">
                                Soluciones <span className="gradient-text">Especializadas</span> para tu Vehiculo
                            </h2>
                            <p className="mt-4 text-lg text-dark-500">
                                Desde el escaneo de la computadora hasta la reparacion de modulos electronicos. Tecnologia de punta para cualquier marca.
                            </p>
                        </div>

                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {services.map((service, i) => (
                                <div key={service.title} className="group card-hover cursor-pointer relative overflow-hidden hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary-500/15 transition-all duration-300" style={{ animationDelay: `${i * 80}ms` }}>
                                    {/* Colored left border accent */}
                                    <div className={`absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b ${
                                        service.color === 'blue'
                                            ? 'from-blue-500 to-cyan-400'
                                            : 'from-violet-500 to-purple-400'
                                    } opacity-0 group-hover:opacity-100 transition-opacity duration-300`} />
                                    {/* Colored top border on hover */}
                                    <div className={`absolute top-0 left-0 right-0 h-1 bg-gradient-to-r ${
                                        service.color === 'blue'
                                            ? 'from-blue-500 to-cyan-500'
                                            : 'from-violet-500 to-purple-400'
                                    } opacity-0 group-hover:opacity-100 transition-opacity duration-300`} />
                                    <div className={`inline-flex items-center justify-center w-14 h-14 rounded-2xl mb-5 transition-all duration-300 ${
                                        service.color === 'blue'
                                            ? 'bg-gradient-to-br from-blue-100 to-cyan-100 group-hover:from-blue-200 group-hover:to-cyan-200 group-hover:shadow-glow'
                                            : 'bg-gradient-to-br from-violet-100 to-purple-100 group-hover:from-violet-200 group-hover:to-purple-200 group-hover:shadow-glow-accent'
                                    }`}>
                                        <service.icon className={`w-7 h-7 ${service.color === 'blue' ? 'text-blue-600' : 'text-violet-600'}`} />
                                    </div>
                                    <h3 className="text-lg font-bold text-dark-900 mb-2">{service.title}</h3>
                                    <p className="text-sm text-dark-500 leading-relaxed">{service.description}</p>
                                    <div className="mt-5 flex items-center text-primary-600 text-sm font-semibold opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                        Conocer mas <ChevronRightIcon className="w-4 h-4 ml-0.5" />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ═══ PROCESS ═══ */}
                <section className="py-24 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-3xl mx-auto mb-16">
                            <span className="text-sm font-bold text-primary-600 uppercase tracking-widest">Como Funciona</span>
                            <h2 className="mt-4 text-3xl sm:text-4xl font-extrabold text-dark-900">
                                Tu Vehiculo en <span className="gradient-text">Buenas Manos</span>
                            </h2>
                            <p className="mt-4 text-lg text-dark-500">
                                Un proceso transparente y digital. Recibe notificaciones en cada etapa.
                            </p>
                        </div>

                        <div className="relative">
                            {/* Connection line (desktop) - more vibrant */}
                            <div className="hidden lg:block absolute top-16 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-blue-400 via-violet-500 to-cyan-400" />

                            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
                                {processSteps.map((item, i) => (
                                    <div key={item.step} className="relative text-center group" style={{ animationDelay: `${i * 100}ms` }}>
                                        {/* Step circle */}
                                        <div className="relative inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-violet-600 text-white font-extrabold text-lg shadow-glow group-hover:scale-110 transition-all duration-300 z-10">
                                            {item.step}
                                        </div>
                                        <div className="mt-5">
                                            <h3 className="text-base font-bold text-dark-900">{item.title}</h3>
                                            <p className="mt-2 text-sm text-dark-500 leading-relaxed">{item.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* ═══ STATS ═══ */}
                <section className="py-24 bg-dark-900 relative overflow-hidden">
                    <div className="absolute inset-0 bg-gradient-radial from-blue-500/20 via-transparent to-transparent" />
                    <div className="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-400/60 to-transparent" />
                    <div className="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-violet-400/60 to-transparent" />
                    {/* Floating glow orbs - brighter */}
                    <div className="absolute top-1/4 left-1/4 w-[300px] h-[300px] bg-blue-500/20 rounded-full blur-[100px]" />
                    <div className="absolute bottom-1/4 right-1/4 w-[250px] h-[250px] bg-violet-500/20 rounded-full blur-[80px]" />

                    <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
                            {[
                                { ref: stat1.ref, count: stat1.count, end: '1,500+', label: 'Vehiculos Atendidos' },
                                { ref: stat2.ref, count: stat2.count, end: '15+', label: 'Anos de Experiencia' },
                                { ref: stat3.ref, count: stat3.count, end: '8,000+', label: 'Diagnosticos Realizados' },
                                { ref: stat4.ref, count: stat4.count, end: '98%', label: 'Clientes Satisfechos' },
                            ].map((stat, i) => (
                                <div ref={stat.ref} key={i} className="text-center py-6">
                                    <div className="text-5xl sm:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300 glow-text">
                                        {stat.count.toLocaleString()}
                                        <span className="text-3xl">{stat.end.replace(/[\d,]+/, '')}</span>
                                    </div>
                                    <p className="mt-3 text-sm font-medium text-dark-300 uppercase tracking-wider">{stat.label}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ═══ TESTIMONIALS ═══ */}
                <section className="py-24 bg-dark-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-3xl mx-auto mb-16">
                            <span className="text-sm font-bold text-primary-600 uppercase tracking-widest">Testimonios</span>
                            <h2 className="mt-4 text-3xl sm:text-4xl font-extrabold text-dark-900">
                                Lo que Dicen <span className="gradient-text">Nuestros Clientes</span>
                            </h2>
                        </div>

                        <div className="grid md:grid-cols-3 gap-8">
                            {testimonials.map((t) => (
                                <div key={t.name} className="card-hover relative overflow-hidden bg-gradient-to-br from-white via-blue-50/30 to-violet-50/30">
                                    {/* Top gradient bar */}
                                    <div className="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-violet-500 to-cyan-500" />
                                    {/* Subtle gradient overlay */}
                                    <div className="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-violet-500/5 rounded-2xl" />
                                    <div className="relative">
                                        <div className="flex gap-1 mb-4 mt-2">
                                            {[...Array(t.rating)].map((_, i) => (
                                                <StarIcon key={i} className="h-5 w-5 text-yellow-400" fill="currentColor" />
                                            ))}
                                        </div>
                                        <p className="text-dark-600 text-sm leading-relaxed italic">"{t.text}"</p>
                                        <div className="mt-6 pt-4 border-t border-dark-100 flex items-center gap-3">
                                            <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-violet-500 text-sm font-bold text-white shadow-lg shadow-blue-500/20">
                                                {t.name.split(' ').map((n) => n[0]).join('')}
                                            </div>
                                            <div>
                                                <p className="text-sm font-bold text-dark-900">{t.name}</p>
                                                <p className="text-xs text-dark-500">{t.role} &middot; {t.vehicle}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ═══ CTA ═══ */}
                <section className="py-24 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 via-dark-800 to-violet-700 px-8 py-20 sm:px-16">
                            {/* Brighter orbs */}
                            <div className="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-400/25 rounded-full blur-[100px] animate-float" />
                            <div className="absolute bottom-0 left-0 w-[400px] h-[400px] bg-violet-400/20 rounded-full blur-[80px] animate-float" style={{ animationDelay: '2s' }} />
                            <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-cyan-400/10 rounded-full blur-[80px] animate-pulse-slow" />
                            <div className="absolute inset-0 opacity-[0.06]" style={{
                                backgroundImage: 'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.8) 1px, transparent 0)',
                                backgroundSize: '30px 30px'
                            }} />

                            <div className="relative z-10 text-center max-w-2xl mx-auto">
                                <h2 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white">
                                    Registra tu Vehiculo y Comienza
                                </h2>
                                <p className="mt-4 text-lg text-blue-100/80 max-w-xl mx-auto">
                                    Unete a miles de clientes que confian en nosotros. Check-in digital, notificaciones por email y seguimiento en tiempo real.
                                </p>
                                <div className="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link href="/register" className="btn-primary btn-lg bg-gradient-to-r from-blue-400 via-blue-500 to-violet-500 hover:from-blue-500 hover:via-blue-600 hover:to-violet-600 shadow-glow hover:shadow-glow-accent">
                                        Crear Cuenta Gratis
                                        <ArrowRightIcon className="h-5 w-5" />
                                    </Link>
                                    <Link href="/contacto" className="inline-flex items-center justify-center gap-2 bg-white/15 hover:bg-white/25 text-white font-semibold py-3.5 px-8 rounded-xl border border-white/30 backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-500/20">
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
