import React from 'react';
import { usePage, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import {
    ClipboardDocumentListIcon,
    TruckIcon,
    UserIcon,
    WrenchScrewdriverIcon,
    DocumentTextIcon,
    ClockIcon,
    XMarkIcon,
    CheckCircleIcon,
    ArrowRightIcon,
    ExclamationCircleIcon,
    PhotoIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, ServiceReport } from '../../../types';

interface ClientOrderShowProps extends PageProps {
    order: ServiceOrder;
    reports: ServiceReport[];
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

const priorityBadgeClasses: Record<string, string> = {
    low: 'bg-gray-100 text-gray-700 border-gray-200',
    normal: 'bg-blue-100 text-blue-700 border-blue-200',
    high: 'bg-yellow-100 text-yellow-800 border-yellow-200',
    urgent: 'bg-red-100 text-red-800 border-red-200',
};

const priorityLabels: Record<string, string> = {
    low: 'Baja',
    normal: 'Normal',
    high: 'Alta',
    urgent: 'Urgente',
};

// Progress stages for the progress tracker
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
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};

const formatDateTime = (dateStr: string): string => {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

export default function ClientOrderShow() {
    const { props } = usePage<ClientOrderShowProps>();
    const { order, reports } = props;

    const currentStageIdx = getStageIndex(order.status);

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <Link
                        href="/orders"
                        className="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <XMarkIcon className="h-4 w-4" />
                        Volver
                    </Link>
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <ClipboardDocumentListIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">{order.order_number}</h1>
                        <p className="text-sm text-gray-500">Detalles de la orden de servicio</p>
                    </div>
                </div>
            }
        >
            <div className="space-y-6">
                {/* Order header card - READ ONLY */}
                <div className="card">
                    <div className="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div className="flex-1 space-y-4">
                            {/* Order number and status */}
                            <div className="flex flex-wrap items-center gap-3">
                                <h2 className="text-2xl font-bold text-gray-900">{order.order_number}</h2>
                                <StatusBadge
                                    status={order.status}
                                    label={order.status_label || statusLabels[order.status]}
                                    size="md"
                                />
                                <span
                                    className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border ${
                                        priorityBadgeClasses[order.priority] || 'bg-gray-100 text-gray-700'
                                    }`}
                                >
                                    Prioridad: {order.priority_label || priorityLabels[order.priority]}
                                </span>
                            </div>

                            {/* Vehicle info */}
                            <div className="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100">
                                    <TruckIcon className="h-5 w-5 text-primary-600" />
                                </div>
                                <div className="space-y-1">
                                    <div className="flex items-center gap-2">
                                        <span className="text-sm font-bold text-primary-600">
                                            {order.vehicle?.plate_formatted || order.vehicle?.plate}
                                        </span>
                                        <span className="text-gray-300">•</span>
                                        <span className="text-sm font-medium text-gray-700">
                                            {order.vehicle?.brand} {order.vehicle?.model} ({order.vehicle?.year})
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Service type and technician */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div className="flex items-center gap-2">
                                    <WrenchScrewdriverIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Tipo de Servicio</p>
                                        <p className="text-sm font-medium text-gray-700">
                                            {order.service_type_label}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-2">
                                    <UserIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Técnico Asignado</p>
                                        <p className="text-sm font-medium text-gray-700">
                                            {order.technician?.name || 'Por asignar'}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Progress Tracker */}
                <div className="card">
                    <div className="flex items-center gap-2 mb-6">
                        <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100">
                            <ClipboardDocumentListIcon className="h-4 w-4 text-primary-600" />
                        </div>
                        <h3 className="text-base font-semibold text-gray-900">Progreso de la Orden</h3>
                    </div>

                    <div className="relative">
                        {/* Desktop progress */}
                        <div className="hidden sm:block">
                            <div className="flex items-center justify-between relative">
                                {/* Connection line */}
                                <div className="absolute top-5 left-0 right-0 h-1 bg-gray-200 rounded-full -z-10">
                                    <div
                                        className="h-full bg-primary-500 rounded-full transition-all duration-500"
                                        style={{ width: `${(currentStageIdx / (progressStages.length - 1)) * 100}%` }}
                                    />
                                </div>

                                {progressStages.map((stage, idx) => {
                                    const isCompleted = idx < currentStageIdx;
                                    const isCurrent = idx === currentStageIdx;
                                    const isPending = idx > currentStageIdx;

                                    return (
                                        <div key={stage.key} className="flex flex-col items-center flex-1">
                                            <div
                                                className={`flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all ${
                                                    isCompleted
                                                        ? 'bg-primary-500 border-primary-500 text-white'
                                                        : isCurrent
                                                        ? 'bg-white border-primary-500 text-primary-600'
                                                        : 'bg-white border-gray-300 text-gray-400'
                                                }`}
                                            >
                                                {isCompleted ? (
                                                    <CheckCircleIcon className="h-5 w-5" />
                                                ) : (
                                                    <span className="text-xs font-bold">{idx + 1}</span>
                                                )}
                                            </div>
                                            <p
                                                className={`mt-2 text-xs font-medium text-center ${
                                                    isCompleted
                                                        ? 'text-primary-600'
                                                        : isCurrent
                                                        ? 'text-primary-600 font-semibold'
                                                        : 'text-gray-400'
                                                }`}
                                            >
                                                {stage.label}
                                            </p>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>

                        {/* Mobile progress (vertical) */}
                        <div className="sm:hidden space-y-0">
                            {progressStages.map((stage, idx) => {
                                const isCompleted = idx < currentStageIdx;
                                const isCurrent = idx === currentStageIdx;
                                const isPending = idx > currentStageIdx;

                                return (
                                    <div key={stage.key} className="flex items-start gap-3">
                                        {/* Vertical line */}
                                        <div className="flex flex-col items-center">
                                            <div
                                                className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 ${
                                                    isCompleted
                                                        ? 'bg-primary-500 border-primary-500 text-white'
                                                        : isCurrent
                                                        ? 'bg-white border-primary-500 text-primary-600'
                                                        : 'bg-white border-gray-300 text-gray-400'
                                                }`}
                                            >
                                                {isCompleted ? (
                                                    <CheckCircleIcon className="h-4 w-4" />
                                                ) : (
                                                    <span className="text-[10px] font-bold">{idx + 1}</span>
                                                )}
                                            </div>
                                            {idx < progressStages.length - 1 && (
                                                <div
                                                    className={`w-0.5 h-8 ${
                                                        idx < currentStageIdx ? 'bg-primary-500' : 'bg-gray-200'
                                                    }`}
                                                />
                                            )}
                                        </div>
                                        <div className="pt-1 pb-4">
                                            <p
                                                className={`text-sm font-medium ${
                                                    isCompleted || isCurrent
                                                        ? 'text-primary-600'
                                                        : 'text-gray-400'
                                                }`}
                                            >
                                                {stage.label}
                                            </p>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* Description section */}
                {order.description && (
                    <div className="card">
                        <div className="flex items-center gap-2 mb-3">
                            <DocumentTextIcon className="h-5 w-5 text-gray-400" />
                            <h3 className="text-base font-semibold text-gray-900">Descripción del Servicio</h3>
                        </div>
                        <p className="text-sm text-gray-600 whitespace-pre-line">{order.description}</p>
                    </div>
                )}

                {/* Reports Timeline */}
                <div className="card">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100">
                            <DocumentTextIcon className="h-4 w-4 text-indigo-600" />
                        </div>
                        <h3 className="text-base font-semibold text-gray-900">Reportes del Técnico</h3>
                        <span className="text-sm text-gray-500">({reports.length})</span>
                    </div>

                    {reports.length === 0 ? (
                        <div className="text-center py-8">
                            <DocumentTextIcon className="h-12 w-12 text-gray-300 mx-auto mb-3" />
                            <p className="text-gray-500 text-sm">No hay reportes registrados aún.</p>
                            <p className="text-gray-400 text-xs mt-1">
                                Los reportes del técnico aparecerán aquí a medida que avance el servicio.
                            </p>
                        </div>
                    ) : (
                        <div className="relative">
                            {/* Timeline line */}
                            <div className="absolute left-[18px] top-3 bottom-3 w-0.5 bg-gray-200" />

                            <div className="space-y-6">
                                {reports.map((report, idx) => (
                                    <div key={report.id} className="relative pl-12">
                                        {/* Timeline dot */}
                                        <div className="absolute left-[11px] top-1.5 flex h-4 w-4 items-center justify-center">
                                            <div className="h-3 w-3 rounded-full bg-primary-500 ring-4 ring-white" />
                                        </div>

                                        {/* Report card */}
                                        <div className="border border-gray-200 rounded-xl p-4 hover:shadow-card-hover transition-shadow duration-200">
                                            {/* Header */}
                                            <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                                <div>
                                                    <h4 className="text-sm font-semibold text-gray-900">
                                                        {report.title || 'Reporte sin título'}
                                                    </h4>
                                                    <div className="flex flex-wrap items-center gap-2 mt-1">
                                                        <span className="text-xs text-gray-500">
                                                            {formatDateTime(report.created_at)}
                                                        </span>
                                                        {report.technician && (
                                                            <>
                                                                <span className="text-gray-300">•</span>
                                                                <span className="text-xs text-gray-500">
                                                                    {report.technician.name}
                                                                </span>
                                                            </>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Description */}
                                            {report.description && (
                                                <div className="mb-3">
                                                    <p className="text-xs font-medium text-gray-500 mb-1">Descripción:</p>
                                                    <p className="text-sm text-gray-700 whitespace-pre-line">
                                                        {report.description}
                                                    </p>
                                                </div>
                                            )}

                                            {/* Findings */}
                                            {report.findings && (
                                                <div className="mb-3">
                                                    <p className="text-xs font-medium text-gray-500 mb-1 flex items-center gap-1">
                                                        <ExclamationCircleIcon className="h-3.5 w-3.5" />
                                                        Hallazgos:
                                                    </p>
                                                    <p className="text-sm text-gray-700 whitespace-pre-line">
                                                        {report.findings}
                                                    </p>
                                                </div>
                                            )}

                                            {/* Notes for client (highlighted) */}
                                            {report.notes && (
                                                <div className="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                                    <p className="text-xs font-medium text-blue-700 mb-1">
                                                        📋 Nota para ti:
                                                    </p>
                                                    <p className="text-sm text-blue-800 whitespace-pre-line">
                                                        {report.notes}
                                                    </p>
                                                </div>
                                            )}

                                            {/* Status change indicator */}
                                            {report.work_performed && (
                                                <div className="mb-3">
                                                    <p className="text-xs font-medium text-gray-500 mb-1 flex items-center gap-1">
                                                        <WrenchScrewdriverIcon className="h-3.5 w-3.5" />
                                                        Trabajo realizado:
                                                    </p>
                                                    <p className="text-sm text-gray-700 whitespace-pre-line">
                                                        {report.work_performed}
                                                    </p>
                                                </div>
                                            )}

                                            {/* Bottom info */}
                                            <div className="flex flex-wrap items-center gap-4 pt-3 border-t border-gray-100">
                                                {report.labor_hours != null && report.labor_hours > 0 && (
                                                    <div className="flex items-center gap-1.5 text-xs text-gray-500">
                                                        <ClockIcon className="h-3.5 w-3.5" />
                                                        <span>{report.formatted_labor_hours || `${report.labor_hours}h`} de trabajo</span>
                                                    </div>
                                                )}
                                                {report.parts_used && report.parts_used.length > 0 && (
                                                    <div className="flex items-center gap-1.5 text-xs text-gray-500">
                                                        <WrenchScrewdriverIcon className="h-3.5 w-3.5" />
                                                        <span>
                                                            {report.parts_count || report.parts_used.length} repuesto(s) utilizado(s)
                                                        </span>
                                                    </div>
                                                )}
                                                {report.images_count != null && report.images_count > 0 && (
                                                    <div className="flex items-center gap-1.5 text-xs text-gray-500">
                                                        <PhotoIcon className="h-3.5 w-3.5" />
                                                        <span>{report.images_count} imagen(es)</span>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                {/* Read-only notice */}
                <div className="text-center py-4">
                    <p className="text-xs text-gray-400">
                        Esta vista es de solo lectura. Si necesitas hacer cambios, contacta al taller.
                    </p>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
