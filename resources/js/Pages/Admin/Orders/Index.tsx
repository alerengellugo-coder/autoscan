import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import StatsCard from '../../../Components/StatsCard';
import {
    PlusCircleIcon,
    MagnifyingGlassIcon,
    FunnelIcon,
    EyeIcon,
    ClipboardDocumentListIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    CheckCircleIcon,
    TruckIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, PaginationData, SelectOption } from '../../../types';

interface OrdersIndexProps extends PageProps {
    orders: PaginationData<ServiceOrder>;
    status_options: SelectOption[];
    priority_options: SelectOption[];
    technician_options: SelectOption[];
    status_counts: Record<string, number>;
    filters: {
        status?: string;
        priority?: string;
        technician_id?: string;
        date_from?: string;
        date_to?: string;
    };
}

export default function OrdersIndex({
    orders,
    status_options,
    priority_options,
    technician_options,
    status_counts,
    filters,
}: OrdersIndexProps) {
    const [localFilters, setLocalFilters] = useState({
        status: filters.status || '',
        priority: filters.priority || '',
        technician_id: filters.technician_id || '',
        date_from: filters.date_from || '',
        date_to: filters.date_to || '',
    });

    const handleFilterChange = (key: string, value: string) => {
        setLocalFilters((prev) => ({ ...prev, [key]: value }));
    };

    const applyFilters = () => {
        router.get('/orders', localFilters, { preserveState: true });
    };

    const clearFilters = () => {
        setLocalFilters({ status: '', priority: '', technician_id: '', date_from: '', date_to: '' });
        router.get('/orders', {}, { preserveState: true });
    };

    return (
        <>
            <Head title="Órdenes de Servicio" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-900">
                            Órdenes de Servicio
                        </h1>
                        <Link
                            href="/orders/create"
                            className="inline-flex items-center gap-2 btn-primary"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Nueva Orden
                        </Link>
                    </div>
                }
            >
                {/* Stats by Status */}
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
                    <div className="stat-card !p-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-100">
                                <ClipboardDocumentListIcon className="h-4 w-4 text-primary-600" />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500">Total</p>
                                <p className="text-sm font-bold text-gray-900">{status_counts.total || 0}</p>
                            </div>
                        </div>
                    </div>
                    <div className="stat-card !p-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-yellow-100">
                                <ClockIcon className="h-4 w-4 text-yellow-600" />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500">Pendientes</p>
                                <p className="text-sm font-bold text-gray-900">{status_counts.pending || 0}</p>
                            </div>
                        </div>
                    </div>
                    <div className="stat-card !p-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100">
                                <TruckIcon className="h-4 w-4 text-blue-600" />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500">En Progreso</p>
                                <p className="text-sm font-bold text-gray-900">{status_counts.in_progress || 0}</p>
                            </div>
                        </div>
                    </div>
                    <div className="stat-card !p-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-100">
                                <ExclamationTriangleIcon className="h-4 w-4 text-purple-600" />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500">Calidad</p>
                                <p className="text-sm font-bold text-gray-900">{status_counts.quality_check || 0}</p>
                            </div>
                        </div>
                    </div>
                    <div className="stat-card !p-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-100">
                                <CheckCircleIcon className="h-4 w-4 text-green-600" />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500">Completadas</p>
                                <p className="text-sm font-bold text-gray-900">{status_counts.completed || 0}</p>
                            </div>
                        </div>
                    </div>
                    <div className="stat-card !p-3">
                        <div className="flex items-center gap-2">
                            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100">
                                <ExclamationTriangleIcon className="h-4 w-4 text-red-600" />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500">Canceladas</p>
                                <p className="text-sm font-bold text-gray-900">{status_counts.cancelled || 0}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Filter Bar */}
                <div className="card mb-6">
                    <div className="flex flex-col lg:flex-row items-start lg:items-end gap-4">
                        <div className="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <FunnelIcon className="h-4 w-4" />
                            Filtros:
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 flex-1">
                            <select
                                value={localFilters.status}
                                onChange={(e) => handleFilterChange('status', e.target.value)}
                                className="input-field px-3 py-2 text-sm"
                            >
                                <option value="">Todos los estados</option>
                                {status_options.map((opt) => (
                                    <option key={opt.value} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                            <select
                                value={localFilters.priority}
                                onChange={(e) => handleFilterChange('priority', e.target.value)}
                                className="input-field px-3 py-2 text-sm"
                            >
                                <option value="">Todas las prioridades</option>
                                {priority_options.map((opt) => (
                                    <option key={opt.value} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                            <select
                                value={localFilters.technician_id}
                                onChange={(e) => handleFilterChange('technician_id', e.target.value)}
                                className="input-field px-3 py-2 text-sm"
                            >
                                <option value="">Todos los técnicos</option>
                                {technician_options.map((opt) => (
                                    <option key={opt.value} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                            <input
                                type="date"
                                value={localFilters.date_from}
                                onChange={(e) => handleFilterChange('date_from', e.target.value)}
                                className="input-field px-3 py-2 text-sm"
                                placeholder="Desde"
                            />
                            <input
                                type="date"
                                value={localFilters.date_to}
                                onChange={(e) => handleFilterChange('date_to', e.target.value)}
                                className="input-field px-3 py-2 text-sm"
                                placeholder="Hasta"
                            />
                        </div>
                        <div className="flex items-center gap-2">
                            <button onClick={applyFilters} className="btn-primary py-2 text-sm">
                                Filtrar
                            </button>
                            <button onClick={clearFilters} className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3">Orden</th>
                                    <th className="table-header px-6 py-3">Vehículo</th>
                                    <th className="table-header px-6 py-3">Cliente</th>
                                    <th className="table-header px-6 py-3">Técnico</th>
                                    <th className="table-header px-6 py-3">Tipo</th>
                                    <th className="table-header px-6 py-3">Estado</th>
                                    <th className="table-header px-6 py-3">Prioridad</th>
                                    <th className="table-header px-6 py-3">Fecha</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {orders.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={9} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron órdenes de servicio.
                                        </td>
                                    </tr>
                                ) : (
                                    orders.data.map((order) => (
                                        <tr key={order.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {order.order_number}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {order.vehicle?.brand} {order.vehicle?.model}
                                                <span className="text-gray-400 ml-1">({order.vehicle?.plate})</span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {order.client?.name || '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {order.technician?.name || 'Sin asignar'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {order.service_type_label || order.service_type}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <StatusBadge status={order.status} label={order.status_label} />
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                                        order.priority === 'high'
                                                            ? 'bg-red-100 text-red-800'
                                                            : order.priority === 'medium'
                                                            ? 'bg-yellow-100 text-yellow-800'
                                                            : 'bg-gray-100 text-gray-800'
                                                    }`}
                                                >
                                                    {order.priority_label || order.priority}
                                                </span>
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
                                                    href={`/orders/${order.id}`}
                                                    className="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700"
                                                >
                                                    <EyeIcon className="h-4 w-4" />
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
                    {orders.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {orders.from} a {orders.to} de {orders.total} resultados
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={orders.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        orders.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {orders.current_page} de {orders.last_page}
                                </span>
                                <Link
                                    href={orders.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        orders.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
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
