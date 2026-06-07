import React, { useEffect, useRef, useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import {
    CpuChipIcon,
    WrenchScrewdriverIcon,
    BoltIcon,
    ShieldCheckIcon,
    DocumentTextIcon,
    ArrowRightIcon,
    StarIcon,
    CheckCircleIcon,
    EyeIcon,
    CubeIcon,
    TruckIcon,
    EnvelopeIcon,
    PhoneIcon,
    MapPinIcon,
    ChevronDownIcon,
} from '@heroicons/react/24/outline';

/* ─── Data ─── */

const services = [
    {
        title: 'Escaneo de Computadora',
        description: 'Diagnostico completo de la ECU y modulos electronicos. Lectura de codigos DTC, datos en vivo y pruebas avanzadas con equipos OEM.',
        icon: CpuChipIcon,
    },
    {
        title: 'Reparacion de Modulos',
        description: 'Reparacion de ECU, BCM, TCM, ABS y modulos electronicos. Flasheo de software, recalibracion y reprogramacion.',
        icon: BoltIcon,
    },
    {
        title: 'Diagnostico Electrico',
        description: 'Deteccion de cortocircuitos, fallas en arranque, carga, iluminacion, sensores y actuadores electronicos del vehiculo.',
        icon: EyeIcon,
    },
    {
        title: 'Sistemas Hibridos y Electricos',
        description: 'Baterias de alto voltaje, motores electricos, inversores y sistemas de gestion de energia para vehiculos verdes.',
        icon: ShieldCheckIcon,
    },
    {
        title: 'Electronica Automotriz',
        description: 'Instalacion de alarmas, GPS, estereos, camaras de retroceso, sensores de estacionamiento y accesorios premium.',
        icon: CubeIcon,
    },
    {
        title: 'Mantenimiento Preventivo',
        description: 'Programas de mantenimiento con diagnostico electronico periodico para prevenir fallas y maximizar el rendimiento.',
        icon: WrenchScrewdriverIcon,
    },
];

const steps = [
    { num: '1', title: 'Registro', desc: 'Registra tu vehiculo y describe el problema desde tu cuenta.', icon: TruckIcon },
    { num: '2', title: 'Diagnostico', desc: 'Escaneamos la computadora con equipos de ultima generacion.', icon: CpuChipIcon },
    { num: '3', title: 'Presupuesto', desc: 'Recibes un presupuesto detallado por email para tu aprobacion.', icon: DocumentTextIcon },
    { num: '4', title: 'Reparacion', desc: 'Reparamos y te notificamos cada avance en tiempo real.', icon: WrenchScrewdriverIcon },
    { num: '5', title: 'Entrega', desc: 'Verificacion final, check-out y vehiculo listo.', icon: CheckCircleIcon },
];

const testimonials = [
    { name: 'Carlos Mendoza', role: 'Empresario', text: 'Encontraron un problema en mi Camry que 3 talleres no pudieron detectar. Servicio excepcional y muy profesionales.', vehicle: 'Toyota Camry 2023' },
    { name: 'Maria Garcia', role: 'Profesora', text: 'Diagnosticaron mi Honda en 30 minutos. El presupuesto fue transparente y sin sorpresas. Muy recomendados.', vehicle: 'Honda CR-V 2022' },
    { name: 'Roberto Sanchez', role: 'Ingeniero', text: 'Llevo 3 anos como cliente. Las notificaciones por email me permiten seguir cada paso de la reparacion.', vehicle: 'Ford F-150 2024' },
];

const brands = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan', 'Volkswagen', 'Hyundai', 'Kia', 'Mazda', 'BMW'];

/* ─── Counter Hook ─── */

function useCountUp(end: number, duration = 2000) {
    const [count, setCount] = useState(0);
    const ref = useRef<HTMLDivElement>(null);
    const started = useRef(false);

    useEffect(() => {
        const el = ref.current;
        if (!el) return;
        const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting && !started.current) started.current = true; }, { threshold: 0.3 });
        obs.observe(el);
        return () => obs.disconnect();
    }, []);

    useEffect(() => {
        if (!started.current) return;
        let t0: number | null = null;
        const step = (ts: number) => {
            if (!t0) t0 = ts;
            const p = Math.min((ts - t0) / duration, 1);
            setCount(Math.floor(p * end));
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }, [end, duration]);

    return { count, ref };
}

/* ─── Page ─── */

export default function Home() {
    const s1 = useCountUp(1500);
    const s2 = useCountUp(15);
    const s3 = useCountUp(8000);
    const s4 = useCountUp(98);

    return (
        <>
            <Head title="AutoScan - Escaneo y Diagnostico Electronico Automotriz" />
            <PublicLayout>

                {/* ════════ HERO ════════ */}
                <section className="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900">
                    {/* Subtle animated gradient mesh */}
                    <div className="absolute inset-0">
                        <div className="absolute -top-40 -right-40 w-[700px] h-[700px] bg-blue-600/20 rounded-full blur-[120px] animate-pulse-slow" />
                        <div className="absolute -bottom-20 -left-40 w-[500px] h-[500px] bg-indigo-500/15 rounded-full blur-[100px] animate-pulse-slow" style={{ animationDelay: '2s' }} />
                        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-sky-400/10 rounded-full blur-[80px]" />
                    </div>

                    <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
                        <div className="grid lg:grid-cols-2 gap-16 items-center">
                            {/* Left: Text */}
                            <div>
                                <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 mb-8">
                                    <span className="relative flex h-2 w-2">
                                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75" />
                                        <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-400" />
                                    </span>
                                    <span className="text-sm font-medium text-blue-300">Especialistas en Electronica Automotriz</span>
                                </div>

                                <h1 className="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-[1.1] tracking-tight">
                                    Diagnostico electronico{' '}
                                    <span className="relative">
                                        <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-400">profesional</span>
                                        <span className="absolute -bottom-2 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" />
                                    </span>
                                    <br />
                                    para tu vehiculo
                                </h1>

                                <p className="mt-6 text-lg text-slate-300 max-w-xl leading-relaxed">
                                    Escaneo de computadoras, reparacion de modulos ECU/BCM/TCM, diagnostico electrico
                                    y mantenimiento preventivo. Tecnologia de punta con seguimiento digital en tiempo real.
                                </p>

                                <div className="mt-10 flex flex-col sm:flex-row gap-4">
                                    <Link
                                        href="/register"
                                        className="group inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-200 shadow-xl shadow-blue-600/25 hover:shadow-blue-500/30 hover:-translate-y-0.5"
                                    >
                                        Registrar mi Vehiculo
                                        <ArrowRightIcon className="h-5 w-5 group-hover:translate-x-1 transition-transform" />
                                    </Link>
                                    <Link
                                        href="/servicios"
                                        className="inline-flex items-center justify-center gap-2 text-white font-semibold py-4 px-8 rounded-2xl border border-white/15 hover:border-white/30 hover:bg-white/5 transition-all duration-200"
                                    >
                                        Ver Servicios
                                    </Link>
                                </div>

                                {/* Inline stats */}
                                <div className="mt-14 flex items-center gap-10">
                                    {[
                                        { val: '15+', label: 'Anos' },
                                        { val: '1,500+', label: 'Vehiculos' },
                                        { val: '98%', label: 'Satisfaccion' },
                                    ].map((s) => (
                                        <div key={s.label}>
                                            <div className="text-2xl font-black text-white">{s.val}</div>
                                            <div className="text-sm text-slate-400">{s.label}</div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* Right: Feature cards stack */}
                            <div className="hidden lg:flex flex-col gap-4">
                                {[
                                    { icon: CpuChipIcon, title: 'Escaneo OBD-II', desc: 'Lectura de codigos y datos en vivo', color: 'from-blue-600 to-blue-700' },
                                    { icon: BoltIcon, title: 'Reparacion ECU', desc: 'Flasheo y recalibracion', color: 'from-indigo-600 to-violet-700' },
                                    { icon: ShieldCheckIcon, title: 'Garantia', desc: 'Todos nuestros trabajos', color: 'from-cyan-600 to-teal-700' },
                                ].map((card, i) => (
                                    <div
                                        key={card.title}
                                        className={`group flex items-center gap-5 p-5 rounded-2xl bg-white/5 backdrop-blur-sm border border-white/10 hover:bg-white/10 hover:border-white/20 transition-all duration-300 hover:-translate-y-1 cursor-default ${i === 1 ? 'ml-8' : ''}`}
                                    >
                                        <div className={`flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br ${card.color} shadow-lg`}>
                                            <card.icon className="h-7 w-7 text-white" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold text-white">{card.title}</h3>
                                            <p className="text-sm text-slate-400">{card.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Bottom fade to brands section */}
                    <div className="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent" />
                </section>

                {/* ════════ TRUSTED BRANDS BAR ════════ */}
                <section className="py-12 bg-white border-b border-slate-100">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <p className="text-center text-sm font-semibold text-slate-500 uppercase tracking-widest mb-8">
                            Trabajamos con todas las marcas
                        </p>
                        <div className="flex flex-wrap items-center justify-center gap-x-12 gap-y-4">
                            {brands.map((b) => (
                                <span key={b} className="text-lg font-bold text-slate-400 hover:text-blue-600 transition-colors duration-200 cursor-default">{b}</span>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ════════ SERVICES ════════ */}
                <section className="py-24 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-2xl mx-auto mb-16">
                            <span className="text-sm font-bold text-blue-600 uppercase tracking-widest">Servicios</span>
                            <h2 className="mt-3 text-3xl sm:text-4xl font-black text-slate-900">
                                Lo que hacemos por ti
                            </h2>
                            <p className="mt-4 text-lg text-slate-500">
                                Soluciones especializadas en electronica automotriz con la mejor tecnologia del mercado.
                            </p>
                        </div>

                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {services.map((service) => (
                                <div
                                    key={service.title}
                                    className="group relative p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:bg-blue-600 hover:border-blue-600 transition-all duration-500 cursor-pointer overflow-hidden"
                                >
                                    {/* Hover gradient overlay */}
                                    <div className="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-500" />

                                    <div className="relative z-10">
                                        <div className="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-100 text-blue-600 group-hover:bg-white/20 group-hover:text-white transition-all duration-500 mb-6">
                                            <service.icon className="w-7 h-7" />
                                        </div>
                                        <h3 className="text-xl font-bold text-slate-900 group-hover:text-white transition-colors duration-500 mb-3">
                                            {service.title}
                                        </h3>
                                        <p className="text-slate-500 group-hover:text-blue-100 leading-relaxed transition-colors duration-500">
                                            {service.description}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ════════ HOW IT WORKS ════════ */}
                <section className="py-24 bg-slate-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-2xl mx-auto mb-16">
                            <span className="text-sm font-bold text-blue-600 uppercase tracking-widest">Proceso</span>
                            <h2 className="mt-3 text-3xl sm:text-4xl font-black text-slate-900">
                                Como funciona
                            </h2>
                            <p className="mt-4 text-lg text-slate-500">
                                Un proceso simple, transparente y 100% digital.
                            </p>
                        </div>

                        <div className="max-w-4xl mx-auto">
                            {steps.map((item, i) => (
                                <div key={item.num} className="flex gap-6 items-start">
                                    {/* Vertical line */}
                                    {i < steps.length - 1 && (
                                        <div className="flex flex-col items-center">
                                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white font-black text-lg shadow-lg shadow-blue-600/30">
                                                {item.num}
                                            </div>
                                            <div className="w-0.5 flex-1 bg-gradient-to-b from-blue-300 to-blue-100 my-2" />
                                        </div>
                                    )}
                                    {i === steps.length - 1 && (
                                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-black text-lg shadow-lg shadow-blue-600/30">
                                            {item.num}
                                        </div>
                                    )}

                                    {/* Content */}
                                    <div className={`pb-12 ${i === steps.length - 1 ? 'pb-0' : ''}`}>
                                        <h3 className="text-lg font-bold text-slate-900">{item.title}</h3>
                                        <p className="mt-1 text-slate-500">{item.desc}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ════════ STATS ════════ */}
                <section className="py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 relative overflow-hidden">
                    <div className="absolute inset-0">
                        <div className="absolute -top-20 -right-20 w-[400px] h-[400px] bg-white/5 rounded-full blur-[80px]" />
                        <div className="absolute -bottom-20 -left-20 w-[300px] h-[300px] bg-white/5 rounded-full blur-[60px]" />
                    </div>

                    <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
                            {[
                                { ref: s1.ref, count: s1.count, suffix: '+', label: 'Vehiculos Atendidos' },
                                { ref: s2.ref, count: s2.count, suffix: '+', label: 'Anos de Experiencia' },
                                { ref: s3.ref, count: s3.count, suffix: '+', label: 'Diagnosticos Realizados' },
                                { ref: s4.ref, count: s4.count, suffix: '%', label: 'Clientes Satisfechos' },
                            ].map((stat, i) => (
                                <div ref={stat.ref} key={i} className="text-center">
                                    <div className="text-4xl sm:text-5xl font-black text-white">
                                        {stat.count.toLocaleString()}{stat.suffix}
                                    </div>
                                    <p className="mt-2 text-sm font-medium text-blue-200 uppercase tracking-wider">{stat.label}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ════════ TESTIMONIALS ════════ */}
                <section className="py-24 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center max-w-2xl mx-auto mb-16">
                            <span className="text-sm font-bold text-blue-600 uppercase tracking-widest">Testimonios</span>
                            <h2 className="mt-3 text-3xl sm:text-4xl font-black text-slate-900">
                                Clientes felices
                            </h2>
                        </div>

                        <div className="grid md:grid-cols-3 gap-8">
                            {testimonials.map((t) => (
                                <div key={t.name} className="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-shadow duration-300">
                                    <div className="flex gap-1 mb-5">
                                        {[...Array(5)].map((_, i) => (
                                            <StarIcon key={i} className="h-5 w-5 text-yellow-400" fill="currentColor" />
                                        ))}
                                    </div>
                                    <p className="text-slate-600 leading-relaxed italic">"{t.text}"</p>
                                    <div className="mt-6 pt-6 border-t border-slate-200 flex items-center gap-3">
                                        <div className="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
                                            {t.name.split(' ').map((n) => n[0]).join('')}
                                        </div>
                                        <div>
                                            <p className="text-sm font-bold text-slate-900">{t.name}</p>
                                            <p className="text-xs text-slate-400">{t.role} &middot; {t.vehicle}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* ════════ CTA ════════ */}
                <section className="py-24 bg-slate-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 px-8 py-20 sm:px-16 text-center">
                            <div className="absolute inset-0">
                                <div className="absolute -top-10 -right-10 w-[300px] h-[300px] bg-white/10 rounded-full blur-[60px]" />
                                <div className="absolute -bottom-10 -left-10 w-[250px] h-[250px] bg-indigo-400/20 rounded-full blur-[50px]" />
                            </div>

                            <div className="relative z-10">
                                <h2 className="text-3xl sm:text-4xl lg:text-5xl font-black text-white">
                                    Lista tu vehiculo hoy
                                </h2>
                                <p className="mt-4 text-lg text-blue-100 max-w-xl mx-auto">
                                    Crea tu cuenta, registra tu vehiculo y recibe diagnostico electronico profesional con seguimiento completo.
                                </p>
                                <div className="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                                    <Link
                                        href="/register"
                                        className="group inline-flex items-center justify-center gap-2 bg-white text-blue-700 font-bold py-4 px-8 rounded-2xl hover:bg-blue-50 transition-all duration-200 shadow-xl hover:-translate-y-0.5"
                                    >
                                        Crear Cuenta Gratis
                                        <ArrowRightIcon className="h-5 w-5 group-hover:translate-x-1 transition-transform" />
                                    </Link>
                                    <Link
                                        href="/contacto"
                                        className="inline-flex items-center justify-center gap-2 text-white font-semibold py-4 px-8 rounded-2xl border-2 border-white/25 hover:border-white/50 hover:bg-white/10 transition-all duration-200"
                                    >
                                        <PhoneIcon className="h-5 w-5" />
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
