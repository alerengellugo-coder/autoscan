import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import {
    MapPinIcon,
    PhoneIcon,
    EnvelopeIcon,
    ClockIcon,
    PaperAirplaneIcon,
    TruckIcon,
    CheckCircleIcon,
} from '@heroicons/react/24/outline';

export default function Contact() {
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
    });

    const [submitted, setSubmitted] = useState(false);
    const [sending, setSending] = useState(false);

    const subjects = [
        { value: '', label: 'Seleccionar tema...' },
        { value: 'diagnostico', label: 'Solicitud de Diagnóstico' },
        { value: 'cotizacion', label: 'Solicitar Cotización' },
        { value: 'servicio', label: 'Información de Servicios' },
        { value: 'reclamo', label: 'Reclamo o Queja' },
        { value: 'otro', label: 'Otro' },
    ];

    const handleChange = (
        e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>
    ) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setSending(true);
        // Simulate form submission
        setTimeout(() => {
            setSending(false);
            setSubmitted(true);
            setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
        }, 1000);
    };

    return (
        <>
            <Head title="Contáctanos - AutoScan" />
            <PublicLayout>
                {/* Hero */}
                <section className="relative bg-gradient-to-br from-primary-600 via-primary-700 to-dark-800 py-16">
                    <div className="absolute inset-0 opacity-10">
                        <div
                            className="absolute inset-0"
                            style={{
                                backgroundImage:
                                    'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0)',
                                backgroundSize: '32px 32px',
                            }}
                        />
                    </div>
                    <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">
                            Contáctanos
                        </h1>
                        <p className="text-lg text-primary-100 max-w-2xl mx-auto">
                            ¿Tienes preguntas sobre nuestros servicios? Estamos aquí para ayudarte.
                            Envíanos un mensaje y te responderemos lo antes posible.
                        </p>
                    </div>
                </section>

                {/* Contact Section */}
                <section className="py-16 lg:py-24 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            {/* Left - Contact Form */}
                            <div>
                                <div className="card">
                                    <h2 className="text-2xl font-bold text-gray-900 mb-6">
                                        Envíanos un Mensaje
                                    </h2>

                                    {submitted ? (
                                        <div className="text-center py-8">
                                            <div className="flex justify-center mb-4">
                                                <div className="flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                                                    <CheckCircleIcon className="h-8 w-8 text-green-600" />
                                                </div>
                                            </div>
                                            <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                                ¡Mensaje Enviado!
                                            </h3>
                                            <p className="text-sm text-gray-500 mb-6">
                                                Hemos recibido tu mensaje. Te contactaremos a la brevedad posible.
                                            </p>
                                            <button
                                                type="button"
                                                onClick={() => setSubmitted(false)}
                                                className="btn-primary"
                                            >
                                                Enviar otro mensaje
                                            </button>
                                        </div>
                                    ) : (
                                        <form onSubmit={handleSubmit} className="space-y-5">
                                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                        Nombre Completo *
                                                    </label>
                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value={formData.name}
                                                        onChange={handleChange}
                                                        required
                                                        className="input-field px-4 py-2.5"
                                                        placeholder="Tu nombre"
                                                    />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                        Correo Electrónico *
                                                    </label>
                                                    <input
                                                        type="email"
                                                        name="email"
                                                        value={formData.email}
                                                        onChange={handleChange}
                                                        required
                                                        className="input-field px-4 py-2.5"
                                                        placeholder="tu@email.com"
                                                    />
                                                </div>
                                            </div>

                                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                        Teléfono
                                                    </label>
                                                    <input
                                                        type="tel"
                                                        name="phone"
                                                        value={formData.phone}
                                                        onChange={handleChange}
                                                        className="input-field px-4 py-2.5"
                                                        placeholder="+52 (555) 123-4567"
                                                    />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                        Tema *
                                                    </label>
                                                    <select
                                                        name="subject"
                                                        value={formData.subject}
                                                        onChange={handleChange}
                                                        required
                                                        className="input-field px-4 py-2.5"
                                                    >
                                                        {subjects.map((s) => (
                                                            <option key={s.value} value={s.value}>
                                                                {s.label}
                                                            </option>
                                                        ))}
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                    Mensaje *
                                                </label>
                                                <textarea
                                                    name="message"
                                                    value={formData.message}
                                                    onChange={handleChange}
                                                    required
                                                    rows={5}
                                                    className="input-field px-4 py-2.5"
                                                    placeholder="Cuéntanos en qué podemos ayudarte..."
                                                />
                                            </div>

                                            <button
                                                type="submit"
                                                disabled={sending}
                                                className="w-full btn-primary py-3 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                <PaperAirplaneIcon className="h-5 w-5" />
                                                {sending ? 'Enviando...' : 'Enviar Mensaje'}
                                            </button>
                                        </form>
                                    )}
                                </div>
                            </div>

                            {/* Right - Workshop Info */}
                            <div className="space-y-6">
                                {/* Info Cards */}
                                <div className="card">
                                    <h2 className="text-2xl font-bold text-gray-900 mb-6">
                                        Información del Taller
                                    </h2>
                                    <div className="space-y-6">
                                        <div className="flex items-start gap-4">
                                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-100">
                                                <MapPinIcon className="h-6 w-6 text-primary-600" />
                                            </div>
                                            <div>
                                                <h3 className="text-sm font-semibold text-gray-900">
                                                    Dirección
                                                </h3>
                                                <p className="text-sm text-gray-600 mt-1">
                                                    Av. Principal #123, Col. Centro,
                                                    <br />
                                                    Ciudad de México, CP 06000
                                                </p>
                                            </div>
                                        </div>

                                        <div className="flex items-start gap-4">
                                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
                                                <PhoneIcon className="h-6 w-6 text-green-600" />
                                            </div>
                                            <div>
                                                <h3 className="text-sm font-semibold text-gray-900">
                                                    Teléfono
                                                </h3>
                                                <p className="text-sm text-gray-600 mt-1">
                                                    +52 (555) 123-4567
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    +52 (555) 765-4321
                                                </p>
                                            </div>
                                        </div>

                                        <div className="flex items-start gap-4">
                                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                                                <EnvelopeIcon className="h-6 w-6 text-blue-600" />
                                            </div>
                                            <div>
                                                <h3 className="text-sm font-semibold text-gray-900">
                                                    Correo Electrónico
                                                </h3>
                                                <p className="text-sm text-gray-600 mt-1">
                                                    info@autoscan.com
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    soporte@autoscan.com
                                                </p>
                                            </div>
                                        </div>

                                        <div className="flex items-start gap-4">
                                            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-orange-100">
                                                <ClockIcon className="h-6 w-6 text-orange-600" />
                                            </div>
                                            <div>
                                                <h3 className="text-sm font-semibold text-gray-900">
                                                    Horario de Atención
                                                </h3>
                                                <div className="text-sm text-gray-600 mt-1 space-y-1">
                                                    <p>
                                                        <span className="font-medium">Lunes a Viernes:</span> 8:00 AM - 6:00 PM
                                                    </p>
                                                    <p>
                                                        <span className="font-medium">Sábados:</span> 8:00 AM - 2:00 PM
                                                    </p>
                                                    <p>
                                                        <span className="font-medium">Domingos:</span> Cerrado
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Map Placeholder */}
                                <div className="card p-0 overflow-hidden">
                                    <div className="bg-gray-200 rounded-t-xl h-64 flex flex-col items-center justify-center">
                                        <TruckIcon className="h-12 w-12 text-gray-400 mb-3" />
                                        <p className="text-sm font-medium text-gray-500">
                                            Mapa de Ubicación
                                        </p>
                                        <p className="text-xs text-gray-400 mt-1">
                                            Av. Principal #123, Col. Centro
                                        </p>
                                    </div>
                                    <div className="p-4 text-center">
                                        <a
                                            href="https://maps.google.com/?q=Av+Principal+123+Ciudad+de+Mexico"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-sm font-medium text-primary-600 hover:text-primary-700"
                                        >
                                            Abrir en Google Maps →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="py-16 lg:py-20 bg-white">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <div className="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-600 to-secondary-500 px-8 py-12">
                            <div className="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl" />
                            <div className="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl" />
                            <div className="relative z-10">
                                <h2 className="text-2xl sm:text-3xl font-bold text-white mb-3">
                                    ¿Necesitas un diagnóstico?
                                </h2>
                                <p className="text-primary-100 mb-6 max-w-xl mx-auto">
                                    Regístrate ahora y obtén un diagnóstico electrónico profesional para tu vehículo con tecnología de última generación.
                                </p>
                                <Link
                                    href="/register"
                                    className="inline-flex items-center justify-center gap-2 bg-white text-primary-700 font-semibold py-3 px-8 rounded-xl shadow-lg hover:bg-gray-50 transition-all duration-300 hover:-translate-y-0.5"
                                >
                                    Regístrate Ahora
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>
            </PublicLayout>
        </>
    );
}
