import React, { useState, useEffect, useCallback } from 'react';
import { usePage, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import EmptyState from '../../../Components/EmptyState';
import {
    ClipboardDocumentListIcon,
    FunnelIcon,
    ArrowPathIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    EyeIcon,
    ArrowUpTrayIcon,
    WrenchScrewdriverIcon,
    ClockIcon,
    CheckCircleIcon,
    ExclamationTriangleIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, DashboardStats, PaginationData } from '../../../types';

interface TechnicianOrdersPageProps extends PageProps {
    orders: PaginationData<ServiceOrder>;
    stats: DashboardStats;
    filters?: {
        status?: string;
    };
    statuses?: { value: string; label: string }[];
}

const priorityBadgeClasses: Record<string, string> = {
    low: 'bg-gray-100 text-gray-700',
    normal: 'bg-blue-100 text-blue-700',
    high: 'bg-yellow-100 text-yellow-800',
    urgent: 'bg-red-100 text-red-800',
};

const priorityLabels: Record<string, string> = {
    low: 'Baja',
    normal: 'Normal',
    high: 'Alta',
    urgent: 'Urgente',
};

const serviceTypeBadgeClasses: Record<string, string> = {
    diagnostic: 'bg-purple-100 text-purple-700',
    repair: 'bg-orange-100 text-orange-700',
    maintenance: 'bg-teal-100 text-teal-700',
    scan: 'bg-indigo-100 text-indigo-700',
    electrical: 'bg-yellow-100 text-yellow-700',
    bodywork: 'bg-pink-100 text-pink-700',
};

const serviceTypeLabels: Record<string, string> = {
    diagnostic: 'Diagnóstico',
    repair: 'Reparación',
    maintenance: 'Mantenimiento',
    scan: 'Escaneo',
    electrical: 'Eléctrico',
    bodywork: 'Carrocería',
};

const statusTransitions: Record<string, string[]> = {
    pending: ['diagnosing'],
    diagnosing: ['in_progress', 'waiting_parts'],
    in_progress: ['quality_check', 'waiting_parts'],
    waiting_parts: ['in_progress'],
    quality_check: ['completed', 'in_progress'],
    completed: ['delivered'],
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

export default function TechnicianOrdersIndex() {
    const { props } = usePage<TechnicianOrdersPageProps>();
    const { orders, stats, filters, statuses } = props;
    const [activeFilter, setActiveFilter] = useState(filters?.status || '');
    const [showDropdown, setShowDropdown] = useState<number | null>(null);
    const [autoRefresh, setAutoRefresh] = useState(true);

    useEffect(() => {
        setActiveFilter(filters?.status || '');
    }, [filters]);

    useEffect(() => {
        if (!autoRefresh) return;
        const interval = setInterval(() => {
            router.reload({ only: ['orders', 'stats'], preserveState: true });
        }, 30000);
        return () => clearInterval(interval);
    }, [autoRefresh]);

    const handleFilter = useCallback((status: string) => {
        setActiveFilter(status);
        router.get(
            '/tecnico/ordenes',
            { status: status || undefined },
            { preserveState: true, preserveScroll: true }
        );
    }, []);

    const handleQuickStatus = useCallback((orderId: number, newStatus: string) => {
        router.put(
            `/tecnico/ordenes/${orderId}/status`,
            { status: newStatus, notes: '' },
            { preserveState: true }
        );
        setShowDropdown(null);
    }, []);

    const formatDate = (dateStr: string): string => {
        return new Date(dateStr).toLocaleDateString('es-MX', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <ClipboardDocumentListIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Mis Órdenes de Servicio</h1>
                        <p className="text-sm text-gray-500">Gestiona las órdenes asignadas</p>
                    </div>
                </div>
            }
        >
            <div className="space-y-6">
                {/* Auto refresh hint */}
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-2 text-sm text-gray-500">
                        <ArrowPathIcon className={`h-4 w-4 ${autoRefresh ? 'animate-spin-slow text-primary-500' : 'text-gray-400'}`} />
                        <span>{autoRefresh ? 'Actualización automática cada 30s' : 'Actualización automática desactivada'}</span>
                    </div>
                    <button
                        type="button"
                        onClick={() => setAutoRefresh(!autoRefresh)}
                        className="text-sm text-primary-600 hover:text-primary-700 font-medium"
                    >
                        {autoRefresh ? 'Desactivar' : 'Activar'} auto-refresh
                    </button>
                </div>

                {/* Stats */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div className="stat-card hover:shadow-card-hover transition-shadow duration-200">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                            <ClipboardDocumentListIcon className="h-6 w-6 text-blue-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500 truncate">Total Asignadas</p>
                            <p className="text-2xl font-bold text-gray-900">{stats?.assigned_orders ?? 0}</p>
                        </div>
                    </div>
                    <div className="stat-card hover:shadow-card-hover transition-shadow duration-200">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-100">
                            <WrenchScrewdriverIcon className="h-6 w-6 text-primary-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500 truncate">En Progreso</p>
                            <p className="text-2xl font-bold text-gray-900">{stats?.active_orders ?? 0}</p>
                        </div>
                    </div>
                    <div className="stat-card hover:shadow-card-hover transition-shadow duration-200">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
                            <CheckCircleIcon className="h-6 w-6 text-green-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500 truncate">Completadas Hoy</p>
                            <p className="text-2xl font-bold text-gray-900">{stats?.completed_today ?? 0}</p>
                        </div>
                    </div>
                    <div className="stat-card hover:shadow-card-hover transition-shadow duration-200">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-100">
                            <ExclamationTriangleIcon className="h-6 w-6 text-yellow-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500 truncate">Pendientes Diagnóstico</p>
                            <p className="text-2xl font-bold text-gray-900">{stats?.pending_diagnostics ?? 0}</p>
                        </div>
                    </div>
                </div>

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
                        title="No hay órdenes asignadas"
                        description="No tienes órdenes de servicio asignadas en este momento. Se mostrarán cuando un administrador te asigne una."
                        icon={ClipboardDocumentListIcon}
                    />
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        {orders.data.map((order) => {
                            const availableTransitions = statusTransitions[order.status] || [];

                            return (
                                <div
                                    key={order.id}
                                    className="card hover:shadow-card-hover transition-shadow duration-200 flex flex-col"
                                >
                                    {/* Card header */}
                                    <div className="flex items-start justify-between mb-4">
                                        <div>
                                            <Link
                                                href={`/tecnico/ordenes/${order.id}`}
                                                className="text-lg font-bold text-primary-600 hover:text-primary-700 transition-colors"
                                            >
                                                {order.order_number}
                                            </Link>
                                            <div className="flex items-center gap-2 mt-1">
                                                <span className="text-sm text-gray-500">
                                                    {order.vehicle?.plate_formatted || order.vehicle?.plate}
                                                </span>
                                                <span className="text-gray-300">•</span>
                                                <span className="text-sm font-medium text-gray-700">
                                                    {order.vehicle?.brand} {order.vehicle?.model}
                                                </span>
                                            </div>
                                        </div>
                                        <StatusBadge
                                            status={order.status}
                                            label={order.status_label || statusLabels[order.status]}
                                            size="md"
                                        />
                                    </div>

                                    {/* Client name */}
                                    <div className="flex items-center gap-2 mb-3">
                                        <span className="text-sm text-gray-500">Cliente:</span>
                                        <span className="text-sm font-medium text-gray-700">
                                            {order.client?.name}
                                        </span>
                                    </div>

                                    {/* Service type badge */}
                                    <div className="mb-3">
                                        <span
                                            className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                serviceTypeBadgeClasses[order.service_type] || 'bg-gray-100 text-gray-700'
                                            }`}
                                        >
                                            {order.service_type_label || serviceTypeLabels[order.service_type]}
                                        </span>
                                    </div>

                                    {/* Priority badge */}
                                    <div className="mb-3">
                                        <span
                                            className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                priorityBadgeClasses[order.priority] || 'bg-gray-100 text-gray-700'
                                            }`}
                                        >
                                            {order.priority_label || priorityLabels[order.priority]}
                                        </span>
                                    </div>

                                    {/* Created date */}
                                    <div className="flex items-center gap-1.5 text-sm text-gray-400 mb-4">
                                        <ClockIcon className="h-4 w-4" />
                                        <span>{formatDate(order.created_at)}</span>
                                    </div>

                                    {/* Spacer */}
                                    <div className="flex-1" />

                                    {/* Actions */}
                                    <div className="flex items-center gap-2 pt-4 border-t border-gray-100">
                                        <Link
                                            href={`/tecnico/ordenes/${order.id}`}
                                            className="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium hover:bg-primary-100 transition-colors"
                                        >
                                            <EyeIcon className="h-4 w-4" />
                                            Ver Detalles
                                        </Link>

                                        {availableTransitions.length > 0 && (
                                            <div className="relative">
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setShowDropdown(showDropdown === order.id ? null : order.id)
                                                    }
                                                    className="flex items-center gap-1.5 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors"
                                                >
                                                    <ArrowUpTrayIcon className="h-4 w-4" />
                                                    Estatus
                                                </button>
                                                {showDropdown === order.id && (
                                                    <>
                                                        <div
                                                            className="fixed inset-0 z-10"
                                                            onClick={() => setShowDropdown(null)}
                                                        />
                                                        <div className="absolute right-0 bottom-full mb-1 z-20 w-48 rounded-lg bg-white shadow-lg ring-1 ring-black/5 py-1">
                                                            {availableTransitions.map((transition) => (
                                                                <button
                                                                    key={transition}
                                                                    type="button"
                                                                    onClick={() => handleQuickStatus(order.id, transition)}
                                                                    className="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                                                >
                                                                    <span className="flex items-center justify-between">
                                                                        <span>{statusLabels[transition]}</span>
                                                                        <span className="text-gray-400">→</span>
                                                                    </span>
                                                                </button>
                                                            ))}
                                                        </div>
                                                    </>
                                                )}
                                            </div>
                                        )}
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
                                                        router.get(`/tecnico/ordenes?page=${page}&status=${activeFilter || ''}`)
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
