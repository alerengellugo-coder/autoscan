import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../Components/StatusBadge';
import {
    TruckIcon,
    PlusCircleIcon,
    BellIcon,
    ClipboardDocumentListIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    ClockIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Vehicle, ServiceOrder, AppNotification } from '../../types';

interface ClientDashboardProps extends PageProps {
    vehicles: Vehicle[];
    active_orders: ServiceOrder[];
    notifications: AppNotification[];
}

const statusSteps = [
    { key: 'pending', label: 'Pendiente' },
    { key: 'diagnosing', label: 'Diagnóstico' },
    { key: 'in_progress', label: 'En Progreso' },
    { key: 'quality_check', label: 'Control Calidad' },
    { key: 'completed', label: 'Completada' },
    { key: 'delivered', label: 'Entregada' },
];

function getOrderProgressIndex(status: string): number {
    const index = statusSteps.findIndex((s) => s.key === status);
    return index >= 0 ? index : 0;
}

function timeAgo(dateString: string): string {
    const now = new Date();
    const date = new Date(dateString);
    const diffMs = now.getTime() - date.getTime();
    const diffMinutes = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMinutes / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMinutes < 1) return 'Ahora mismo';
    if (diffMinutes < 60) return `Hace ${diffMinutes} min`;
    if (diffHours < 24) return `Hace ${diffHours}h`;
    if (diffDays < 7) return `Hace ${diffDays}d`;
    return date.toLocaleDateString('es-MX', { day: '2-digit', month: 'short' });
}

function getNotificationIcon(type: string) {
    if (type.includes('status') || type.includes('Status')) return CheckCircleIcon;
    if (type.includes('quotation') || type.includes('Quotation')) return ExclamationTriangleIcon;
    if (type.includes('report') || type.includes('Report')) return InformationCircleIcon;
    return BellIcon;
}

function getNotificationColor(type: string) {
    if (type.includes('status') || type.includes('Status')) return 'bg-green-100 text-green-600';
    if (type.includes('quotation') || type.includes('Quotation')) return 'bg-yellow-100 text-yellow-600';
    if (type.includes('report') || type.includes('Report')) return 'bg-blue-100 text-blue-600';
    return 'bg-gray-100 text-gray-600';
}

export default function ClientDashboard({
    auth,
    vehicles,
    active_orders,
    notifications,
}: ClientDashboardProps) {
    const user = auth.user;

    return (
        <>
            <Head title="Mi Panel" />
            <AuthenticatedLayout
                header={
                    <h1 className="text-xl font-semibold text-gray-900">
                        Panel del Cliente
                    </h1>
                }
            >
                {/* Welcome */}
                <div className="card bg-gradient-to-r from-primary-600 to-primary-700 text-white mb-8">
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-xl font-bold">
                                ¡Bienvenido, {user?.name}!
                            </h2>
                            <p className="text-primary-100 mt-1">
                                Administra tus vehículos y sigue el estado de tus órdenes de servicio.
                            </p>
                        </div>
                        <Link
                            href="/mi-cuenta/vehiculos/crear"
                            className="hidden sm:inline-flex items-center gap-2 bg-white text-primary-700 font-semibold py-2.5 px-5 rounded-xl hover:bg-primary-50 transition-colors"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Registrar Vehículo
                        </Link>
                    </div>
                    <Link
                        href="/mi-cuenta/vehiculos/crear"
                        className="sm:hidden mt-4 inline-flex items-center gap-2 bg-white text-primary-700 font-semibold py-2.5 px-5 rounded-xl hover:bg-primary-50 transition-colors w-full justify-center"
                    >
                        <PlusCircleIcon className="h-5 w-5" />
                        Registrar Vehículo
                    </Link>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    {/* My Vehicles */}
                    <div className="lg:col-span-2">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-lg font-semibold text-gray-900">
                                Mis Vehículos
                            </h2>
                            <Link
                                href="/mi-cuenta/vehiculos"
                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                            >
                                Ver todos
                            </Link>
                        </div>
                        {vehicles.length === 0 ? (
                            <div className="card text-center py-12">
                                <TruckIcon className="h-12 w-12 text-gray-300 mx-auto mb-4" />
                                <p className="text-gray-500 font-medium">
                                    No tienes vehículos registrados.
                                </p>
                                <p className="text-sm text-gray-400 mt-1">
                                    Registra tu primer vehículo para comenzar a usar el servicio.
                                </p>
                                <Link
                                    href="/mi-cuenta/vehiculos/crear"
                                    className="btn-primary mt-4 inline-flex items-center gap-2"
                                >
                                    <PlusCircleIcon className="h-4 w-4" />
                                    Registrar Vehículo
                                </Link>
                            </div>
                        ) : (
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {vehicles.map((vehicle) => (
                                    <div
                                        key={vehicle.id}
                                        className="card hover:shadow-card-hover transition-shadow"
                                    >
                                        <div className="flex items-start justify-between mb-3">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                                                    <TruckIcon className="h-5 w-5 text-primary-600" />
                                                </div>
                                                <div>
                                                    <h3 className="text-sm font-semibold text-gray-900">
                                                        {vehicle.brand} {vehicle.model}
                                                    </h3>
                                                    <p className="text-xs text-gray-500">{vehicle.year}</p>
                                                </div>
                                            </div>
                                            <StatusBadge
                                                status={vehicle.status}
                                                label={vehicle.status_label}
                                            />
                                        </div>
                                        <div className="flex items-center gap-4 text-xs text-gray-500">
                                            <span>Placa: <span className="font-medium text-gray-700">{vehicle.plate}</span></span>
                                            {vehicle.color && <span>Color: <span className="font-medium text-gray-700">{vehicle.color}</span></span>}
                                        </div>
                                        <div className="mt-3 pt-3 border-t border-gray-100">
                                            <Link
                                                href={`/mi-cuenta/vehiculos/${vehicle.id}`}
                                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                                            >
                                                Ver detalles →
                                            </Link>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Notifications */}
                    <div>
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-lg font-semibold text-gray-900">
                                Notificaciones
                            </h2>
                            <Link
                                href="/notificaciones"
                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                            >
                                Ver todas
                            </Link>
                        </div>
                        <div className="card p-0 overflow-hidden">
                            <div className="divide-y divide-gray-100">
                                {notifications.length === 0 ? (
                                    <div className="p-6 text-center text-gray-500 text-sm">
                                        No tienes notificaciones.
                                    </div>
                                ) : (
                                    notifications.slice(0, 5).map((notification) => {
                                        const IconComponent = getNotificationIcon(notification.type);
                                        const colorClass = getNotificationColor(notification.type);
                                        return (
                                            <div
                                                key={notification.id}
                                                className={`p-4 hover:bg-gray-50 transition-colors ${
                                                    !notification.read_at ? 'bg-primary-50/50' : ''
                                                }`}
                                            >
                                                <div className="flex items-start gap-3">
                                                    <div className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full ${colorClass}`}>
                                                        <IconComponent className="h-4 w-4" />
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <p className="text-sm text-gray-900">
                                                            {notification.data.title || notification.data.message || notification.data.body || 'Notificación'}
                                                        </p>
                                                        <p className="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                                            <ClockIcon className="h-3 w-3" />
                                                            {timeAgo(notification.created_at)}
                                                        </p>
                                                    </div>
                                                    {!notification.read_at && (
                                                        <div className="h-2 w-2 rounded-full bg-primary-500 shrink-0 mt-2" />
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Active Orders */}
                <div>
                    <div className="flex items-center justify-between mb-4">
                        <h2 className="text-lg font-semibold text-gray-900">
                            Órdenes de Servicio Activas
                        </h2>
                        <Link
                            href="/mi-cuenta/ordenes"
                            className="text-sm font-medium text-primary-600 hover:text-primary-700"
                        >
                            Ver todas
                        </Link>
                    </div>
                    {active_orders.length === 0 ? (
                        <div className="card text-center py-12">
                            <ClipboardDocumentListIcon className="h-12 w-12 text-gray-300 mx-auto mb-4" />
                            <p className="text-gray-500 font-medium">
                                No tienes órdenes de servicio activas.
                            </p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {active_orders.map((order) => {
                                const currentIndex = getOrderProgressIndex(order.status);
                                const progressPercent = (currentIndex / (statusSteps.length - 1)) * 100;
                                return (
                                    <div key={order.id} className="card">
                                        <div className="flex items-start justify-between mb-4">
                                            <div>
                                                <div className="flex items-center gap-2 mb-1">
                                                    <h3 className="text-sm font-semibold text-gray-900">
                                                        {order.order_number}
                                                    </h3>
                                                    <StatusBadge
                                                        status={order.status}
                                                        label={order.status_label}
                                                    />
                                                </div>
                                                <p className="text-sm text-gray-500">
                                                    {order.vehicle?.brand} {order.vehicle?.model} ({order.vehicle?.plate})
                                                </p>
                                            </div>
                                            <Link
                                                href={`/mi-cuenta/ordenes/${order.id}`}
                                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                                            >
                                                Ver detalles
                                            </Link>
                                        </div>

                                        {/* Progress Bar */}
                                        <div>
                                            <div className="flex items-center justify-between mb-2">
                                                <div className="flex w-full justify-between">
                                                    {statusSteps.map((step, index) => (
                                                        <div key={step.key} className="flex flex-col items-center">
                                                            <div
                                                                className={`flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold border-2 transition-all ${
                                                                    index <= currentIndex
                                                                        ? 'bg-primary-600 border-primary-600 text-white'
                                                                        : 'bg-white border-gray-300 text-gray-400'
                                                                }`}
                                                            >
                                                                {index <= currentIndex ? '✓' : index + 1}
                                                            </div>
                                                            <span
                                                                className={`text-[10px] mt-1 ${
                                                                    index <= currentIndex
                                                                        ? 'text-primary-600 font-medium'
                                                                        : 'text-gray-400'
                                                                }`}
                                                            >
                                                                {step.label}
                                                            </span>
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                            {/* Progress bar connecting dots */}
                                            <div className="relative h-1 bg-gray-200 rounded-full -mt-5 mb-3 mx-3">
                                                <div
                                                    className="absolute top-0 left-0 h-full bg-primary-600 rounded-full transition-all duration-500"
                                                    style={{ width: `${progressPercent}%` }}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>
            </AuthenticatedLayout>
        </>
    );
}
