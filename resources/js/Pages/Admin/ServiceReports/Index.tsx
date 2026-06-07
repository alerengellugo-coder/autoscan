import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import {
    EyeIcon,
    TrashIcon,
    DocumentTextIcon,
    ClipboardDocumentListIcon,
    MagnifyingGlassIcon,
    UserCircleIcon,
    ExclamationCircleIcon,
} from '@heroicons/react/24/outline';
import { PageProps, ServiceReport } from '../../../types';

interface Props extends PageProps {
    reports: {
        data: ServiceReport[];
        current_page: number;
        last_page: number;
        total: number;
        from: number;
        to: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    filters: {
        search?: string;
        service_order_id?: string;
        per_page?: string;
    };
}

function formatDate(date: string): string {
    return new Date(date).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

function truncateText(text: string, maxLen: number): string {
    if (!text) return '—';
    return text.length > maxLen ? text.slice(0, maxLen) + '...' : text;
}

export default function AdminServiceReportsIndex({ reports, filters }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [orderId, setOrderId] = useState(filters.service_order_id || '');

    const handleFilter = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/admin/reportes-servicio', {
            search: search || undefined,
            service_order_id: orderId || undefined,
        }, { preserveState: true });
    };

    const handleDelete = (id: number) => {
        if (confirm('¿Deseas eliminar este reporte de servicio?')) {
            router.delete(`/admin/reportes-servicio/${id}`);
        }
    };

    return (
        <>
            <Head title="Reportes de Servicio" />
            <AuthenticatedLayout header={<h1 className="text-xl font-semibold text-gray-900">Reportes de Servicio</h1>}>
                {/* Filter */}
                <div className="card mb-6">
                    <form onSubmit={handleFilter} className="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div className="relative flex-1 w-full">
                            <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Buscar por descripción o trabajo..."
                                className="input-field pl-10 pr-4 py-2 text-sm"
                            />
                        </div>
                        <div className="flex items-center gap-3">
                            <input
                                type="number"
                                value={orderId}
                                onChange={(e) => setOrderId(e.target.value)}
                                placeholder="ID Orden"
                                className="input-field px-4 py-2 text-sm w-32"
                                min="1"
                            />
                            <button type="submit" className="btn-primary py-2 text-sm">
                                Filtrar
                            </button>
                            {(search || orderId) && (
                                <Link
                                    href="/admin/reportes-servicio"
                                    className="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors"
                                >
                                    Limpiar
                                </Link>
                            )}
                        </div>
                    </form>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3">ID</th>
                                    <th className="table-header px-6 py-3">Orden de Servicio</th>
                                    <th className="table-header px-6 py-3">Vehículo</th>
                                    <th className="table-header px-6 py-3">Técnico</th>
                                    <th className="table-header px-6 py-3">Descripción</th>
                                    <th className="table-header px-6 py-3">Fecha</th>
                                    <th className="table-header px-6 py-3">Horas</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {reports.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={8} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron reportes de servicio.
                                        </td>
                                    </tr>
                                ) : (
                                    reports.data.map((report) => (
                                        <tr key={report.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{report.id}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {report.service_order ? (
                                                    <Link
                                                        href={`/admin/ordenes/${report.service_order_id}`}
                                                        className="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700"
                                                    >
                                                        <ClipboardDocumentListIcon className="h-4 w-4" />
                                                        {report.service_order.order_number}
                                                    </Link>
                                                ) : (
                                                    <span className="text-sm text-gray-500">Orden #{report.service_order_id}</span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {report.service_order?.vehicle
                                                    ? `${report.service_order.vehicle.brand} ${report.service_order.vehicle.model} ${report.service_order.vehicle.year}`
                                                    : '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center gap-2">
                                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700 text-xs font-bold">
                                                        {report.technician?.initials || report.technician?.name?.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2) || '—'}
                                                    </div>
                                                    <span className="text-sm text-gray-700">{report.technician?.name || '—'}</span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                                {truncateText(report.description || '', 80)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {formatDate(report.report_date)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {report.labor_hours != null ? `${report.labor_hours}h` : '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right">
                                                <div className="flex items-center justify-end gap-1">
                                                    <Link
                                                        href={`/admin/reportes-servicio/${report.id}`}
                                                        className="text-primary-600 hover:text-primary-700 p-1.5 rounded-lg hover:bg-primary-50 transition-colors"
                                                        title="Ver"
                                                    >
                                                        <EyeIcon className="h-4 w-4" />
                                                    </Link>
                                                    <button
                                                        onClick={() => handleDelete(report.id)}
                                                        className="text-red-600 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors"
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
                    {reports.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {reports.from} a {reports.to} de {reports.total} reportes
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={reports.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        reports.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {reports.current_page} de {reports.last_page}
                                </span>
                                <Link
                                    href={reports.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        reports.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
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
