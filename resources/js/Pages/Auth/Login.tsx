import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { WrenchScrewdriverIcon } from '@heroicons/react/24/outline';
import { PageProps } from '../../types';

export default function Login({ status }: PageProps & { status?: string }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/login', {
            onFinish: () => reset('password'),
        });
    };

    return (
        <>
            <Head title="Iniciar Sesión" />

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

                        {/* Welcome back */}
                        <div className="mb-8">
                            <h2 className="text-2xl font-bold text-gray-900">
                                Bienvenido de vuelta
                            </h2>
                            <p className="mt-2 text-sm text-gray-600">
                                Ingresa tus credenciales para acceder a tu cuenta
                            </p>
                        </div>

                        {/* Status message */}
                        {status && (
                            <div className="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                                {status}
                            </div>
                        )}

                        {/* Login form */}
                        <form onSubmit={handleSubmit} className="space-y-5">
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
                                    autoFocus
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.email}
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
                                        placeholder="••••••••"
                                        autoComplete="current-password"
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

                            {/* Remember & Forgot */}
                            <div className="flex items-center justify-between">
                                <label className="flex items-center gap-2 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        checked={data.remember}
                                        onChange={(e) =>
                                            setData(
                                                'remember',
                                                e.target.checked
                                            )
                                        }
                                        className="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                    />
                                    <span className="text-sm text-gray-600">
                                        Recordarme
                                    </span>
                                </label>
                                <Link
                                    href="/forgot-password"
                                    className="text-sm font-medium text-primary-600 hover:text-primary-700"
                                >
                                    ¿Olvidaste tu contraseña?
                                </Link>
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
                                        Ingresando...
                                    </>
                                ) : (
                                    'Iniciar Sesión'
                                )}
                            </button>
                        </form>

                        {/* Register link */}
                        <p className="mt-8 text-center text-sm text-gray-600">
                            ¿No tienes una cuenta?{' '}
                            <Link
                                href="/register"
                                className="font-semibold text-primary-600 hover:text-primary-700"
                            >
                                Regístrate gratis
                            </Link>
                        </p>
                    </div>
                </div>

                {/* Right side - Decorative panel */}
                <div className="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-dark-900 via-dark-800 to-primary-900">
                    {/* Background pattern */}
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

                    {/* Decorative elements */}
                    <div className="absolute top-20 right-20 w-64 h-64 rounded-full bg-primary-500/20 blur-3xl" />
                    <div className="absolute bottom-20 left-20 w-48 h-48 rounded-full bg-secondary-500/20 blur-3xl" />

                    {/* Content */}
                    <div className="relative z-10 flex flex-col items-center justify-center p-12 text-center">
                        {/* Car SVG illustration */}
                        <div className="mb-12">
                            <svg
                                className="w-48 h-48 text-primary-400/80"
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
                                {/* Scan lines */}
                                <line
                                    x1="95"
                                    y1="30"
                                    x2="95"
                                    y2="65"
                                    stroke="currentColor"
                                    strokeWidth="1.5"
                                    strokeDasharray="4 3"
                                />
                                <line
                                    x1="100"
                                    y1="25"
                                    x2="100"
                                    y2="65"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                />
                                <line
                                    x1="105"
                                    y1="30"
                                    x2="105"
                                    y2="65"
                                    stroke="currentColor"
                                    strokeWidth="1.5"
                                    strokeDasharray="4 3"
                                />
                            </svg>
                        </div>

                        <h2 className="text-3xl font-bold text-white mb-4">
                            Diagnóstico Profesional
                        </h2>
                        <p className="text-lg text-gray-300 max-w-md">
                            Gestiona tus vehículos, órdenes de servicio y
                            diagnósticos desde una plataforma moderna y
                            profesional.
                        </p>

                        {/* Features */}
                        <div className="mt-12 grid grid-cols-3 gap-6">
                            <div className="text-center">
                                <div className="text-2xl font-bold text-primary-400">
                                    1000+
                                </div>
                                <div className="text-xs text-gray-400 mt-1">
                                    Vehículos atendidos
                                </div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-secondary-400">
                                    5000+
                                </div>
                                <div className="text-xs text-gray-400 mt-1">
                                    Diagnósticos realizados
                                </div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold text-green-400">
                                    98%
                                </div>
                                <div className="text-xs text-gray-400 mt-1">
                                    Clientes satisfechos
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
