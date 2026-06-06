import React, { useState, useCallback } from 'react';
import { usePage, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import EmptyState from '../../../Components/EmptyState';
import {
    ClipboardDocumentListIcon,
    FunnelIcon,
    EyeIcon,
    ClockIcon,
    TruckIcon,
    UserIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, PaginationData } from '../../../types';

interface ClientOrdersPageProps extends PageProps {
    orders: PaginationData<ServiceOrder>;
    filters?: {
        status?: string;
    };
    statuses?: { value: string; label: string }[];
}

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

const serviceTypeBadgeClasses: Record<string, string> = {
    diagnostic: 'bg-purple-100 text-purple-700',
    repair: 'bg-orange-100 text-orange-700',
    maintenance: 'bg-teal-100 text-teal-700',
    scan: 'bg-indigo-100 text-indigo-700',
    electrical: 'bg-yellow-100 text-yellow-700',
    bodywork: 'bg-pink-100 text-pink-700',
};

// Progress stages for the progress bar
const progressStages = [
    { key: 'pending', label: 'Pendiente' },
    { key: 'diagnosing', label: 'Diagnóstico' },
    { key: 'in_progress', label: 'En Progreso' },
    { key: 'quality_check', label: 'Calidad' },
    { key: 'completed', label: 'Completado' },
    { key: 'delivered', label: 'Entregado' },
];

const getStageIndex = (status: string): number => {
    const idx = progressStages.findIndex((s) => s.key === status);
    return idx >= 0 ? idx : 0;
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

export default function ClientOrdersIndex() {
    const { props } = usePage<ClientOrdersPageProps>();
    const { orders, filters, statuses } = props;
    const [activeFilter, setActiveFilter] = useState(filters?.status || '');

    React.useEffect(() => {
        setActiveFilter(filters?.status || '');
    }, [filters]);

    const handleFilter = useCallback((status: string) => {
        setActiveFilter(status);
        router.get(
            '/orders',
            { status: status || undefined },
            { preserveState: true, preserveScroll: true }
        );
    }, []);

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <ClipboardDocumentListIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Mis Órdenes de Servicio</h1>
                        <p className="text-sm text-gray-500">Seguimiento de tus servicios</p>
                    </div>
                </div>
            }
        >
            <div className="space-y-6">
                {/* Filters */}
                <div className="card">
                    <div className="flex items-center gap-3 mb-4">
                        <FunnelIcon className="h-5 w-5 text-gray-400" />
                        <span className="text-sm font-medium text-gray-700">Filtrar por estatus</span>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <button
                            type="button"
                            onClick={() => handleFilter('')}
                            className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                                activeFilter === ''
                                    ? 'bg-primary-600 text-white shadow-sm'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            }`}
                        >
                            Todas
                        </button>
                        {(statuses || [
                            { value: 'pending', label: 'Pendiente' },
                            { value: 'diagnosing', label: 'En diagnóstico' },
                            { value: 'in_progress', label: 'En progreso' },
                            { value: 'waiting_parts', label: 'Esperando repuestos' },
                            { value: 'quality_check', label: 'Control de calidad' },
                            { value: 'completed', label: 'Completado' },
                            { value: 'delivered', label: 'Entregado' },
                        ]).map((s) => (
                            <button
                                key={s.value}
                                type="button"
                                onClick={() => handleFilter(s.value)}
                                className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                                    activeFilter === s.value
                                        ? 'bg-primary-600 text-white shadow-sm'
                                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                }`}
                            >
                                {s.label}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Order cards */}
                {orders.data.length === 0 ? (
                    <EmptyState
                        title="No hay órdenes de servicio"
                        description="No tienes órdenes de servicio registradas. Crea una nueva orden para comenzar."
                        icon={ClipboardDocumentListIcon}
                    />
                ) : (
                    <div className="space-y-4">
                        {orders.data.map((order) => {
                            const currentStageIdx = getStageIndex(order.status);

                            return (
                                <div
                                    key={order.id}
                                    className="card hover:shadow-card-hover transition-shadow duration-200"
                                >
                                    {/* Card header */}
                                    <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                                        <div className="flex items-center gap-3">
                                            <div>
                                                <Link
                                                    href={`/orders/${order.id}`}
                                                    className="text-lg font-bold text-primary-600 hover:text-primary-700 transition-colors"
                                                >
                                                    {order.order_number}
                                                </Link>
                                                <div className="flex items-center gap-2 mt-1">
                                                    <span className="text-sm text-gray-500">
                                                        {order.vehicle?.brand} {order.vehicle?.model}
                                                    </span>
                                                    <span className="text-gray-300">•</span>
                                                    <span className="text-sm font-medium text-primary-600">
                                                        {order.vehicle?.plate_formatted || order.vehicle?.plate}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <StatusBadge
                                            status={order.status}
                                            label={order.status_label || statusLabels[order.status]}
                                            size="md"
                                        />
                                    </div>

                                    {/* Service type */}
                                    <div className="mb-4">
                                        <span
                                            className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                serviceTypeBadgeClasses[order.service_type] || 'bg-gray-100 text-gray-700'
                                            }`}
                                        >
                                            {order.service_type_label}
                                        </span>
                                    </div>

                                    {/* Progress bar */}
                                    <div className="mb-4">
                                        <div className="flex items-center justify-between mb-2">
                                            {progressStages.map((stage, idx) => (
                                                <div
                                                    key={stage.key}
                                                    className="flex flex-col items-center flex-1"
                                                >
                                                    <div
                                                        className={`h-2.5 flex-1 rounded-full transition-colors ${
                                                            idx <= currentStageIdx
                                                                ? 'bg-primary-500'
                                                                : 'bg-gray-200'
                                                        }`}
                                                        style={{ minWidth: '8px' }}
                                                    />
                                                </div>
                                            ))}
                                        </div>
                                        <div className="flex items-center justify-between">
                                            {progressStages.map((stage, idx) => (
                                                <div
                                                    key={stage.key}
                                                    className={`text-[10px] font-medium text-center flex-1 ${
                                                        idx <= currentStageIdx
                                                            ? 'text-primary-600'
                                                            : 'text-gray-400'
                                                    }`}
                                                >
                                                    {stage.label}
                                                </div>
                                            ))}
                                        </div>
                                    </div>

                                    {/* Bottom info row */}
                                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-gray-100">
                                        <div className="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                            <div className="flex items-center gap-1.5">
                                                <ClockIcon className="h-4 w-4" />
                                                <span>{formatDateTime(order.updated_at)}</span>
                                            </div>
                                            {order.technician && (
                                                <div className="flex items-center gap-1.5">
                                                    <UserIcon className="h-4 w-4" />
                                                    <span>{order.technician.name}</span>
                                                </div>
                                            )}
                                        </div>
                                        <Link
                                            href={`/orders/${order.id}`}
                                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium hover:bg-primary-100 transition-colors self-start sm:self-auto"
                                        >
                                            <EyeIcon className="h-4 w-4" />
                                            Ver Detalles
                                        </Link>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}

                {/* Pagination */}
                {orders.last_page > 1 && (
                    <div className="flex items-center justify-between card">
                        <p className="text-sm text-gray-500">
                            Mostrando {orders.from} a {orders.to} de {orders.total} órdenes
                        </p>
                        <div className="flex items-center gap-2">
                            <button
                                type="button"
                                disabled={orders.current_page <= 1}
                                onClick={() => router.get(orders.prev_page_url || '')}
                                className="flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <ChevronLeftIcon className="h-4 w-4" />
                                Anterior
                            </button>
                            <div className="hidden sm:flex items-center gap-1">
                                {Array.from({ length: orders.last_page }, (_, i) => i + 1)
                                    .filter((page) => {
                                        return (
                                            page === 1 ||
                                            page === orders.last_page ||
                                            Math.abs(page - orders.current_page) <= 1
                                        );
                                    })
                                    .map((page, idx, arr) => {
                                        const prevPage = arr[idx - 1];
                                        return (
                                            <React.Fragment key={page}>
                                                {prevPage && page - prevPage > 1 && (
                                                    <span className="px-2 text-gray-400">...</span>
                                                )}
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        router.get(`/orders?page=${page}&status=${activeFilter || ''}`)
                                                    }
                                                    className={`px-3 py-1.5 text-sm rounded-lg font-medium transition-colors ${
                                                        page === orders.current_page
                                                            ? 'bg-primary-600 text-white'
                                                            : 'text-gray-700 hover:bg-gray-100'
                                                    }`}
                                                >
                                                    {page}
                                                </button>
                                            </React.Fragment>
                                        );
                                    })}
                            </div>
                            <button
                                type="button"
                                disabled={orders.current_page >= orders.last_page}
                                onClick={() => router.get(orders.next_page_url || '')}
                                className="flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                Siguiente
                                <ChevronRightIcon className="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
