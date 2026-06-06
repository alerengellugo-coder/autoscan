import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { WrenchScrewdriverIcon } from '@heroicons/react/24/outline';
import { PageProps } from '../../types';

export default function Register() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        password: '',
        password_confirmation: '',
        terms: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <>
            <Head title="Crear Cuenta" />

            <div className="min-h-screen flex">
                {/* Left side - Form */}
                <div className="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8">
                    <div className="w-full max-w-md">
                        {/* Logo */}
                        <div className="flex items-center gap-3 mb-8">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-600 shadow-lg">
                                <WrenchScrewdriverIcon className="h-7 w-7 text-white" />
                            </div>
                            <div>
                                <h1 className="text-2xl font-bold text-dark-900">
                                    Auto<span className="text-primary-600">Scan</span>
                                </h1>
                                <p className="text-xs text-gray-500">
                                    Sistema de Gestión Automotriz
                                </p>
                            </div>
                        </div>

                        {/* Heading */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900">
                                Crea tu cuenta
                            </h2>
                            <p className="mt-2 text-sm text-gray-600">
                                Regístrate para comenzar a gestionar tus
                                vehículos y servicios
                            </p>
                        </div>

                        {/* Register form */}
                        <form onSubmit={handleSubmit} className="space-y-5">
                            {/* Name */}
                            <div>
                                <label
                                    htmlFor="name"
                                    className="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Nombre completo
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData('name', e.target.value)
                                    }
                                    className={`input-field px-4 py-2.5 ${
                                        errors.name
                                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                                            : ''
                                    }`}
                                    placeholder="Juan Pérez"
                                    autoFocus
                                />
                                {errors.name && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.name}
                                    </p>
                                )}
                            </div>

                            {/* Email */}
                            <div>
                                <label
                                    htmlFor="email"
                                    className="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Correo electrónico
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) =>
                                        setData('email', e.target.value)
                                    }
                                    className={`input-field px-4 py-2.5 ${
                                        errors.email
                                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                                            : ''
                                    }`}
                                    placeholder="tu@email.com"
                                    autoComplete="email"
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.email}
                                    </p>
                                )}
                            </div>

                            {/* Phone */}
                            <div>
                                <label
                                    htmlFor="phone"
                                    className="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Teléfono
                                </label>
                                <input
                                    id="phone"
                                    type="tel"
                                    value={data.phone}
                                    onChange={(e) =>
                                        setData('phone', e.target.value)
                                    }
                                    className={`input-field px-4 py-2.5 ${
                                        errors.phone
                                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                                            : ''
                                    }`}
                                    placeholder="+52 (555) 123-4567"
                                />
                                {errors.phone && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.phone}
                                    </p>
                                )}
                            </div>

                            {/* Password */}
                            <div>
                                <label
                                    htmlFor="password"
                                    className="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Contraseña
                                </label>
                                <div className="relative">
                                    <input
                                        id="password"
                                        type={
                                            showPassword
                                                ? 'text'
                                                : 'password'
                                        }
                                        value={data.password}
                                        onChange={(e) =>
                                            setData(
                                                'password',
                                                e.target.value
                                            )
                                        }
                                        className={`input-field px-4 py-2.5 pr-10 ${
                                            errors.password
                                                ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                                                : ''
                                        }`}
                                        placeholder="Mínimo 8 caracteres"
                                        autoComplete="new-password"
                                    />
                                    <button
                                        type="button"
                                        onClick={() =>
                                            setShowPassword(!showPassword)
                                        }
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm"
                                    >
                                        {showPassword
                                            ? 'Ocultar'
                                            : 'Mostrar'}
                                    </button>
                                </div>
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.password}
                                    </p>
                                )}
                            </div>

                            {/* Confirm Password */}
                            <div>
                                <label
                                    htmlFor="password_confirmation"
                                    className="block text-sm font-medium text-gray-700 mb-1.5"
                                >
                                    Confirmar contraseña
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) =>
                                        setData(
                                            'password_confirmation',
                                            e.target.value
                                        )
                                    }
                                    className={`input-field px-4 py-2.5 ${
                                        errors.password_confirmation
                                            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
                                            : ''
                                    }`}
                                    placeholder="Repite tu contraseña"
                                    autoComplete="new-password"
                                />
                                {errors.password_confirmation && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.password_confirmation}
                                    </p>
                                )}
                            </div>

                            {/* Terms */}
                            <div>
                                <label className="flex items-start gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="terms"
                                        checked={data.terms}
                                        onChange={(e) =>
                                            setData(
                                                'terms',
                                                e.target.checked
                                            )
                                        }
                                        className="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 mt-0.5"
                                    />
                                    <span className="text-sm text-gray-600">
                                        Acepto los{' '}
                                        <a
                                            href="#"
                                            className="text-primary-600 hover:text-primary-700 font-medium"
                                        >
                                            términos y condiciones
                                        </a>{' '}
                                        y la{' '}
                                        <a
                                            href="#"
                                            className="text-primary-600 hover:text-primary-700 font-medium"
                                        >
                                            política de privacidad
                                        </a>
                                    </span>
                                </label>
                                {errors.terms && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.terms}
                                    </p>
                                )}
                            </div>

                            {/* Submit */}
                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full btn-primary py-3 text-base flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? (
                                    <>
                                        <svg
                                            className="animate-spin h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                className="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                strokeWidth="4"
                                            />
                                            <path
                                                className="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            />
                                        </svg>
                                        Creando cuenta...
                                    </>
                                ) : (
                                    'Crear Cuenta'
                                )}
                            </button>
                        </form>

                        {/* Login link */}
                        <p className="mt-8 text-center text-sm text-gray-600">
                            ¿Ya tienes una cuenta?{' '}
                            <Link
                                href="/login"
                                className="font-semibold text-primary-600 hover:text-primary-700"
                            >
                                Inicia sesión
                            </Link>
                        </p>
                    </div>
                </div>

                {/* Right side - Decorative */}
                <div className="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-primary-700 via-primary-800 to-dark-900">
                    <div className="absolute inset-0 opacity-10">
                        <div
                            className="absolute inset-0"
                            style={{
                                backgroundImage:
                                    'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0)',
                                backgroundSize: '40px 40px',
                            }}
                        />
                    </div>

                    <div className="absolute top-32 left-16 w-56 h-56 rounded-full bg-secondary-500/20 blur-3xl" />
                    <div className="absolute bottom-32 right-16 w-48 h-48 rounded-full bg-primary-400/20 blur-3xl" />

                    <div className="relative z-10 flex flex-col items-center justify-center p-12 text-center">
                        <svg
                            className="w-48 h-48 text-white/60 mb-12"
                            viewBox="0 0 200 200"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <circle
                                cx="100"
                                cy="100"
                                r="90"
                                stroke="currentColor"
                                strokeWidth="1"
                                strokeDasharray="8 4"
                            />
                            <path
                                d="M60 120 L65 85 L85 70 L130 70 L140 85 L145 100 L155 105 L155 120 L60 120Z"
                                stroke="currentColor"
                                strokeWidth="2"
                                fill="currentColor"
                                fillOpacity="0.1"
                            />
                            <circle
                                cx="80"
                                cy="125"
                                r="12"
                                stroke="currentColor"
                                strokeWidth="2"
                            />
                            <circle
                                cx="135"
                                cy="125"
                                r="12"
                                stroke="currentColor"
                                strokeWidth="2"
                            />
                            {/* Shield check icon */}
                            <path
                                d="M100 35 L115 42 L115 58 C115 70 100 78 100 78 C100 78 85 70 85 58 L85 42 L100 35Z"
                                stroke="currentColor"
                                strokeWidth="2"
                                fill="currentColor"
                                fillOpacity="0.15"
                            />
                            <path
                                d="M93 55 L98 60 L108 50"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>

                        <h2 className="text-3xl font-bold text-white mb-4">
                            Gestión Total
                        </h2>
                        <p className="text-lg text-gray-300 max-w-md">
                            Registra tus vehículos, recibe diagnósticos
                            detallados, cotizaciones y sigue el progreso de cada
                            servicio en tiempo real.
                        </p>

                        <div className="mt-12 space-y-4 text-left">
                            {[
                                'Registro ilimitado de vehículos',
                                'Seguimiento de órdenes en tiempo real',
                                'Cotizaciones y reportes digitales',
                                'Historial completo de servicios',
                            ].map((feature) => (
                                <div
                                    key={feature}
                                    className="flex items-center gap-3"
                                >
                                    <div className="flex h-6 w-6 items-center justify-center rounded-full bg-primary-500/30">
                                        <svg
                                            className="h-3.5 w-3.5 text-primary-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2.5}
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                    </div>
                                    <span className="text-sm text-gray-200">
                                        {feature}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
