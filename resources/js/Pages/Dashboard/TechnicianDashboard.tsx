import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import StatsCard from '../../Components/StatsCard';
import StatusBadge from '../../Components/StatusBadge';
import {
    ClipboardDocumentListIcon,
    WrenchScrewdriverIcon,
    CheckCircleIcon,
    ClockIcon,
    EyeIcon,
    PencilSquareIcon,
    DocumentTextIcon,
} from '@heroicons/react/24/outline';
import { PageProps, DashboardStats, ServiceOrder, ServiceReport } from '../../types';

interface TechnicianDashboardProps extends PageProps {
    stats: DashboardStats;
    active_orders: ServiceOrder[];
    recent_reports: ServiceReport[];
}

export default function TechnicianDashboard({
    stats,
    active_orders,
    recent_reports,
}: TechnicianDashboardProps) {
    return (
        <>
            <Head title="Panel del Técnico" />
            <AuthenticatedLayout
                header={
                    <h1 className="text-xl font-semibold text-gray-900">
                        Panel del Técnico
                    </h1>
                }
            >
                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <StatsCard
                        title="Órdenes Asignadas"
                        value={stats.assigned_orders || 0}
                        icon={ClipboardDocumentListIcon}
                        iconBg="bg-primary-100"
                        iconColor="text-primary-600"
                    />
                    <StatsCard
                        title="En Diagnóstico"
                        value={stats.pending_diagnostics || 0}
                        icon={WrenchScrewdriverIcon}
                        iconBg="bg-blue-100"
                        iconColor="text-blue-600"
                    />
                    <StatsCard
                        title="Completadas Hoy"
                        value={stats.completed_today || 0}
                        icon={CheckCircleIcon}
                        iconBg="bg-green-100"
                        iconColor="text-green-600"
                    />
                    <StatsCard
                        title="Pendientes"
                        value={stats.active_orders || 0}
                        icon={ClockIcon}
                        iconBg="bg-yellow-100"
                        iconColor="text-yellow-600"
                    />
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    {/* My Active Orders */}
                    <div className="lg:col-span-2">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-lg font-semibold text-gray-900">
                                Mis Órdenes Activas
                            </h2>
                            <button
                                onClick={() => router.get('/tecnico/ordenes')}
                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                            >
                                Ver todas
                            </button>
                        </div>
                        {active_orders.length === 0 ? (
                            <div className="card text-center py-12">
                                <ClipboardDocumentListIcon className="h-12 w-12 text-gray-300 mx-auto mb-4" />
                                <p className="text-gray-500 font-medium">
                                    No tienes órdenes asignadas actualmente.
                                </p>
                                <p className="text-sm text-gray-400 mt-1">
                                    Se te asignarán nuevas órdenes cuando estén disponibles.
                                </p>
                            </div>
                        ) : (
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {active_orders.map((order) => (
                                    <div
                                        key={order.id}
                                        className="card hover:shadow-card-hover transition-shadow"
                                    >
                                        <div className="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 className="text-sm font-semibold text-gray-900">
                                                    {order.order_number}
                                                </h3>
                                                <p className="text-sm text-gray-500">
                                                    {order.service_type_label || order.service_type}
                                                </p>
                                            </div>
                                            <StatusBadge
                                                status={order.status}
                                                label={order.status_label}
                                                size="sm"
                                            />
                                        </div>

                                        <div className="space-y-2 mb-4">
                                            <div className="flex items-center gap-2 text-sm">
                                                <TruckIcon className="h-4 w-4 text-gray-400" />
                                                <span className="text-gray-600">
                                                    {order.vehicle?.brand} {order.vehicle?.model}{' '}
                                                    <span className="text-gray-400">
                                                        ({order.vehicle?.plate})
                                                    </span>
                                                </span>
                                            </div>
                                            <div className="flex items-center gap-2 text-sm">
                                                <WrenchScrewdriverIcon className="h-4 w-4 text-gray-400" />
                                                <span className="text-gray-600">
                                                    Cliente: {order.client?.name}
                                                </span>
                                            </div>
                                        </div>

                                        <div className="flex items-center justify-between pt-3 border-t border-gray-100">
                                            <span
                                                className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                                    order.priority === 'high'
                                                        ? 'bg-red-100 text-red-800'
                                                        : order.priority === 'medium'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : 'bg-gray-100 text-gray-800'
                                                }`}
                                            >
                                                Prioridad: {order.priority_label || order.priority}
                                            </span>
                                            <div className="flex items-center gap-2">
                                                <Link
                                                    href={`/tecnico/ordenes/${order.id}`}
                                                    className="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors"
                                                >
                                                    <EyeIcon className="h-3.5 w-3.5" />
                                                    Ver
                                                </Link>
                                                <button
                                                    onClick={() => router.get(`/tecnico/ordenes/${order.id}`)}
                                                    className="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-secondary-600 bg-secondary-50 rounded-lg hover:bg-secondary-100 transition-colors"
                                                >
                                                    <PencilSquareIcon className="h-3.5 w-3.5" />
                                                    Actualizar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Right Column */}
                    <div className="space-y-6">
                        {/* Quick Add Report */}
                        <Link
                            href="/tecnico/reportes/create"
                            className="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-primary-300 hover:border-primary-500 hover:bg-primary-50 transition-all group card"
                        >
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 group-hover:bg-primary-200 transition-colors">
                                <DocumentTextIcon className="h-6 w-6 text-primary-600" />
                            </div>
                            <div>
                                <p className="text-sm font-semibold text-gray-900 group-hover:text-primary-700">
                                    Agregar Reporte
                                </p>
                                <p className="text-xs text-gray-500">
                                    Registrar hallazgos y trabajos realizados
                                </p>
                            </div>
                        </Link>

                        {/* Recent Reports */}
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200">
                                <h2 className="text-lg font-semibold text-gray-900">
                                    Reportes Recientes
                                </h2>
                            </div>
                            <div className="p-4">
                                {recent_reports.length === 0 ? (
                                    <p className="text-sm text-gray-500 text-center py-4">
                                        No hay reportes recientes.
                                    </p>
                                ) : (
                                    <ul className="space-y-3">
                                        {recent_reports.slice(0, 5).map((report) => (
                                            <li key={report.id} className="p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                                                <div className="flex items-center justify-between mb-1">
                                                    <p className="text-sm font-medium text-gray-900 truncate">
                                                        {report.service_order?.order_number || `Reporte #${report.id}`}
                                                    </p>
                                                    <span className="text-xs text-gray-500">
                                                        {new Date(report.report_date || report.created_at).toLocaleDateString('es-MX', {
                                                            day: '2-digit',
                                                            month: 'short',
                                                        })}
                                                    </span>
                                                </div>
                                                <p className="text-xs text-gray-500 line-clamp-2">
                                                    {report.findings || report.description || 'Sin hallazgos registrados'}
                                                </p>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
