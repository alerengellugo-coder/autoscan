import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { WrenchScrewdriverIcon, EnvelopeIcon } from '@heroicons/react/24/outline';
import { PageProps } from '../../types';

export default function ForgotPassword({ status }: PageProps & { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/forgot-password');
    };

    return (
        <>
            <Head title="Recuperar Contraseña" />

            <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-dark-900 via-dark-800 to-primary-900 px-4">
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

                <div className="relative z-10 w-full max-w-md">
                    {/* Logo */}
                    <div className="flex items-center justify-center gap-3 mb-8">
                        <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-600 shadow-lg">
                            <WrenchScrewdriverIcon className="h-7 w-7 text-white" />
                        </div>
                        <span className="text-2xl font-bold text-white">
                            Auto<span className="text-primary-400">Scan</span>
                        </span>
                    </div>

                    <div className="card">
                        {/* Header */}
                        <div className="text-center mb-6">
                            <div className="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-primary-100 mb-4">
                                <EnvelopeIcon className="h-7 w-7 text-primary-600" />
                            </div>
                            <h2 className="text-xl font-bold text-gray-900">
                                ¿Olvidaste tu contraseña?
                            </h2>
                            <p className="mt-2 text-sm text-gray-600">
                                No te preocupes. Ingresa tu correo electrónico y
                                te enviaremos un enlace para restablecer tu
                                contraseña.
                            </p>
                        </div>

                        {/* Status message */}
                        {status && (
                            <div className="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                                {status}
                            </div>
                        )}

                        {/* Form */}
                        <form onSubmit={handleSubmit} className="space-y-4">
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
                                    autoFocus
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-600">
                                        {errors.email}
                                    </p>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full btn-primary py-3 text-base flex items-center justify-center gap-2 disabled:opacity-50"
                            >
                                {processing ? (
                                    <>
                                        <svg
                                            className="animate-spin h-5 w-5"
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
                                        Enviando...
                                    </>
                                ) : (
                                    'Enviar Enlace de Recuperación'
                                )}
                            </button>
                        </form>

                        <div className="mt-6 text-center">
                            <Link
                                href="/login"
                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                            >
                                ← Volver a iniciar sesión
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
