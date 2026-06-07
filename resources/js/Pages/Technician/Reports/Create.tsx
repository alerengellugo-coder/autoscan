import React, { useState } from 'react';
import { usePage, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import {
    DocumentTextIcon,
    PlusIcon,
    TrashIcon,
    PhotoIcon,
    ArrowUpTrayIcon,
    ClipboardDocumentListIcon,
    XMarkIcon,
    ArrowPathIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceOrder, PartUsed, SelectOption } from '../../../types';

interface TechnicianReportCreateProps extends PageProps {
    orders: ServiceOrder[];
    status_transitions?: Record<string, string[]>;
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

const defaultTransitions: Record<string, string[]> = {
    pending: ['diagnosing'],
    diagnosing: ['in_progress', 'waiting_parts'],
    in_progress: ['quality_check', 'waiting_parts'],
    waiting_parts: ['in_progress'],
    quality_check: ['completed', 'in_progress'],
    completed: ['delivered'],
};

export default function TechnicianReportCreate() {
    const { props } = usePage<TechnicianReportCreateProps>();
    const { orders, status_transitions } = props;

    const transitions = status_transitions || defaultTransitions;

    const form = useForm({
        service_order_id: '',
        title: '',
        description: '',
        findings: '',
        previous_status: '',
        new_status: '',
        parts_used: [{ name: '', quantity: 1 }] as PartUsed[],
        labor_hours: '',
        notes: '',
        images: [] as File[],
    });

    const selectedOrder = orders.find((o) => String(o.id) === form.data.service_order_id);

    // Auto-fill previous status when order changes
    React.useEffect(() => {
        if (selectedOrder) {
            form.setData('previous_status', selectedOrder.status);
            form.setData('new_status', '');
        }
    }, [form.data.service_order_id]);

    const availableTransitions = selectedOrder
        ? transitions[selectedOrder.status] || []
        : [];

    const addPartRow = () => {
        form.setData('parts_used', [...form.data.parts_used, { name: '', quantity: 1 }]);
    };

    const removePartRow = (index: number) => {
        const updated = form.data.parts_used.filter((_, i) => i !== index);
        form.setData('parts_used', updated);
    };

    const updatePart = (index: number, field: keyof PartUsed, value: string | number) => {
        const updated = [...form.data.parts_used];
        if (field === 'quantity') {
            updated[index] = { ...updated[index], quantity: Number(value) };
        } else {
            updated[index] = { ...updated[index], [field]: value };
        }
        form.setData('parts_used', updated);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post('/tecnico/reportes', {
            onSuccess: () => {
                router.visit('/tecnico/ordenes');
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
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100">
                        <DocumentTextIcon className="h-6 w-6 text-indigo-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Crear Reporte de Servicio</h1>
                        <p className="text-sm text-gray-500">Documenta el avance y hallazgos</p>
                    </div>
                </div>
            }
        >
            <div className="max-w-4xl mx-auto space-y-6">
                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Select Service Order */}
                    <div className="card">
                        <div className="flex items-center gap-2 mb-4">
                            <ClipboardDocumentListIcon className="h-5 w-5 text-gray-400" />
                            <h3 className="text-base font-semibold text-gray-900">Orden de Servicio</h3>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Orden de Servicio <span className="text-red-500">*</span>
                            </label>
                            <select
                                value={form.data.service_order_id}
                                onChange={(e) => form.setData('service_order_id', e.target.value)}
                                className="input-field"
                                required
                            >
                                <option value="">— Seleccionar orden —</option>
                                {orders.map((order) => (
                                    <option key={order.id} value={order.id}>
                                        {order.order_number} — {order.vehicle?.brand}{' '}
                                        {order.vehicle?.model} ({order.vehicle?.plate}) —{' '}
                                        {statusLabels[order.status]}
                                    </option>
                                ))}
                            </select>
                            {form.errors.service_order_id && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.service_order_id}</p>
                            )}
                        </div>

                        {/* Previous status (auto-filled, read only) */}
                        {selectedOrder && (
                            <div className="mt-4 p-4 bg-gray-50 rounded-xl">
                                <p className="text-xs font-medium text-gray-500 mb-1">Estatus Actual de la Orden</p>
                                <span className="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    {statusLabels[selectedOrder.status]}
                                </span>
                            </div>
                        )}
                    </div>

                    {/* Title and Description */}
                    <div className="card">
                        <h3 className="text-base font-semibold text-gray-900 mb-4">Información del Reporte</h3>

                        {/* Title */}
                        <div className="mb-4">
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Título <span className="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={form.data.title}
                                onChange={(e) => form.setData('title', e.target.value)}
                                className="input-field"
                                placeholder="Ej: Diagnóstico inicial completado"
                                required
                            />
                            {form.errors.title && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.title}</p>
                            )}
                        </div>

                        {/* Description (rich text area placeholder) */}
                        <div className="mb-4">
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Descripción
                            </label>
                            <textarea
                                value={form.data.description}
                                onChange={(e) => form.setData('description', e.target.value)}
                                rows={4}
                                className="input-field"
                                placeholder="Describe el trabajo realizado y los detalles del servicio..."
                            />
                            {form.errors.description && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.description}</p>
                            )}
                        </div>

                        {/* Findings */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Hallazgos
                            </label>
                            <textarea
                                value={form.data.findings}
                                onChange={(e) => form.setData('findings', e.target.value)}
                                rows={4}
                                className="input-field"
                                placeholder="Describe los hallazgos técnicos, problemas detectados, etc..."
                            />
                            {form.errors.findings && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.findings}</p>
                            )}
                        </div>
                    </div>

                    {/* Status change */}
                    <div className="card">
                        <div className="flex items-center gap-2 mb-4">
                            <ArrowPathIcon className="h-5 w-5 text-gray-400" />
                            <h3 className="text-base font-semibold text-gray-900">Cambio de Estatus</h3>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Nuevo Estatus de la Orden <span className="text-red-500">*</span>
                            </label>
                            <select
                                value={form.data.new_status}
                                onChange={(e) => form.setData('new_status', e.target.value)}
                                className="input-field"
                                required
                                disabled={!selectedOrder || availableTransitions.length === 0}
                            >
                                <option value="">
                                    {selectedOrder
                                        ? availableTransitions.length > 0
                                            ? '— Seleccionar nuevo estatus —'
                                            : 'No hay transiciones disponibles'
                                        : 'Selecciona una orden primero'}
                                </option>
                                {availableTransitions.map((transition) => (
                                    <option key={transition} value={transition}>
                                        {statusLabels[selectedOrder?.status || '']} → {statusLabels[transition]}
                                    </option>
                                ))}
                            </select>
                            {form.errors.new_status && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.new_status}</p>
                            )}
                        </div>
                    </div>

                    {/* Parts Used */}
                    <div className="card">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="text-base font-semibold text-gray-900">Repuestos Utilizados</h3>
                            <button
                                type="button"
                                onClick={addPartRow}
                                className="flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium"
                            >
                                <PlusIcon className="h-4 w-4" />
                                Agregar fila
                            </button>
                        </div>

                        {/* Dynamic table */}
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b border-gray-200">
                                        <th className="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2 pr-2">
                                            Nombre
                                        </th>
                                        <th className="text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2 px-2 w-24">
                                            Cantidad
                                        </th>
                                        <th className="w-10" />
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100">
                                    {form.data.parts_used.map((part, index) => (
                                        <tr key={index}>
                                            <td className="py-2 pr-2">
                                                <input
                                                    type="text"
                                                    value={part.name}
                                                    onChange={(e) => updatePart(index, 'name', e.target.value)}
                                                    className="input-field"
                                                    placeholder="Nombre del repuesto"
                                                />
                                            </td>
                                            <td className="py-2 px-2">
                                                <input
                                                    type="number"
                                                    value={part.quantity}
                                                    onChange={(e) => updatePart(index, 'quantity', e.target.value)}
                                                    className="input-field"
                                                    min="1"
                                                    placeholder="Cant."
                                                />
                                            </td>
                                            <td className="py-2 pl-2">
                                                <button
                                                    type="button"
                                                    onClick={() => removePartRow(index)}
                                                    className="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                    disabled={form.data.parts_used.length === 1}
                                                >
                                                    <TrashIcon className="h-4 w-4" />
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                        {form.errors.parts_used && (
                            <p className="mt-2 text-sm text-red-600">{form.errors.parts_used}</p>
                        )}
                    </div>

                    {/* Labor Hours and Notes */}
                    <div className="card">
                        <h3 className="text-base font-semibold text-gray-900 mb-4">Horas y Notas</h3>

                        {/* Labor hours */}
                        <div className="mb-4">
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Horas de Trabajo
                            </label>
                            <input
                                type="number"
                                value={form.data.labor_hours}
                                onChange={(e) => form.setData('labor_hours', e.target.value)}
                                className="input-field w-40"
                                step="0.5"
                                min="0"
                                placeholder="0.0"
                            />
                            <p className="text-xs text-gray-400 mt-1">
                                Puedes usar decimales (ej: 2.5)
                            </p>
                            {form.errors.labor_hours && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.labor_hours}</p>
                            )}
                        </div>

                        {/* Notes for client */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                <span className="flex items-center gap-1.5">
                                    Notas para el cliente
                                    <span className="inline-flex items-center px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded text-[10px] font-medium">
                                        VISIBLE PARA EL CLIENTE
                                    </span>
                                </span>
                            </label>
                            <textarea
                                value={form.data.notes}
                                onChange={(e) => form.setData('notes', e.target.value)}
                                rows={3}
                                className="input-field"
                                placeholder="Notas y recomendaciones que serán visibles para el cliente..."
                            />
                            {form.errors.notes && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.notes}</p>
                            )}
                        </div>
                    </div>

                    {/* Image uploads */}
                    <div className="card">
                        <div className="flex items-center gap-2 mb-4">
                            <PhotoIcon className="h-5 w-5 text-gray-400" />
                            <h3 className="text-base font-semibold text-gray-900">Imágenes / Evidencia</h3>
                        </div>

                        <div className="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
                            <PhotoIcon className="h-12 w-12 text-gray-400 mx-auto mb-3" />
                            <p className="text-sm text-gray-600 font-medium">
                                Haz clic o arrastra imágenes aquí
                            </p>
                            <p className="text-xs text-gray-400 mt-1">
                                PNG, JPG, WEBP — Máximo 5MB por archivo — Múltiples archivos permitidos
                            </p>
                            <input
                                type="file"
                                multiple
                                accept="image/*"
                                onChange={(e) => {
                                    if (e.target.files) {
                                        form.setData('images', Array.from(e.target.files));
                                    }
                                }}
                                className="hidden"
                                id="standalone-report-images"
                            />
                            <label
                                htmlFor="standalone-report-images"
                                className="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium hover:bg-primary-100 cursor-pointer transition-colors"
                            >
                                <ArrowUpTrayIcon className="h-4 w-4" />
                                Seleccionar archivos
                            </label>
                        </div>

                        {/* Show selected files */}
                        {form.data.images.length > 0 && (
                            <div className="mt-4 space-y-2">
                                {Array.from(form.data.images).map((file, index) => (
                                    <div
                                        key={index}
                                        className="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg"
                                    >
                                        <span className="text-sm text-gray-700 truncate">{file.name}</span>
                                        <span className="text-xs text-gray-400 ml-2">
                                            {(file.size / 1024).toFixed(1)} KB
                                        </span>
                                    </div>
                                ))}
                            </div>
                        )}
                        {form.errors.images && (
                            <p className="mt-2 text-sm text-red-600">{form.errors.images}</p>
                        )}
                    </div>

                    {/* Submit / Cancel */}
                    <div className="flex items-center justify-end gap-3">
                        <Link
                            href="/tecnico/ordenes"
                            className="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            disabled={form.processing}
                            className="btn-primary flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-2.5"
                        >
                            {form.processing && (
                                <div className="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                            )}
                            <DocumentTextIcon className="h-4 w-4" />
                            Crear Reporte
                        </button>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
