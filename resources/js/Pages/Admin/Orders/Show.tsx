import React, { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import Modal from '../../../Components/Modal';
import {
    ArrowLeftIcon,
    TruckIcon,
    UserIcon,
    WrenchScrewdriverIcon,
    ClipboardDocumentListIcon,
    DocumentTextIcon,
    PlusCircleIcon,
    XMarkIcon,
    DocumentArrowDownIcon,
    ExclamationCircleIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, ServiceReport, SelectOption } from '../../../types';

interface OrdersShowProps extends PageProps {
    order: ServiceOrder;
    status_timeline: {
        status: string;
        label: string;
        date: string;
        user_name: string;
    }[];
    reports: ServiceReport[];
    status_options: SelectOption[];
}

export default function OrdersShow({
    order,
    status_timeline,
    reports,
    status_options,
}: OrdersShowProps) {
    const [showStatusModal, setShowStatusModal] = useState(false);
    const { data, setData, put, processing, reset } = useForm({
        status: order.status,
    });

    const handleStatusUpdate = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/orders/${order.id}/status`, {
            onSuccess: () => {
                setShowStatusModal(false);
                reset();
            },
        });
    };

    const handleCancelOrder = () => {
        if (confirm('¿Estás seguro de cancelar esta orden de servicio? Esta acción no se puede deshacer.')) {
            router.put(`/orders/${order.id}/status`, { status: 'cancelled' });
        }
    };

    const formatDate = (date: string | null) => {
        if (!date) return '—';
        return new Date(date).toLocaleDateString('es-MX', {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    return (
        <>
            <Head title={`Orden ${order.order_number}`} />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/orders"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Orden {order.order_number}
                        </h1>
                    </div>
                }
            >
                {/* Order Header */}
                <div className="card mb-6">
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div className="flex items-center gap-4">
                            <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-primary-100">
                                <ClipboardDocumentListIcon className="h-7 w-7 text-primary-600" />
                            </div>
                            <div>
                                <h2 className="text-xl font-bold text-gray-900">{order.order_number}</h2>
                                <p className="text-sm text-gray-500">
                                    Creada el {formatDate(order.created_at)}
                                </p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 flex-wrap">
                            <StatusBadge status={order.status} label={order.status_label} size="md" />
                            <span
                                className={`inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium ${
                                    order.priority === 'high'
                                        ? 'bg-red-100 text-red-800'
                                        : order.priority === 'medium'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-gray-100 text-gray-800'
                                }`}
                            >
                                Prioridad: {order.priority_label || order.priority}
                            </span>
                        </div>
                    </div>

                    {/* Info Grid */}
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-200">
                        {/* Vehicle */}
                        <div className="flex items-start gap-3">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100">
                                <TruckIcon className="h-5 w-5 text-blue-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</p>
                                <Link
                                    href={`/vehicles/${order.vehicle?.id}`}
                                    className="text-sm font-semibold text-gray-900 hover:text-primary-600"
                                >
                                    {order.vehicle?.brand} {order.vehicle?.model}
                                </Link>
                                <p className="text-xs text-gray-500">
                                    Placa: {order.vehicle?.plate || '—'}
                                </p>
                            </div>
                        </div>
                        {/* Client */}
                        <div className="flex items-start gap-3">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100">
                                <UserIcon className="h-5 w-5 text-green-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</p>
                                <p className="text-sm font-semibold text-gray-900">{order.client?.name || '—'}</p>
                                <p className="text-xs text-gray-500">{order.client?.phone || order.client?.email || '—'}</p>
                            </div>
                        </div>
                        {/* Technician */}
                        <div className="flex items-start gap-3">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-100">
                                <WrenchScrewdriverIcon className="h-5 w-5 text-orange-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</p>
                                <p className="text-sm font-semibold text-gray-900">{order.technician?.name || 'Sin asignar'}</p>
                                <p className="text-xs text-gray-500">
                                    {order.technician?.email || '—'}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left Column */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Order Details */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Detalles de la Orden
                            </h3>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p className="text-xs font-medium text-gray-500 mb-1">Tipo de Servicio</p>
                                    <p className="text-sm text-gray-900">{order.service_type_label || order.service_type}</p>
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 mb-1">Descripción</p>
                                    <p className="text-sm text-gray-900">{order.description || '—'}</p>
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 mb-1">Diagnóstico</p>
                                    <p className="text-sm text-gray-900">{order.diagnosis || 'Sin diagnóstico'}</p>
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 mb-1">Fecha Estimada</p>
                                    <p className="text-sm text-gray-900">
                                        {order.estimated_completion_date
                                            ? new Date(order.estimated_completion_date).toLocaleDateString('es-MX', {
                                                  day: '2-digit',
                                                  month: 'long',
                                                  year: 'numeric',
                                              })
                                            : '—'}
                                    </p>
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 mb-1">Costo Estimado</p>
                                    <p className="text-sm font-semibold text-gray-900">
                                        {order.formatted_estimated_cost || (order.estimated_cost ? `$${order.estimated_cost.toLocaleString('es-MX', { minimumFractionDigits: 2 })}` : '—')}
                                    </p>
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 mb-1">Costo Actual</p>
                                    <p className="text-sm font-semibold text-gray-900">
                                        {order.formatted_actual_cost || (order.actual_cost ? `$${order.actual_cost.toLocaleString('es-MX', { minimumFractionDigits: 2 })}` : '—')}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Reports */}
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 className="text-lg font-semibold text-gray-900">
                                    Reportes de Servicio ({reports.length})
                                </h3>
                                <Link
                                    href={`/orders/${order.id}/reports/create`}
                                    className="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700"
                                >
                                    <PlusCircleIcon className="h-4 w-4" />
                                    Agregar Reporte
                                </Link>
                            </div>
                            {reports.length === 0 ? (
                                <div className="p-8 text-center text-gray-500 text-sm">
                                    No hay reportes de servicio registrados.
                                </div>
                            ) : (
                                <div className="divide-y divide-gray-100">
                                    {reports.map((report) => (
                                        <div key={report.id} className="px-6 py-4 hover:bg-gray-50 transition-colors">
                                            <div className="flex items-center justify-between mb-2">
                                                <div className="flex items-center gap-2">
                                                    <DocumentTextIcon className="h-4 w-4 text-gray-400" />
                                                    <h4 className="text-sm font-medium text-gray-900">
                                                        Reporte del {new Date(report.report_date || report.created_at).toLocaleDateString('es-MX', {
                                                            day: '2-digit',
                                                            month: 'long',
                                                        })}
                                                    </h4>
                                                </div>
                                                <span className="text-xs text-gray-500">
                                                    {report.technician?.name || '—'}
                                                </span>
                                            </div>
                                            <p className="text-sm text-gray-600 line-clamp-2">
                                                {report.findings || report.description || 'Sin hallazgos registrados'}
                                            </p>
                                            {report.labor_hours && (
                                                <p className="text-xs text-gray-500 mt-1">
                                                    Horas de trabajo: {report.labor_hours}h
                                                </p>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Right Column - Timeline & Actions */}
                    <div className="space-y-6">
                        {/* Status Timeline */}
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200">
                                <h3 className="text-lg font-semibold text-gray-900">
                                    Historial de Estado
                                </h3>
                            </div>
                            <div className="p-6">
                                {status_timeline.length === 0 ? (
                                    <p className="text-sm text-gray-500 text-center">
                                        No hay cambios de estado registrados.
                                    </p>
                                ) : (
                                    <div className="relative">
                                        {/* Vertical line */}
                                        <div className="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200" />

                                        <div className="space-y-6">
                                            {status_timeline.map((entry, index) => (
                                                <div key={index} className="relative pl-10">
                                                    {/* Dot */}
                                                    <div
                                                        className={`absolute left-2 top-1 h-5 w-5 rounded-full border-2 ${
                                                            index === status_timeline.length - 1
                                                                ? 'bg-primary-600 border-primary-600'
                                                                : 'bg-white border-gray-300'
                                                        }`}
                                                    />
                                                    <div>
                                                        <p className="text-sm font-medium text-gray-900">
                                                            {entry.label}
                                                        </p>
                                                        <p className="text-xs text-gray-500">
                                                            {formatDate(entry.date)}
                                                        </p>
                                                        <p className="text-xs text-gray-400 mt-0.5">
                                                            Por: {entry.user_name}
                                                        </p>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Acciones
                            </h3>
                            <div className="space-y-3">
                                {order.status !== 'cancelled' && order.status !== 'delivered' && (
                                    <button
                                        onClick={() => setShowStatusModal(true)}
                                        className="w-full btn-primary py-2.5 flex items-center justify-center gap-2"
                                    >
                                        <WrenchScrewdriverIcon className="h-4 w-4" />
                                        Actualizar Estado
                                    </button>
                                )}
                                <Link
                                    href={`/orders/${order.id}/reports/create`}
                                    className="w-full btn-secondary py-2.5 flex items-center justify-center gap-2"
                                >
                                    <DocumentTextIcon className="h-4 w-4" />
                                    Agregar Reporte
                                </Link>
                                {order.status !== 'cancelled' && order.status !== 'delivered' && order.status !== 'completed' && (
                                    <button
                                        onClick={handleCancelOrder}
                                        className="w-full px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors flex items-center justify-center gap-2"
                                    >
                                        <ExclamationCircleIcon className="h-4 w-4" />
                                        Cancelar Orden
                                    </button>
                                )}
                                <Link
                                    href={`/orders/${order.id}/pdf`}
                                    className="w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2"
                                >
                                    <DocumentArrowDownIcon className="h-4 w-4" />
                                    Generar PDF
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Status Update Modal */}
                <Modal
                    isOpen={showStatusModal}
                    onClose={() => setShowStatusModal(false)}
                    title="Actualizar Estado"
                >
                    <form onSubmit={handleStatusUpdate} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Nuevo Estado
                            </label>
                            <select
                                value={data.status}
                                onChange={(e) => setData('status', e.target.value)}
                                className="input-field px-4 py-2.5"
                            >
                                {status_options.map((opt) => (
                                    <option key={opt.value} value={opt.value}>
                                        {opt.label}
                                    </option>
                                ))}
                            </select>
                        </div>
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => setShowStatusModal(false)}
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                disabled={processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Actualizando...' : 'Actualizar'}
                            </button>
                        </div>
                    </form>
                </Modal>
            </AuthenticatedLayout>
        </>
    );
}
