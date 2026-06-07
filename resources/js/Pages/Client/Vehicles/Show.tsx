import React, { useState } from 'react';
import { usePage, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import EmptyState from '../../../Components/EmptyState';
import {
    TruckIcon,
    PlusIcon,
    EyeIcon,
    ClipboardDocumentListIcon,
    ClockIcon,
    XMarkIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    WrenchScrewdriverIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Vehicle, ServiceOrder } from '../../../types';

interface ClientVehicleShowProps extends PageProps {
    vehicle: Vehicle;
    active_orders: ServiceOrder[];
    completed_orders: ServiceOrder[];
}

const vehicleStatusLabel: Record<string, string> = {
    active: 'Activo',
    in_service: 'En Servicio',
    sold: 'Vendido',
    inactive: 'Inactivo',
};

const engineTypeLabel: Record<string, string> = {
    gasoline: 'Gasolina',
    diesel: 'Diésel',
    electric: 'Eléctrico',
    hybrid: 'Híbrido',
};

const transmissionLabel: Record<string, string> = {
    automatic: 'Automática',
    manual: 'Manual',
    cvt: 'CVT',
};

const statusLabels: Record<string, string> = {
    pending: 'Pendiente',
    diagnosing: 'En diagnóstico',
    in_progress: 'En progreso',
    waiting_parts: 'Esperando repuestos',
    quality_check: 'Control de calidad',
    completed: 'Completado',
    delivered: 'Entregado',
    cancelled: 'Cancelado',
};

const formatDate = (dateStr: string): string => {
    return new Date(dateStr).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const formatDateTime = (dateStr: string): string => {
    return new Date(dateStr).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

export default function ClientVehicleShow() {
    const { props } = usePage<ClientVehicleShowProps>();
    const { vehicle, active_orders, completed_orders } = props;
    const [showHistory, setShowHistory] = useState(false);

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <Link
                        href="/mi-cuenta/vehiculos"
                        className="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <XMarkIcon className="h-4 w-4" />
                        Volver
                    </Link>
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <TruckIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">
                            {vehicle.brand} {vehicle.model} ({vehicle.year})
                        </h1>
                        <p className="text-sm text-gray-500">Detalles del vehículo</p>
                    </div>
                </div>
            }
        >
            <div className="space-y-6">
                {/* Vehicle info card */}
                <div className="card">
                    <div className="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div className="flex-1">
                            <div className="flex items-center gap-4 mb-6">
                                <div className="flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-100">
                                    <TruckIcon className="h-8 w-8 text-primary-600" />
                                </div>
                                <div>
                                    <h2 className="text-2xl font-bold text-gray-900">
                                        {vehicle.brand} {vehicle.model}
                                    </h2>
                                    <p className="text-lg font-medium text-gray-500">{vehicle.year}</p>
                                </div>
                                <StatusBadge
                                    status={vehicle.status}
                                    label={vehicle.status_label || vehicleStatusLabel[vehicle.status]}
                                    size="md"
                                />
                            </div>

                            {/* Plate */}
                            <div className="p-4 bg-gray-50 rounded-xl mb-6">
                                <p className="text-xs font-medium text-gray-500 mb-1">Placa</p>
                                <p className="text-2xl font-bold text-primary-600 tracking-widest">
                                    {vehicle.plate_formatted || vehicle.plate}
                                </p>
                            </div>

                            {/* Details grid */}
                            <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                {vehicle.color && (
                                    <div className="p-3 bg-gray-50 rounded-lg">
                                        <p className="text-xs text-gray-500 mb-1">Color</p>
                                        <p className="text-sm font-medium text-gray-700">{vehicle.color}</p>
                                    </div>
                                )}
                                {vehicle.engine_type && (
                                    <div className="p-3 bg-gray-50 rounded-lg">
                                        <p className="text-xs text-gray-500 mb-1">Tipo de Motor</p>
                                        <p className="text-sm font-medium text-gray-700">
                                            {vehicle.engine_type_label || engineTypeLabel[vehicle.engine_type]}
                                        </p>
                                    </div>
                                )}
                                {vehicle.transmission && (
                                    <div className="p-3 bg-gray-50 rounded-lg">
                                        <p className="text-xs text-gray-500 mb-1">Transmisión</p>
                                        <p className="text-sm font-medium text-gray-700">
                                            {vehicle.transmission_label || transmissionLabel[vehicle.transmission]}
                                        </p>
                                    </div>
                                )}
                                {vehicle.mileage != null && (
                                    <div className="p-3 bg-gray-50 rounded-lg">
                                        <p className="text-xs text-gray-500 mb-1">Kilometraje</p>
                                        <p className="text-sm font-medium text-gray-700">
                                            {vehicle.mileage_formatted || `${Number(vehicle.mileage).toLocaleString()} km`}
                                        </p>
                                    </div>
                                )}
                                {vehicle.vin && (
                                    <div className="p-3 bg-gray-50 rounded-lg col-span-2">
                                        <p className="text-xs text-gray-500 mb-1">VIN</p>
                                        <p className="text-sm font-medium text-gray-700 font-mono">{vehicle.vin}</p>
                                    </div>
                                )}
                            </div>

                            {vehicle.notes && (
                                <div className="mt-4">
                                    <p className="text-xs font-medium text-gray-500 mb-1">Notas</p>
                                    <p className="text-sm text-gray-700 whitespace-pre-line">{vehicle.notes}</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Create order button */}
                {vehicle.status === 'active' && (
                    <div className="flex justify-end">
                        <Link
                            href={`/mi-cuenta/ordenes/create?vehicle_id=${vehicle.id}`}
                            className="btn-primary flex items-center gap-2"
                        >
                            <PlusIcon className="h-4 w-4" />
                            Crear Orden de Servicio
                        </Link>
                    </div>
                )}

                {/* Active Service Orders */}
                <div className="card">
                    <div className="flex items-center gap-3 mb-4">
                        <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100">
                            <WrenchScrewdriverIcon className="h-4 w-4 text-blue-600" />
                        </div>
                        <h3 className="text-base font-semibold text-gray-900">
                            Órdenes de Servicio Activas
                        </h3>
                        <span className="text-sm text-gray-500">
                            ({active_orders.length})
                        </span>
                    </div>

                    {active_orders.length === 0 ? (
                        <div className="text-center py-6">
                            <ClipboardDocumentListIcon className="h-10 w-10 text-gray-300 mx-auto mb-2" />
                            <p className="text-sm text-gray-500">No hay órdenes activas para este vehículo.</p>
                        </div>
                    ) : (
                        <div className="space-y-3">
                            {active_orders.map((order) => (
                                <div
                                    key={order.id}
                                    className="border border-gray-200 rounded-xl p-4 hover:shadow-card-hover transition-shadow duration-200"
                                >
                                    <div className="flex items-start justify-between mb-3">
                                        <div>
                                            <Link
                                                href={`/mi-cuenta/ordenes/${order.id}`}
                                                className="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors"
                                            >
                                                {order.order_number}
                                            </Link>
                                            <div className="flex items-center gap-2 mt-1">
                                                <span className="text-xs text-gray-500">
                                                    {order.service_type_label}
                                                </span>
                                            </div>
                                        </div>
                                        <StatusBadge
                                            status={order.status}
                                            label={order.status_label || statusLabels[order.status]}
                                            size="md"
                                        />
                                    </div>

                                    {order.description && (
                                        <p className="text-sm text-gray-600 mb-3 line-clamp-2">
                                            {order.description}
                                        </p>
                                    )}

                                    <div className="flex items-center justify-between pt-3 border-t border-gray-100">
                                        <div className="flex items-center gap-1.5 text-xs text-gray-400">
                                            <ClockIcon className="h-3.5 w-3.5" />
                                            <span>Última actualización: {formatDateTime(order.updated_at)}</span>
                                        </div>
                                        <Link
                                            href={`/mi-cuenta/ordenes/${order.id}`}
                                            className="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1"
                                        >
                                            <EyeIcon className="h-3.5 w-3.5" />
                                            Ver completo
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* Service History */}
                <div className="card">
                    <button
                        type="button"
                        onClick={() => setShowHistory(!showHistory)}
                        className="w-full flex items-center justify-between"
                    >
                        <div className="flex items-center gap-3">
                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100">
                                <ClipboardDocumentListIcon className="h-4 w-4 text-green-600" />
                            </div>
                            <h3 className="text-base font-semibold text-gray-900">
                                Historial de Servicios
                            </h3>
                            <span className="text-sm text-gray-500">
                                ({completed_orders.length})
                            </span>
                        </div>
                        {showHistory ? (
                            <ChevronUpIcon className="h-5 w-5 text-gray-400" />
                        ) : (
                            <ChevronDownIcon className="h-5 w-5 text-gray-400" />
                        )}
                    </button>

                    {showHistory && (
                        <div className="mt-4 space-y-3">
                            {completed_orders.length === 0 ? (
                                <div className="text-center py-6">
                                    <ClipboardDocumentListIcon className="h-10 w-10 text-gray-300 mx-auto mb-2" />
                                    <p className="text-sm text-gray-500">No hay servicios completados registrados.</p>
                                </div>
                            ) : (
                                completed_orders.map((order) => (
                                    <div
                                        key={order.id}
                                        className="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors"
                                    >
                                        <div className="flex items-start justify-between">
                                            <div>
                                                <Link
                                                    href={`/mi-cuenta/ordenes/${order.id}`}
                                                    className="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors"
                                                >
                                                    {order.order_number}
                                                </Link>
                                                <p className="text-xs text-gray-500 mt-0.5">
                                                    {order.service_type_label} — Completado: {formatDate(order.completed_at || order.updated_at)}
                                                </p>
                                                {order.description && (
                                                    <p className="text-xs text-gray-600 mt-1 line-clamp-1">
                                                        {order.description}
                                                    </p>
                                                )}
                                            </div>
                                            <StatusBadge
                                                status={order.status}
                                                label="Completado"
                                                size="sm"
                                            />
                                        </div>
                                    </div>
                                ))
                            )}
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
