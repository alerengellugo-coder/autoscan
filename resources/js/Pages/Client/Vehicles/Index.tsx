import React from 'react';
import { usePage, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import EmptyState from '../../../Components/EmptyState';
import {
    TruckIcon,
    PlusIcon,
    EyeIcon,
    ClipboardDocumentListIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Vehicle } from '../../../types';

interface ClientVehiclesPageProps extends PageProps {
    vehicles: Vehicle[];
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

export default function ClientVehiclesIndex() {
    const { props } = usePage<ClientVehiclesPageProps>();
    const { vehicles } = props;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                            <TruckIcon className="h-6 w-6 text-primary-600" />
                        </div>
                        <div>
                            <h1 className="text-xl font-bold text-gray-900">Mis Vehículos</h1>
                            <p className="text-sm text-gray-500">Gestiona tu flota de vehículos</p>
                        </div>
                    </div>
                    <Link
                        href="/mi-cuenta/vehiculos/create"
                        className="btn-primary flex items-center gap-2"
                    >
                        <PlusIcon className="h-4 w-4" />
                        Registrar Vehículo
                    </Link>
                </div>
            }
        >
            <div className="space-y-6">
                {vehicles.length === 0 ? (
                    <EmptyState
                        title="No tienes vehículos registrados"
                        description="Registra tu primer vehículo para poder crear órdenes de servicio y hacer seguimiento al mantenimiento."
                        icon={TruckIcon}
                        action={{
                            label: 'Registrar Vehículo',
                            href: '/mi-cuenta/vehiculos/create',
                        }}
                    />
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {vehicles.map((vehicle) => (
                            <div
                                key={vehicle.id}
                                className="card hover:shadow-card-hover transition-shadow duration-200 flex flex-col"
                            >
                                {/* Card header with icon */}
                                <div className="flex items-start justify-between mb-4">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100">
                                            <TruckIcon className="h-6 w-6 text-primary-600" />
                                        </div>
                                        <div>
                                            <h3 className="text-lg font-bold text-gray-900">
                                                {vehicle.brand} {vehicle.model}
                                            </h3>
                                            <p className="text-sm text-gray-500">{vehicle.year}</p>
                                        </div>
                                    </div>
                                    <StatusBadge
                                        status={vehicle.status}
                                        label={vehicle.status_label || vehicleStatusLabel[vehicle.status]}
                                        size="md"
                                    />
                                </div>

                                {/* Plate (highlighted) */}
                                <div className="mb-4 p-3 bg-gray-50 rounded-xl">
                                    <p className="text-xs text-gray-500 mb-1">Placa</p>
                                    <p className="text-lg font-bold text-primary-600 tracking-wider">
                                        {vehicle.plate_formatted || vehicle.plate}
                                    </p>
                                </div>

                                {/* Vehicle details grid */}
                                <div className="grid grid-cols-2 gap-3 mb-4">
                                    {vehicle.color && (
                                        <div>
                                            <p className="text-xs text-gray-500">Color</p>
                                            <p className="text-sm font-medium text-gray-700">{vehicle.color}</p>
                                        </div>
                                    )}
                                    {vehicle.engine_type && (
                                        <div>
                                            <p className="text-xs text-gray-500">Motor</p>
                                            <p className="text-sm font-medium text-gray-700">
                                                {vehicle.engine_type_label || engineTypeLabel[vehicle.engine_type]}
                                            </p>
                                        </div>
                                    )}
                                    {vehicle.transmission && (
                                        <div>
                                            <p className="text-xs text-gray-500">Transmisión</p>
                                            <p className="text-sm font-medium text-gray-700">
                                                {vehicle.transmission_label || transmissionLabel[vehicle.transmission]}
                                            </p>
                                        </div>
                                    )}
                                    {vehicle.mileage != null && (
                                        <div>
                                            <p className="text-xs text-gray-500">Kilometraje</p>
                                            <p className="text-sm font-medium text-gray-700">
                                                {vehicle.mileage_formatted || `${Number(vehicle.mileage).toLocaleString()} km`}
                                            </p>
                                        </div>
                                    )}
                                </div>

                                {/* Active orders count */}
                                {vehicle.service_orders && vehicle.service_orders.length > 0 && (
                                    <div className="flex items-center gap-1.5 mb-4">
                                        <span className="inline-flex items-center gap-1 px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                            <ClipboardDocumentListIcon className="h-3 w-3" />
                                            {vehicle.service_orders.filter((o) => ['pending', 'diagnosing', 'in_progress', 'waiting_parts', 'quality_check'].includes(o.status)).length} orden(es) activa(s)
                                        </span>
                                    </div>
                                )}

                                {/* Spacer */}
                                <div className="flex-1" />

                                {/* Actions */}
                                <div className="flex items-center gap-2 pt-4 border-t border-gray-100">
                                    <Link
                                        href={`/mi-cuenta/vehiculos/${vehicle.id}`}
                                        className="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium hover:bg-primary-100 transition-colors"
                                    >
                                        <EyeIcon className="h-4 w-4" />
                                        Ver Detalles
                                    </Link>
                                    <Link
                                        href={`/mi-cuenta/vehiculos/${vehicle.id}/orders`}
                                        className="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
                                    >
                                        <ClipboardDocumentListIcon className="h-4 w-4" />
                                        Ver Órdenes
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
