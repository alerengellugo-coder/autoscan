import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import {
    ArrowLeftIcon,
    PlusCircleIcon,
    TruckIcon,
    CpuChipIcon,
    IdentificationIcon,
    CalendarIcon,
    PaintBrushIcon,
    MapPinIcon,
    WrenchScrewdriverIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Vehicle, ServiceOrder, PaginationData } from '../../../types';

interface VehiclesShowProps extends PageProps {
    vehicle: Vehicle;
    service_orders: PaginationData<ServiceOrder>;
}

export default function VehiclesShow({ vehicle, service_orders }: VehiclesShowProps) {
    return (
        <>
            <Head title={`${vehicle.brand} ${vehicle.model} - ${vehicle.plate}`} />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/vehiculos"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Detalles del Vehículo
                        </h1>
                    </div>
                }
            >
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Vehicle Info Card */}
                    <div className="lg:col-span-1">
                        <div className="card">
                            {/* Header */}
                            <div className="flex items-center justify-between mb-6">
                                <div className="flex items-center gap-3">
                                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100">
                                        <TruckIcon className="h-6 w-6 text-primary-600" />
                                    </div>
                                    <div>
                                        <h2 className="text-lg font-bold text-gray-900">
                                            {vehicle.brand} {vehicle.model}
                                        </h2>
                                        <p className="text-sm text-gray-500">{vehicle.year}</p>
                                    </div>
                                </div>
                                <StatusBadge
                                    status={vehicle.status}
                                    label={vehicle.status_label}
                                    size="md"
                                />
                            </div>

                            {/* Details Grid */}
                            <div className="space-y-4">
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1">Placa</p>
                                        <p className="text-sm font-semibold text-gray-900">{vehicle.plate}</p>
                                    </div>
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1">Color</p>
                                        <p className="text-sm font-semibold text-gray-900">{vehicle.color || '—'}</p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <IdentificationIcon className="h-3 w-3" />
                                            VIN
                                        </p>
                                        <p className="text-xs font-semibold text-gray-900 break-all">{vehicle.vin || '—'}</p>
                                    </div>
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <MapPinIcon className="h-3 w-3" />
                                            Kilometraje
                                        </p>
                                        <p className="text-sm font-semibold text-gray-900">
                                            {vehicle.mileage ? `${vehicle.mileage.toLocaleString()} km` : '—'}
                                        </p>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-4">
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <CpuChipIcon className="h-3 w-3" />
                                            Motor
                                        </p>
                                        <p className="text-sm font-semibold text-gray-900">
                                            {vehicle.engine_type_label || vehicle.engine_type || '—'}
                                        </p>
                                    </div>
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1 flex items-center gap-1">
                                            <WrenchScrewdriverIcon className="h-3 w-3" />
                                            Transmisión
                                        </p>
                                        <p className="text-sm font-semibold text-gray-900">
                                            {vehicle.transmission_label || vehicle.transmission || '—'}
                                        </p>
                                    </div>
                                </div>

                                {/* Client */}
                                {vehicle.client && (
                                    <div className="p-3 rounded-lg bg-primary-50 border border-primary-100">
                                        <p className="text-xs text-primary-600 mb-1">Propietario</p>
                                        <p className="text-sm font-semibold text-primary-900">{vehicle.client.name}</p>
                                        <p className="text-xs text-primary-600">{vehicle.client.email}</p>
                                    </div>
                                )}

                                {/* Notes */}
                                {vehicle.notes && (
                                    <div className="p-3 rounded-lg bg-gray-50">
                                        <p className="text-xs text-gray-500 mb-1">Notas</p>
                                        <p className="text-sm text-gray-700">{vehicle.notes}</p>
                                    </div>
                                )}
                            </div>

                            {/* Actions */}
                            <div className="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
                                <Link
                                    href={`/admin/vehiculos/${vehicle.id}/edit`}
                                    className="flex-1 btn-primary py-2 text-center text-sm"
                                >
                                    Editar
                                </Link>
                                <Link
                                    href="/admin/ordenes/crear"
                                    className="flex-1 btn-secondary py-2 text-center text-sm inline-flex items-center justify-center gap-1"
                                >
                                    <PlusCircleIcon className="h-4 w-4" />
                                    Nueva Orden
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Service History */}
                    <div className="lg:col-span-2">
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h2 className="text-lg font-semibold text-gray-900">
                                    Historial de Servicios
                                </h2>
                                <Link
                                    href="/admin/ordenes/crear"
                                    className="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700"
                                >
                                    <PlusCircleIcon className="h-4 w-4" />
                                    Agregar Orden
                                </Link>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th className="table-header px-6 py-3">Orden</th>
                                            <th className="table-header px-6 py-3">Tipo de Servicio</th>
                                            <th className="table-header px-6 py-3">Técnico</th>
                                            <th className="table-header px-6 py-3">Estado</th>
                                            <th className="table-header px-6 py-3">Fecha</th>
                                            <th className="table-header px-6 py-3 text-right">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {service_orders.data.length === 0 ? (
                                            <tr>
                                                <td colSpan={6} className="px-6 py-12 text-center text-gray-500">
                                                    No hay registros de servicio para este vehículo.
                                                </td>
                                            </tr>
                                        ) : (
                                            service_orders.data.map((order) => (
                                                <tr key={order.id} className="hover:bg-gray-50 transition-colors">
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {order.order_number}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        {order.service_type_label || order.service_type}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                        {order.technician?.name || '—'}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <StatusBadge
                                                            status={order.status}
                                                            label={order.status_label}
                                                        />
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {new Date(order.created_at).toLocaleDateString('es-MX', {
                                                            day: '2-digit',
                                                            month: 'short',
                                                            year: 'numeric',
                                                        })}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-right">
                                                        <Link
                                                            href={`/admin/ordenes/${order.id}`}
                                                            className="text-sm font-medium text-primary-600 hover:text-primary-700"
                                                        >
                                                            Ver
                                                        </Link>
                                                    </td>
                                                </tr>
                                            ))
                                        )}
                                    </tbody>
                                </table>
                            </div>

                            {/* Pagination */}
                            {service_orders.last_page > 1 && (
                                <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                                    <div className="text-sm text-gray-500">
                                        Mostrando {service_orders.from} a {service_orders.to} de {service_orders.total} resultados
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Link
                                            href={service_orders.prev_page_url || '#'}
                                            className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                                service_orders.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                            }`}
                                            preserveState
                                        >
                                            Anterior
                                        </Link>
                                        <span className="text-sm text-gray-700">
                                            Página {service_orders.current_page} de {service_orders.last_page}
                                        </span>
                                        <Link
                                            href={service_orders.next_page_url || '#'}
                                            className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                                service_orders.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                            }`}
                                            preserveState
                                        >
                                            Siguiente
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
