import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import {
    PlusCircleIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    PencilSquareIcon,
    CheckCircleIcon,
    XMarkIcon,
    ShoppingBagIcon,
    DocumentArrowDownIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Quotation, PaginationData, SelectOption } from '../../../types';

interface QuotationsIndexProps extends PageProps {
    quotations: PaginationData<Quotation>;
    status_options: SelectOption[];
    filters: {
        status?: string;
    };
}

export default function QuotationsIndex({
    quotations,
    status_options = [],
    filters,
}: QuotationsIndexProps) {
    const [statusFilter, setStatusFilter] = useState(filters.status || '');

    const handleFilter = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/admin/cotizaciones', { status: statusFilter }, { preserveState: true });
    };

    const handleAction = (id: number, action: string) => {
        if (action === 'approve') {
            router.patch(`/admin/cotizaciones/${id}/status`, { status: 'approved' });
        } else if (action === 'reject') {
            router.patch(`/admin/cotizaciones/${id}/status`, { status: 'rejected' });
        } else if (action === 'convert-to-sale') {
            router.post(`/admin/cotizaciones/${id}/convertir-venta`);
        } else if (action === 'generate-pdf') {
            window.open(`/admin/cotizaciones/${id}/pdf`, '_blank');
        }
    };

    return (
        <>
            <Head title="Cotizaciones" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-900">
                            Cotizaciones
                        </h1>
                        <Link
                            href="/admin/cotizaciones/create"
                            className="inline-flex items-center gap-2 btn-primary"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Nueva Cotización
                        </Link>
                    </div>
                }
            >
                {/* Filter */}
                <div className="card mb-6">
                    <form onSubmit={handleFilter} className="flex items-center gap-4">
                        <MagnifyingGlassIcon className="h-5 w-5 text-gray-400" />
                        <span className="text-sm font-medium text-gray-700">Filtrar por estado:</span>
                        <select
                            value={statusFilter}
                            onChange={(e) => setStatusFilter(e.target.value)}
                            className="input-field px-4 py-2 text-sm w-48"
                        >
                            <option value="">Todos</option>
                            {status_options.map((opt) => (
                                <option key={opt.value} value={opt.value}>{opt.label}</option>
                            ))}
                        </select>
                        <button type="submit" className="btn-primary py-2 text-sm">
                            Filtrar
                        </button>
                    </form>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3">Cotización</th>
                                    <th className="table-header px-6 py-3">Cliente</th>
                                    <th className="table-header px-6 py-3">Vehículo</th>
                                    <th className="table-header px-6 py-3">Total</th>
                                    <th className="table-header px-6 py-3">Estado</th>
                                    <th className="table-header px-6 py-3">Creada</th>
                                    <th className="table-header px-6 py-3">Válida Hasta</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {quotations.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={8} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron cotizaciones.
                                        </td>
                                    </tr>
                                ) : (
                                    quotations.data.map((quotation) => (
                                        <tr key={quotation.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {quotation.quotation_number}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {quotation.client?.name || '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {quotation.vehicle
                                                    ? `${quotation.vehicle.brand} ${quotation.vehicle.model}`
                                                    : '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {quotation.formatted_total || `$${quotation.total.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <StatusBadge status={quotation.status} label={quotation.status_label} />
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {new Date(quotation.created_at).toLocaleDateString('es-MX', {
                                                    day: '2-digit',
                                                    month: 'short',
                                                    year: 'numeric',
                                                })}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {quotation.valid_until
                                                    ? new Date(quotation.valid_until).toLocaleDateString('es-MX', {
                                                          day: '2-digit',
                                                          month: 'short',
                                                          year: 'numeric',
                                                      })
                                                    : '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right">
                                                <div className="flex items-center justify-end gap-1">
                                                    <Link
                                                        href={`/admin/cotizaciones/${quotation.id}`}
                                                        className="text-primary-600 hover:text-primary-700 p-1 rounded-lg hover:bg-primary-50 transition-colors"
                                                        title="Ver"
                                                    >
                                                        <EyeIcon className="h-4 w-4" />
                                                    </Link>
                                                    {quotation.status === 'draft' && (
                                                        <Link
                                                            href={`/admin/cotizaciones/${quotation.id}/edit`}
                                                            className="text-yellow-600 hover:text-yellow-700 p-1 rounded-lg hover:bg-yellow-50 transition-colors"
                                                            title="Editar"
                                                        >
                                                            <PencilSquareIcon className="h-4 w-4" />
                                                        </Link>
                                                    )}
                                                    {(quotation.status === 'pending_client' || quotation.status === 'draft') && (
                                                        <>
                                                            <button
                                                                onClick={() => handleAction(quotation.id, 'approve')}
                                                                className="text-green-600 hover:text-green-700 p-1 rounded-lg hover:bg-green-50 transition-colors"
                                                                title="Aprobar"
                                                            >
                                                                <CheckCircleIcon className="h-4 w-4" />
                                                            </button>
                                                            <button
                                                                onClick={() => handleAction(quotation.id, 'reject')}
                                                                className="text-red-600 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition-colors"
                                                                title="Rechazar"
                                                            >
                                                                <XMarkIcon className="h-4 w-4" />
                                                            </button>
                                                        </>
                                                    )}
                                                    {quotation.status === 'approved' && (
                                                        <>
                                                            <button
                                                                onClick={() => handleAction(quotation.id, 'convert-to-sale')}
                                                                className="text-purple-600 hover:text-purple-700 p-1 rounded-lg hover:bg-purple-50 transition-colors"
                                                                title="Convertir a Venta"
                                                            >
                                                                <ShoppingBagIcon className="h-4 w-4" />
                                                            </button>
                                                            <button
                                                                onClick={() => handleAction(quotation.id, 'generate-pdf')}
                                                                className="text-gray-600 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-50 transition-colors"
                                                                title="Generar PDF"
                                                            >
                                                                <DocumentArrowDownIcon className="h-4 w-4" />
                                                            </button>
                                                        </>
                                                    )}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {quotations.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {quotations.from} a {quotations.to} de {quotations.total} resultados
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={quotations.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        quotations.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {quotations.current_page} de {quotations.last_page}
                                </span>
                                <Link
                                    href={quotations.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        quotations.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
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
