import React, { useState } from 'react';
import { Head, usePage, useForm, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import {
    UserCircleIcon,
    EnvelopeIcon,
    PhoneIcon,
    LockClosedIcon,
    ChevronDownIcon,
    ChevronUpIcon,
} from '@heroicons/react/24/outline';

interface ProfileEditProps {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
            phone: string | null;
            role: string;
            role_label?: string;
            initials?: string;
            avatar: string | null;
            created_at: string;
        };
    };
    flash: {
        success?: string;
        error?: string;
    };
}

export default function ProfileEdit() {
    const { auth, flash } = usePage().props as unknown as ProfileEditProps;
    const user = auth.user;
    const [showPasswordSection, setShowPasswordSection] = useState(false);

    const { data, setData, put, post, processing, errors, reset, recentlySuccessful } = useForm({
        name: user.name,
        email: user.email,
        phone: user.phone || '',
    });

    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handleProfileSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put('/profile', {
            onSuccess: () => {},
        });
    };

    const handlePasswordSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        passwordForm.post('/profile/password', {
            onSuccess: () => {
                passwordForm.reset();
                setShowPasswordSection(false);
            },
        });
    };

    const inputClass = (field: string, formErrors: Record<string, string>) =>
        `input-field px-4 py-2.5 ${formErrors[field] ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`;

    const getInitials = (name: string): string => {
        return name
            .split(' ')
            .map((part) => part.charAt(0))
            .join('')
            .toUpperCase()
            .slice(0, 2);
    };

    return (
        <>
            <Head title="Mi Perfil" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <UserCircleIcon className="h-6 w-6 text-gray-500" />
                        <h1 className="text-xl font-semibold text-gray-900">
                            Mi Perfil
                        </h1>
                    </div>
                }
            >
                {/* Flash Messages */}
                {(flash.success || flash.error) && (
                    <div className="mb-6">
                        {flash.success && (
                            <div className="flex items-center gap-2 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                                <svg className="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                {flash.success}
                            </div>
                        )}
                        {flash.error && (
                            <div className="flex items-center gap-2 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                                <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                </svg>
                                {flash.error}
                            </div>
                        )}
                    </div>
                )}

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left - Profile Card */}
                    <div className="lg:col-span-1">
                        <div className="card text-center">
                            {/* Avatar */}
                            <div className="flex justify-center mb-4">
                                {user.avatar ? (
                                    <img
                                        src={user.avatar}
                                        alt={user.name}
                                        className="h-24 w-24 rounded-full object-cover border-4 border-primary-100"
                                    />
                                ) : (
                                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 text-3xl font-bold text-white">
                                        {user.initials || getInitials(user.name)}
                                    </div>
                                )}
                            </div>

                            <h2 className="text-lg font-bold text-gray-900">{user.name}</h2>
                            <p className="text-sm text-gray-500">{user.email}</p>
                            <span className="inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {user.role_label || user.role}
                            </span>

                            {/* Quick info */}
                            <div className="mt-6 pt-6 border-t border-gray-200 space-y-3 text-left">
                                <div className="flex items-center gap-3">
                                    <EnvelopeIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Correo</p>
                                        <p className="text-sm text-gray-900">{user.email}</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <PhoneIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Teléfono</p>
                                        <p className="text-sm text-gray-900">{user.phone || 'No registrado'}</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-3">
                                    <UserCircleIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Miembro desde</p>
                                        <p className="text-sm text-gray-900">
                                            {new Date(user.created_at).toLocaleDateString('es-MX', {
                                                month: 'long',
                                                year: 'numeric',
                                            })}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Right - Edit Forms */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Personal Information */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-6">
                                Información Personal
                            </h3>
                            <form onSubmit={handleProfileSubmit} className="space-y-5">
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nombre Completo
                                        </label>
                                        <div className="relative">
                                            <UserCircleIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                                            <input
                                                type="text"
                                                value={data.name}
                                                onChange={(e) => setData('name', e.target.value)}
                                                className={`input-field pl-10 pr-4 py-2.5 w-full ${inputClass('name', errors as Record<string, string>)}`}
                                                placeholder="Tu nombre completo"
                                            />
                                        </div>
                                        {errors.name && (
                                            <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                                        )}
                                    </div>

                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                            Correo Electrónico
                                        </label>
                                        <div className="relative">
                                            <EnvelopeIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                                            <input
                                                type="email"
                                                value={data.email}
                                                onChange={(e) => setData('email', e.target.value)}
                                                className={`input-field pl-10 pr-4 py-2.5 w-full ${inputClass('email', errors as Record<string, string>)}`}
                                                placeholder="tu@email.com"
                                            />
                                        </div>
                                        {errors.email && (
                                            <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                        )}
                                    </div>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                        Teléfono
                                    </label>
                                    <div className="relative sm:max-w-md">
                                        <PhoneIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                                        <input
                                            type="tel"
                                            value={data.phone}
                                            onChange={(e) => setData('phone', e.target.value)}
                                            className={`input-field pl-10 pr-4 py-2.5 w-full ${inputClass('phone', errors as Record<string, string>)}`}
                                            placeholder="+52 (555) 123-4567"
                                        />
                                    </div>
                                    {errors.phone && (
                                        <p className="mt-1 text-sm text-red-600">{errors.phone}</p>
                                    )}
                                </div>

                                <div className="pt-2">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="btn-primary py-2.5 px-6 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {processing ? 'Guardando...' : 'Guardar Cambios'}
                                    </button>
                                </div>
                            </form>
                        </div>

                        {/* Change Password */}
                        <div className="card">
                            <button
                                type="button"
                                onClick={() => setShowPasswordSection(!showPasswordSection)}
                                className="w-full flex items-center justify-between"
                            >
                                <div className="flex items-center gap-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100">
                                        <LockClosedIcon className="h-5 w-5 text-yellow-600" />
                                    </div>
                                    <div className="text-left">
                                        <h3 className="text-lg font-semibold text-gray-900">
                                            Cambiar Contraseña
                                        </h3>
                                        <p className="text-sm text-gray-500">
                                            Actualiza tu contraseña de acceso
                                        </p>
                                    </div>
                                </div>
                                {showPasswordSection ? (
                                    <ChevronUpIcon className="h-5 w-5 text-gray-400" />
                                ) : (
                                    <ChevronDownIcon className="h-5 w-5 text-gray-400" />
                                )}
                            </button>

                            {showPasswordSection && (
                                <div className="mt-6 pt-6 border-t border-gray-200">
                                    <form onSubmit={handlePasswordSubmit} className="space-y-5">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                Contraseña Actual
                                            </label>
                                            <div className="relative sm:max-w-md">
                                                <LockClosedIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                                                <input
                                                    type="password"
                                                    value={passwordForm.data.current_password}
                                                    onChange={(e) => passwordForm.setData('current_password', e.target.value)}
                                                    className={`input-field pl-10 pr-4 py-2.5 w-full ${passwordForm.errors.current_password ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                                    required
                                                />
                                            </div>
                                            {passwordForm.errors.current_password && (
                                                <p className="mt-1 text-sm text-red-600">{passwordForm.errors.current_password}</p>
                                            )}
                                        </div>

                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                    Nueva Contraseña
                                                </label>
                                                <div className="relative">
                                                    <LockClosedIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                                                    <input
                                                        type="password"
                                                        value={passwordForm.data.password}
                                                        onChange={(e) => passwordForm.setData('password', e.target.value)}
                                                        className={`input-field pl-10 pr-4 py-2.5 w-full ${passwordForm.errors.password ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                                        required
                                                    />
                                                </div>
                                                {passwordForm.errors.password && (
                                                    <p className="mt-1 text-sm text-red-600">{passwordForm.errors.password}</p>
                                                )}
                                            </div>

                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                    Confirmar Nueva Contraseña
                                                </label>
                                                <div className="relative">
                                                    <LockClosedIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                                                    <input
                                                        type="password"
                                                        value={passwordForm.data.password_confirmation}
                                                        onChange={(e) => passwordForm.setData('password_confirmation', e.target.value)}
                                                        className="input-field pl-10 pr-4 py-2.5 w-full"
                                                        required
                                                    />
                                                </div>
                                                {passwordForm.errors.password_confirmation && (
                                                    <p className="mt-1 text-sm text-red-600">{passwordForm.errors.password_confirmation}</p>
                                                )}
                                            </div>
                                        </div>

                                        <div className="pt-2">
                                            <button
                                                type="submit"
                                                disabled={passwordForm.processing}
                                                className="btn-primary py-2.5 px-6 disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                {passwordForm.processing ? 'Actualizando...' : 'Actualizar Contraseña'}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
