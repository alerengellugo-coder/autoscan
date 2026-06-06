import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import {
    PlusCircleIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
    TruckIcon,
    WrenchScrewdriverIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Vehicle, PaginationData } from '../../../types';

interface VehiclesIndexProps extends PageProps {
    vehicles: PaginationData<Vehicle>;
    total_vehicles: number;
    in_service_count: number;
}

export default function VehiclesIndex({
    vehicles,
    total_vehicles,
    in_service_count,
}: VehiclesIndexProps) {
    const [search, setSearch] = useState('');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/vehicles', { search }, { preserveState: true });
    };

    return (
        <>
            <Head title="Gestión de Vehículos" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-900">
                            Gestión de Vehículos
                        </h1>
                        <Link
                            href="/vehicles/create"
                            className="inline-flex items-center gap-2 btn-primary"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Nuevo Vehículo
                        </Link>
                    </div>
                }
            >
                {/* Stats */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div className="stat-card">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-100">
                            <TruckIcon className="h-6 w-6 text-primary-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500">Total Vehículos</p>
                            <p className="text-2xl font-bold text-gray-900">{total_vehicles}</p>
                        </div>
                    </div>
                    <div className="stat-card">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                            <WrenchScrewdriverIcon className="h-6 w-6 text-blue-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500">En Servicio</p>
                            <p className="text-2xl font-bold text-gray-900">{in_service_count}</p>
                        </div>
                    </div>
                </div>

                {/* Search */}
                <div className="card mb-6">
                    <form onSubmit={handleSearch} className="flex items-center gap-4">
                        <div className="relative flex-1">
                            <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Buscar por placa, marca, modelo o cliente..."
                                className="input-field pl-10 py-2.5"
                            />
                        </div>
                        <button type="submit" className="btn-primary">
                            Buscar
                        </button>
                    </form>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3">Placa</th>
                                    <th className="table-header px-6 py-3">Marca</th>
                                    <th className="table-header px-6 py-3">Modelo</th>
                                    <th className="table-header px-6 py-3">Año</th>
                                    <th className="table-header px-6 py-3">Cliente</th>
                                    <th className="table-header px-6 py-3">Estado</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {vehicles.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron vehículos.
                                        </td>
                                    </tr>
                                ) : (
                                    vehicles.data.map((vehicle) => (
                                        <tr key={vehicle.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {vehicle.plate}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {vehicle.brand}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {vehicle.model}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {vehicle.year}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {vehicle.client?.name || '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <StatusBadge
                                                    status={vehicle.status}
                                                    label={vehicle.status_label}
                                                />
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right">
                                                <div className="flex items-center justify-end gap-2">
                                                    <Link
                                                        href={`/vehicles/${vehicle.id}`}
                                                        className="text-primary-600 hover:text-primary-700 p-1 rounded-lg hover:bg-primary-50 transition-colors"
                                                        title="Ver"
                                                    >
                                                        <EyeIcon className="h-4 w-4" />
                                                    </Link>
                                                    <Link
                                                        href={`/vehicles/${vehicle.id}/edit`}
                                                        className="text-yellow-600 hover:text-yellow-700 p-1 rounded-lg hover:bg-yellow-50 transition-colors"
                                                        title="Editar"
                                                    >
                                                        <PencilSquareIcon className="h-4 w-4" />
                                                    </Link>
                                                    <button
                                                        onClick={() => {
                                                            if (confirm('¿Estás seguro de eliminar este vehículo?')) {
                                                                router.delete(`/vehicles/${vehicle.id}`);
                                                            }
                                                        }}
                                                        className="text-red-600 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition-colors"
                                                        title="Eliminar"
                                                    >
                                                        <TrashIcon className="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {vehicles.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {vehicles.from} a {vehicles.to} de {vehicles.total} resultados
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={vehicles.prev_page_url || '#'}
                                    className={`inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        vehicles.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {vehicles.current_page} de {vehicles.last_page}
                                </span>
                                <Link
                                    href={vehicles.next_page_url || '#'}
                                    className={`inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        vehicles.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Siguiente
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </AuthenticatedLayout>
        </>
    );
}
