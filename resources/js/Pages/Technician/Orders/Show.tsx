import React, { useState } from 'react';
import { usePage, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import Modal from '../../../Components/Modal';
import {
    ClipboardDocumentListIcon,
    EyeIcon,
    TruckIcon,
    UserIcon,
    WrenchScrewdriverIcon,
    CurrencyDollarIcon,
    DocumentTextIcon,
    PlusIcon,
    TrashIcon,
    PhotoIcon,
    ClockIcon,
    CalendarDaysIcon,
    ExclamationCircleIcon,
    ArrowRightIcon,
    ChevronDownIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, ServiceReport, PartUsed } from '../../../types';

interface TechnicianOrderShowProps extends PageProps {
    order: ServiceOrder;
    reports: ServiceReport[];
    available_transitions?: string[];
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

const statusTransitions: Record<string, string[]> = {
    pending: ['diagnosing'],
    diagnosing: ['in_progress', 'waiting_parts'],
    in_progress: ['quality_check', 'waiting_parts'],
    waiting_parts: ['in_progress'],
    quality_check: ['completed', 'in_progress'],
    completed: ['delivered'],
};

export default function TechnicianOrderShow() {
    const { props } = usePage<TechnicianOrderShowProps>();
    const { order, reports } = props;

    const [isReportModalOpen, setIsReportModalOpen] = useState(false);
    const [isStatusModalOpen, setIsStatusModalOpen] = useState(false);

    const reportForm = useForm({
        title: '',
        description: '',
        findings: '',
        status: '',
        parts_used: [{ name: '', quantity: 1 }] as PartUsed[],
        labor_hours: '',
        notes: '',
        images: [] as File[],
    });

    const statusForm = useForm({
        status: '',
        notes: '',
    });

    const availableTransitions = statusTransitions[order.status] || [];

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

    const addPartRow = () => {
        reportForm.setData('parts_used', [...reportForm.data.parts_used, { name: '', quantity: 1 }]);
    };

    const removePartRow = (index: number) => {
        const updated = reportForm.data.parts_used.filter((_, i) => i !== index);
        reportForm.setData('parts_used', updated);
    };

    const updatePart = (index: number, field: keyof PartUsed, value: string | number) => {
        const updated = [...reportForm.data.parts_used];
        if (field === 'quantity') {
            updated[index] = { ...updated[index], quantity: Number(value) };
        } else {
            updated[index] = { ...updated[index], [field]: value };
        }
        reportForm.setData('parts_used', updated);
    };

    const handleReportSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        reportForm.post(`/tecnico/ordenes/${order.id}/reports`, {
            onSuccess: () => {
                setIsReportModalOpen(false);
                reportForm.reset();
                router.reload({ only: ['order', 'reports'] });
            },
        });
    };

    const handleStatusSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        statusForm.put(`/tecnico/ordenes/${order.id}/status`, {
            onSuccess: () => {
                setIsStatusModalOpen(false);
                statusForm.reset();
                router.reload({ only: ['order'] });
            },
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <Link
                        href="/tecnico/ordenes"
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
                {/* Order header card */}
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
                                    {order.vehicle?.color && (
                                        <span className="text-xs text-gray-500">Color: {order.vehicle.color}</span>
                                    )}
                                </div>
                            </div>

                            {/* Client and service type */}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div className="flex items-center gap-2">
                                    <UserIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Cliente</p>
                                        <p className="text-sm font-medium text-gray-700">{order.client?.name}</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-2">
                                    <WrenchScrewdriverIcon className="h-5 w-5 text-gray-400" />
                                    <div>
                                        <p className="text-xs text-gray-500">Tipo de Servicio</p>
                                        <p className="text-sm font-medium text-gray-700">
                                            {order.service_type_label}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Description and Diagnosis */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div className="card">
                        <div className="flex items-center gap-2 mb-3">
                            <DocumentTextIcon className="h-5 w-5 text-gray-400" />
                            <h3 className="text-base font-semibold text-gray-900">Descripción</h3>
                        </div>
                        <p className="text-sm text-gray-600 whitespace-pre-line">
                            {order.description || 'No hay descripción proporcionada.'}
                        </p>
                    </div>
                    <div className="card">
                        <div className="flex items-center gap-2 mb-3">
                            <ExclamationCircleIcon className="h-5 w-5 text-gray-400" />
                            <h3 className="text-base font-semibold text-gray-900">Diagnóstico</h3>
                        </div>
                        <p className="text-sm text-gray-600 whitespace-pre-line">
                            {order.diagnosis || 'No hay diagnóstico registrado aún.'}
                        </p>
                    </div>
                </div>

                {/* Cost information */}
                <div className="card">
                    <div className="flex items-center gap-2 mb-4">
                        <CurrencyDollarIcon className="h-5 w-5 text-gray-400" />
                        <h3 className="text-base font-semibold text-gray-900">Información de Costos</h3>
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div className="p-4 bg-blue-50 rounded-xl">
                            <p className="text-xs font-medium text-blue-600 mb-1">Costo Estimado</p>
                            <p className="text-xl font-bold text-blue-900">
                                {order.formatted_estimated_cost || '$0.00'}
                            </p>
                        </div>
                        <div className="p-4 bg-green-50 rounded-xl">
                            <p className="text-xs font-medium text-green-600 mb-1">Costo Actual</p>
                            <p className="text-xl font-bold text-green-900">
                                {order.formatted_actual_cost || 'Por determinar'}
                            </p>
                        </div>
                    </div>
                </div>

                {/* STATUS UPDATE section */}
                <div className="card">
                    <div className="flex items-center justify-between mb-4">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-100">
                                <ArrowRightIcon className="h-5 w-5 text-yellow-600" />
                            </div>
                            <div>
                                <h3 className="text-base font-semibold text-gray-900">Estatus Actual</h3>
                                <p className="text-sm text-gray-500">Gestiona las transiciones de estado</p>
                            </div>
                        </div>
                        {availableTransitions.length > 0 && (
                            <button
                                type="button"
                                onClick={() => setIsStatusModalOpen(true)}
                                className="btn-primary flex items-center gap-2"
                            >
                                <ChevronDownIcon className="h-4 w-4" />
                                Cambiar Estatus
                            </button>
                        )}
                    </div>

                    <div className="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <StatusBadge
                            status={order.status}
                            label={order.status_label || statusLabels[order.status]}
                            size="md"
                        />
                        <span className="text-sm text-gray-500">
                            Última actualización: {formatDateTime(order.updated_at)}
                        </span>
                    </div>

                    {availableTransitions.length === 0 && (
                        <p className="text-sm text-gray-400 mt-3">
                            No hay transiciones disponibles para el estado actual.
                        </p>
                    )}
                </div>

                {/* REPORTS SECTION */}
                <div className="card">
                    <div className="flex items-center justify-between mb-6">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100">
                                <DocumentTextIcon className="h-5 w-5 text-indigo-600" />
                            </div>
                            <div>
                                <h3 className="text-base font-semibold text-gray-900">Reportes de Servicio</h3>
                                <p className="text-sm text-gray-500">{reports.length} reporte(s) registrado(s)</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            onClick={() => setIsReportModalOpen(true)}
                            className="btn-primary flex items-center gap-2"
                        >
                            <PlusIcon className="h-4 w-4" />
                            Agregar Reporte
                        </button>
                    </div>

                    {reports.length === 0 ? (
                        <div className="text-center py-8">
                            <DocumentTextIcon className="h-12 w-12 text-gray-300 mx-auto mb-3" />
                            <p className="text-gray-500 text-sm">No hay reportes registrados para esta orden.</p>
                            <p className="text-gray-400 text-xs mt-1">
                                Agrega un reporte para documentar el progreso del servicio.
                            </p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {reports.map((report) => (
                                <div
                                    key={report.id}
                                    className="border border-gray-200 rounded-xl p-4 hover:shadow-card-hover transition-shadow duration-200"
                                >
                                    <div className="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 className="text-sm font-semibold text-gray-900">{report.title || 'Reporte sin título'}</h4>
                                            <div className="flex items-center gap-2 mt-1">
                                                <CalendarDaysIcon className="h-3.5 w-3.5 text-gray-400" />
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
                                        <div className="flex items-center gap-2">
                                            {report.images_count != null && report.images_count > 0 && (
                                                <span className="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                                                    <PhotoIcon className="h-3 w-3" />
                                                    {report.images_count}
                                                </span>
                                            )}
                                            {report.parts_count != null && report.parts_count > 0 && (
                                                <span className="inline-flex items-center gap-1 px-2 py-0.5 bg-orange-50 text-orange-700 rounded-full text-xs font-medium">
                                                    <WrenchScrewdriverIcon className="h-3 w-3" />
                                                    {report.parts_count} repuesto(s)
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    {report.findings && (
                                        <div className="mb-3">
                                            <p className="text-xs font-medium text-gray-500 mb-1">Hallazgos:</p>
                                            <p className="text-sm text-gray-700 whitespace-pre-line line-clamp-3">
                                                {report.findings}
                                            </p>
                                        </div>
                                    )}

                                    {report.description && (
                                        <div className="mb-3">
                                            <p className="text-xs font-medium text-gray-500 mb-1">Descripción:</p>
                                            <p className="text-sm text-gray-700 whitespace-pre-line line-clamp-2">
                                                {report.description}
                                            </p>
                                        </div>
                                    )}

                                    <div className="flex items-center gap-4 pt-3 border-t border-gray-100">
                                        {report.labor_hours != null && (
                                            <div className="flex items-center gap-1.5 text-sm text-gray-500">
                                                <ClockIcon className="h-4 w-4" />
                                                <span>{report.formatted_labor_hours || `${report.labor_hours}h`} de trabajo</span>
                                            </div>
                                        )}
                                        {report.parts_summary && (
                                            <span className="text-sm text-gray-500">
                                                Repuestos: {report.parts_summary}
                                            </span>
                                        )}
                                        <div className="ml-auto">
                                            <Link
                                                href={`/tecnico/ordenes/${order.id}/reports/${report.id}`}
                                                className="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1"
                                            >
                                                <EyeIcon className="h-3.5 w-3.5" />
                                                Ver completo
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>

                {/* Report Creation Modal */}
                <Modal
                    isOpen={isReportModalOpen}
                    onClose={() => {
                        setIsReportModalOpen(false);
                        reportForm.reset();
                    }}
                    title="Agregar Reporte de Servicio"
                    size="xl"
                >
                    <form onSubmit={handleReportSubmit} className="space-y-5">
                        {/* Title */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Título del Reporte <span className="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={reportForm.data.title}
                                onChange={(e) => reportForm.setData('title', e.target.value)}
                                className="input-field"
                                placeholder="Ej: Diagnóstico inicial completado"
                                required
                            />
                            {reportForm.errors.title && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.title}</p>
                            )}
                        </div>

                        {/* Description */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Descripción
                            </label>
                            <textarea
                                value={reportForm.data.description}
                                onChange={(e) => reportForm.setData('description', e.target.value)}
                                rows={3}
                                className="input-field"
                                placeholder="Describe el trabajo realizado..."
                            />
                            {reportForm.errors.description && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.description}</p>
                            )}
                        </div>

                        {/* Findings */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Hallazgos
                            </label>
                            <textarea
                                value={reportForm.data.findings}
                                onChange={(e) => reportForm.setData('findings', e.target.value)}
                                rows={3}
                                className="input-field"
                                placeholder="Describe los hallazgos del diagnóstico..."
                            />
                            {reportForm.errors.findings && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.findings}</p>
                            )}
                        </div>

                        {/* Status change select */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Cambiar estatus de la orden a <span className="text-red-500">*</span>
                            </label>
                            <select
                                value={reportForm.data.status}
                                onChange={(e) => reportForm.setData('status', e.target.value)}
                                className="input-field"
                                required
                            >
                                <option value="">— Seleccionar nuevo estatus —</option>
                                {(statusTransitions[order.status] || []).map((transition) => (
                                    <option key={transition} value={transition}>
                                        {statusLabels[order.status]} → {statusLabels[transition]}
                                    </option>
                                ))}
                            </select>
                            {reportForm.errors.status && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.status}</p>
                            )}
                        </div>

                        {/* Parts used */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Repuestos Utilizados
                            </label>
                            <div className="space-y-2">
                                {reportForm.data.parts_used.map((part, index) => (
                                    <div key={index} className="flex items-center gap-2">
                                        <input
                                            type="text"
                                            value={part.name}
                                            onChange={(e) => updatePart(index, 'name', e.target.value)}
                                            className="input-field flex-1"
                                            placeholder="Nombre del repuesto"
                                        />
                                        <input
                                            type="number"
                                            value={part.quantity}
                                            onChange={(e) => updatePart(index, 'quantity', e.target.value)}
                                            className="input-field w-20"
                                            min="1"
                                            placeholder="Cant."
                                        />
                                        <button
                                            type="button"
                                            onClick={() => removePartRow(index)}
                                            className="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            disabled={reportForm.data.parts_used.length === 1}
                                        >
                                            <TrashIcon className="h-4 w-4" />
                                        </button>
                                    </div>
                                ))}
                            </div>
                            <button
                                type="button"
                                onClick={addPartRow}
                                className="mt-2 flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium"
                            >
                                <PlusIcon className="h-4 w-4" />
                                Agregar repuesto
                            </button>
                            {reportForm.errors.parts_used && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.parts_used}</p>
                            )}
                        </div>

                        {/* Labor hours */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Horas de Trabajo
                            </label>
                            <input
                                type="number"
                                value={reportForm.data.labor_hours}
                                onChange={(e) => reportForm.setData('labor_hours', e.target.value)}
                                className="input-field w-32"
                                step="0.5"
                                min="0"
                                placeholder="0.0"
                            />
                            {reportForm.errors.labor_hours && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.labor_hours}</p>
                            )}
                        </div>

                        {/* Notes for client */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Notas para el cliente
                            </label>
                            <textarea
                                value={reportForm.data.notes}
                                onChange={(e) => reportForm.setData('notes', e.target.value)}
                                rows={2}
                                className="input-field"
                                placeholder="Notas que serán visibles para el cliente..."
                            />
                            {reportForm.errors.notes && (
                                <p className="mt-1 text-sm text-red-600">{reportForm.errors.notes}</p>
                            )}
                        </div>

                        {/* Image upload placeholder */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Imágenes (evidencia)
                            </label>
                            <div className="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-primary-400 transition-colors cursor-pointer">
                                <PhotoIcon className="h-10 w-10 text-gray-400 mx-auto mb-2" />
                                <p className="text-sm text-gray-500">
                                    Haz clic o arrastra imágenes aquí para subirlas
                                </p>
                                <p className="text-xs text-gray-400 mt-1">
                                    PNG, JPG, WEBP — Máximo 5MB por archivo
                                </p>
                                <input
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    onChange={(e) => {
                                        if (e.target.files) {
                                            reportForm.setData('images', Array.from(e.target.files));
                                        }
                                    }}
                                    className="hidden"
                                    id="report-images"
                                />
                                <label
                                    htmlFor="report-images"
                                    className="inline-flex items-center gap-2 mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 cursor-pointer transition-colors"
                                >
                                    <ArrowUpTrayIcon className="h-4 w-4" />
                                    Seleccionar archivos
                                </label>
                            </div>
                        </div>

                        {/* Submit */}
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => {
                                    setIsReportModalOpen(false);
                                    reportForm.reset();
                                }}
                                className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                disabled={reportForm.processing}
                                className="btn-primary flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {reportForm.processing && (
                                    <div className="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                                )}
                                Guardar Reporte
                            </button>
                        </div>
                    </form>
                </Modal>

                {/* Status Update Modal */}
                <Modal
                    isOpen={isStatusModalOpen}
                    onClose={() => {
                        setIsStatusModalOpen(false);
                        statusForm.reset();
                    }}
                    title="Cambiar Estatus de la Orden"
                    size="md"
                >
                    <form onSubmit={handleStatusSubmit} className="space-y-5">
                        {/* Current status */}
                        <div className="p-4 bg-gray-50 rounded-xl">
                            <p className="text-xs font-medium text-gray-500 mb-1">Estatus Actual</p>
                            <StatusBadge
                                status={order.status}
                                label={order.status_label || statusLabels[order.status]}
                                size="md"
                            />
                        </div>

                        {/* New status */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Nuevo Estatus <span className="text-red-500">*</span>
                            </label>
                            <select
                                value={statusForm.data.status}
                                onChange={(e) => statusForm.setData('status', e.target.value)}
                                className="input-field"
                                required
                            >
                                <option value="">— Seleccionar nuevo estatus —</option>
                                {availableTransitions.map((transition) => (
                                    <option key={transition} value={transition}>
                                        {statusLabels[transition]}
                                    </option>
                                ))}
                            </select>
                            {statusForm.errors.status && (
                                <p className="mt-1 text-sm text-red-600">{statusForm.errors.status}</p>
                            )}
                        </div>

                        {/* Notes */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Notas del cambio de estatus
                            </label>
                            <textarea
                                value={statusForm.data.notes}
                                onChange={(e) => statusForm.setData('notes', e.target.value)}
                                rows={3}
                                className="input-field"
                                placeholder="Describe el motivo o contexto de este cambio..."
                            />
                            {statusForm.errors.notes && (
                                <p className="mt-1 text-sm text-red-600">{statusForm.errors.notes}</p>
                            )}
                        </div>

                        {/* Submit */}
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => {
                                    setIsStatusModalOpen(false);
                                    statusForm.reset();
                                }}
                                className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                disabled={statusForm.processing}
                                className="btn-primary flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {statusForm.processing && (
                                    <div className="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                                )}
                                Actualizar Estatus
                            </button>
                        </div>
                    </form>
                </Modal>
            </div>
        </AuthenticatedLayout>
    );
}
