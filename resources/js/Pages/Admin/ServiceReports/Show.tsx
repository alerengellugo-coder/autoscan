import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import {
    ArrowLeftIcon,
    CalendarIcon,
    UserCircleIcon,
    ClipboardDocumentListIcon,
    WrenchScrewdriverIcon,
    DocumentTextIcon,
    ClockIcon,
    ExclamationTriangleIcon,
    LightBulbIcon,
    CubeIcon,
    TrashIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceReport } from '../../../types';

interface Props extends PageProps {
    report: ServiceReport;
}

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

function formatDateTime(date: string): string {
    return new Date(date).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}

export default function AdminServiceReportsShow({ report }: Props) {
    const handleDelete = () => {
        if (confirm('¿Deseas eliminar este reporte de servicio?')) {
            router.delete(`/admin/reportes-servicio/${report.id}`);
        }
    };

    return (
        <>
            <Head title={`Reporte #${report.id}`} />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-4">
                            <Link
                                href="/admin/reportes-servicio"
                                className="text-gray-500 hover:text-gray-700 transition-colors"
                            >
                                <ArrowLeftIcon className="h-5 w-5" />
                            </Link>
                            <h1 className="text-xl font-semibold text-gray-900">
                                Reporte de Servicio #{report.id}
                            </h1>
                        </div>
                        <button
                            onClick={handleDelete}
                            className="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                        >
                            <TrashIcon className="h-4 w-4" />
                            Eliminar
                        </button>
                    </div>
                }
            >
                {/* Report Header */}
                <div className="card mb-6">
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div className="flex items-center gap-4">
                            <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-primary-100">
                                <DocumentTextIcon className="h-7 w-7 text-primary-600" />
                            </div>
                            <div>
                                <h2 className="text-xl font-bold text-gray-900">Reporte #{report.id}</h2>
                                <p className="text-sm text-gray-500">
                                    Fecha: {formatDate(report.report_date)}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Service Order & Technician Info */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 pt-6 border-t border-gray-200">
                        {/* Service Order */}
                        <div className="flex items-start gap-3">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-100">
                                <ClipboardDocumentListIcon className="h-5 w-5 text-purple-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Orden de Servicio</p>
                                {report.service_order ? (
                                    <>
                                        <Link
                                            href={`/admin/ordenes/${report.service_order_id}`}
                                            className="text-sm font-semibold text-primary-600 hover:text-primary-700"
                                        >
                                            {report.service_order.order_number}
                                        </Link>
                                        {report.service_order.vehicle && (
                                            <p className="text-xs text-gray-500 mt-0.5">
                                                {report.service_order.vehicle.brand} {report.service_order.vehicle.model} {report.service_order.vehicle.year}
                                            </p>
                                        )}
                                    </>
                                ) : (
                                    <p className="text-sm text-gray-500">Orden #{report.service_order_id}</p>
                                )}
                            </div>
                        </div>

                        {/* Technician */}
                        <div className="flex items-start gap-3">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-100">
                                <UserCircleIcon className="h-5 w-5 text-primary-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</p>
                                <p className="text-sm font-semibold text-gray-900">{report.technician?.name || '—'}</p>
                                <p className="text-xs text-gray-500">{report.technician?.email || ''}</p>
                            </div>
                        </div>

                        {/* Labor Hours */}
                        {report.labor_hours != null && (
                            <div className="flex items-start gap-3">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100">
                                    <ClockIcon className="h-5 w-5 text-amber-600" />
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Horas de Trabajo</p>
                                    <p className="text-sm font-semibold text-gray-900">{report.labor_hours} horas</p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Description */}
                    {report.description && (
                        <div className="card">
                            <div className="flex items-center gap-2 mb-4">
                                <DocumentTextIcon className="h-5 w-5 text-primary-600" />
                                <h3 className="text-lg font-semibold text-gray-900">Descripción</h3>
                            </div>
                            <p className="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{report.description}</p>
                        </div>
                    )}

                    {/* Work Performed */}
                    {report.work_performed && (
                        <div className="card">
                            <div className="flex items-center gap-2 mb-4">
                                <WrenchScrewdriverIcon className="h-5 w-5 text-green-600" />
                                <h3 className="text-lg font-semibold text-gray-900">Trabajo Realizado</h3>
                            </div>
                            <p className="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{report.work_performed}</p>
                        </div>
                    )}

                    {/* Findings */}
                    {report.findings && (
                        <div className="card">
                            <div className="flex items-center gap-2 mb-4">
                                <ExclamationTriangleIcon className="h-5 w-5 text-amber-600" />
                                <h3 className="text-lg font-semibold text-gray-900">Hallazgos</h3>
                            </div>
                            <p className="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{report.findings}</p>
                        </div>
                    )}

                    {/* Recommendations */}
                    {report.recommendations && (
                        <div className="card">
                            <div className="flex items-center gap-2 mb-4">
                                <LightBulbIcon className="h-5 w-5 text-yellow-500" />
                                <h3 className="text-lg font-semibold text-gray-900">Recomendaciones</h3>
                            </div>
                            <p className="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{report.recommendations}</p>
                        </div>
                    )}
                </div>

                {/* Parts Used */}
                {report.parts_used && report.parts_used.length > 0 && (
                    <div className="card mt-6">
                        <div className="flex items-center gap-2 mb-4">
                            <CubeIcon className="h-5 w-5 text-primary-600" />
                            <h3 className="text-lg font-semibold text-gray-900">Piezas Utilizadas</h3>
                            <span className="badge badge-blue ml-2">{report.parts_used.length}</span>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th className="table-header px-6 py-3">Nombre</th>
                                        <th className="table-header px-6 py-3">Cantidad</th>
                                        <th className="table-header px-6 py-3">No. Parte</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {report.parts_used.map((part, i) => (
                                        <tr key={i} className="hover:bg-gray-50">
                                            <td className="px-6 py-3 text-sm font-medium text-gray-900">{part.name}</td>
                                            <td className="px-6 py-3 text-sm text-gray-600">{part.quantity}</td>
                                            <td className="px-6 py-3 text-sm text-gray-500">{part.part_number || '—'}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}

                {/* Notes */}
                {report.notes && (
                    <div className="card mt-6">
                        <h3 className="text-lg font-semibold text-gray-900 mb-2">Notas Adicionales</h3>
                        <p className="text-sm text-gray-600 whitespace-pre-wrap">{report.notes}</p>
                    </div>
                )}

                {/* Timestamps */}
                <div className="card mt-6">
                    <h3 className="text-sm font-semibold text-gray-900 mb-3">Registro</h3>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div className="flex items-center gap-2 text-sm text-gray-500">
                            <CalendarIcon className="h-4 w-4" />
                            <span>Creado: {formatDateTime(report.created_at)}</span>
                        </div>
                        <div className="flex items-center gap-2 text-sm text-gray-500">
                            <CalendarIcon className="h-4 w-4" />
                            <span>Actualizado: {formatDateTime(report.updated_at)}</span>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
