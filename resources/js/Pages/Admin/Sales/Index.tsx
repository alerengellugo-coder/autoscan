import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import {
    PlusCircleIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    CurrencyDollarIcon,
    BanknotesIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Sale, PaginationData } from '../../../types';

const saleStatusLabels: Record<string, string> = {
    pending: 'Pendiente',
    paid: 'Pagada',
    partially_paid: 'Parcialmente Pagada',
    cancelled: 'Cancelada',
};

const paymentMethodLabels: Record<string, string> = {
    cash: 'Efectivo',
    card: 'Tarjeta',
    transfer: 'Transferencia',
    credit: 'Crédito',
};

const paymentMethodColors: Record<string, string> = {
    cash: 'bg-green-100 text-green-800',
    card: 'bg-blue-100 text-blue-800',
    transfer: 'bg-purple-100 text-purple-800',
    credit: 'bg-orange-100 text-orange-800',
};

interface SalesIndexProps extends PageProps {
    sales: PaginationData<Sale>;
    stats: {
        total_sales: number;
        total_revenue: number;
        pending_payment: number;
    };
    filters: {
        status?: string;
        payment_method?: string;
    };
}

export default function SalesIndex({
    sales,
    stats,
    filters,
}: SalesIndexProps) {
    const [statusFilter, setStatusFilter] = useState(filters.status || '');
    const [paymentMethodFilter, setPaymentMethodFilter] = useState(filters.payment_method || '');

    const handleFilter = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/admin/ventas', {
            status: statusFilter,
            payment_method: paymentMethodFilter,
        }, { preserveState: true });
    };

    const handleCancel = (id: number) => {
        if (confirm('¿Estás seguro de cancelar esta venta? Esta acción no se puede deshacer.')) {
            router.post(`/admin/ventas/${id}/cancelar`);
        }
    };

    const formatMoney = (amount: number) => {
        return `$${amount.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
    };

    return (
        <>
            <Head title="Ventas" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-900">
                            Ventas
                        </h1>
                        <Link
                            href="/admin/ventas/crear"
                            className="inline-flex items-center gap-2 btn-primary"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Nueva Venta
                        </Link>
                    </div>
                }
            >
                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div className="card">
                        <div className="flex items-center gap-4">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100">
                                <BanknotesIcon className="h-6 w-6 text-blue-600" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Total Ventas</p>
                                <p className="text-2xl font-bold text-gray-900">{stats.total_sales}</p>
                            </div>
                        </div>
                    </div>
                    <div className="card">
                        <div className="flex items-center gap-4">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-green-100">
                                <CurrencyDollarIcon className="h-6 w-6 text-green-600" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Total Ingresos</p>
                                <p className="text-2xl font-bold text-gray-900">{formatMoney(stats.total_revenue)}</p>
                            </div>
                        </div>
                    </div>
                    <div className="card">
                        <div className="flex items-center gap-4">
                            <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-100">
                                <MagnifyingGlassIcon className="h-6 w-6 text-yellow-600" />
                            </div>
                            <div>
                                <p className="text-sm text-gray-500">Pendientes de Pago</p>
                                <p className="text-2xl font-bold text-gray-900">{stats.pending_payment}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Filter */}
                <div className="card mb-6">
                    <form onSubmit={handleFilter} className="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <MagnifyingGlassIcon className="h-5 w-5 text-gray-400" />
                        <span className="text-sm font-medium text-gray-700">Filtrar:</span>
                        <select
                            value={statusFilter}
                            onChange={(e) => setStatusFilter(e.target.value)}
                            className="input-field px-4 py-2 text-sm w-44"
                        >
                            <option value="">Todas</option>
                            <option value="pending">Pendiente</option>
                            <option value="paid">Pagada</option>
                            <option value="partially_paid">Parcialmente Pagada</option>
                            <option value="cancelled">Cancelada</option>
                        </select>
                        <select
                            value={paymentMethodFilter}
                            onChange={(e) => setPaymentMethodFilter(e.target.value)}
                            className="input-field px-4 py-2 text-sm w-44"
                        >
                            <option value="">Todas</option>
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta</option>
                            <option value="transfer">Transferencia</option>
                            <option value="credit">Crédito</option>
                        </select>
                        <button type="submit" className="btn-primary py-2 text-sm">
                            Filtrar
                        </button>
                        {(statusFilter || paymentMethodFilter) && (
                            <Link
                                href="/admin/ventas"
                                className="text-sm text-gray-500 hover:text-gray-700 underline"
                            >
                                Limpiar filtros
                            </Link>
                        )}
                    </form>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3 text-left">Venta</th>
                                    <th className="table-header px-6 py-3 text-left">Cliente</th>
                                    <th className="table-header px-6 py-3 text-right">Total</th>
                                    <th className="table-header px-6 py-3 text-left">Método de Pago</th>
                                    <th className="table-header px-6 py-3 text-left">Estado</th>
                                    <th className="table-header px-6 py-3 text-right">Pagado / Total</th>
                                    <th className="table-header px-6 py-3 text-left">Fecha</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {sales.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={8} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron ventas.
                                        </td>
                                    </tr>
                                ) : (
                                    sales.data.map((sale) => {
                                        const paidAmount = sale.paid_amount || 0;
                                        const isFullyPaid = paidAmount >= sale.total;
                                        const paymentPercent = sale.total > 0 ? Math.min((paidAmount / sale.total) * 100, 100) : 0;

                                        return (
                                            <tr key={sale.id} className="hover:bg-gray-50 transition-colors">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {sale.sale_number}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {sale.client?.name || '—'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                                    {sale.formatted_total || formatMoney(sale.total)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    {sale.payment_method ? (
                                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${paymentMethodColors[sale.payment_method] || 'bg-gray-100 text-gray-800'}`}>
                                                            {sale.payment_method_label || paymentMethodLabels[sale.payment_method] || sale.payment_method}
                                                        </span>
                                                    ) : (
                                                        <span className="text-xs text-gray-400">—</span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <StatusBadge status={sale.status} label={sale.status_label || saleStatusLabels[sale.status] || sale.status} />
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right">
                                                    <div className="space-y-1">
                                                        <p className="text-sm font-medium text-gray-900">
                                                            {formatMoney(paidAmount)}
                                                        </p>
                                                        {!isFullyPaid && (
                                                            <div className="w-full bg-gray-200 rounded-full h-1.5">
                                                                <div
                                                                    className="h-1.5 rounded-full bg-primary-500"
                                                                    style={{ width: `${paymentPercent}%` }}
                                                                />
                                                            </div>
                                                        )}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {new Date(sale.created_at).toLocaleDateString('es-MX', {
                                                        day: '2-digit',
                                                        month: 'short',
                                                        year: 'numeric',
                                                    })}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right">
                                                    <div className="flex items-center justify-end gap-1">
                                                        <Link
                                                            href={`/admin/ventas/${sale.id}`}
                                                            className="text-primary-600 hover:text-primary-700 p-1 rounded-lg hover:bg-primary-50 transition-colors"
                                                            title="Ver"
                                                        >
                                                            <EyeIcon className="h-4 w-4" />
                                                        </Link>
                                                        {(sale.status === 'pending' || sale.status === 'partially_paid') && (
                                                            <Link
                                                                href={`/admin/ventas/${sale.id}/pago`}
                                                                className="text-green-600 hover:text-green-700 p-1 rounded-lg hover:bg-green-50 transition-colors"
                                                                title="Registrar Pago"
                                                            >
                                                                <CurrencyDollarIcon className="h-4 w-4" />
                                                            </Link>
                                                        )}
                                                        {sale.status === 'pending' && (
                                                            <button
                                                                onClick={() => handleCancel(sale.id)}
                                                                className="text-red-600 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition-colors"
                                                                title="Cancelar"
                                                            >
                                                                <XMarkIcon className="h-4 w-4" />
                                                            </button>
                                                        )}
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {sales.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {sales.from} a {sales.to} de {sales.total} resultados
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={sales.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        sales.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {sales.current_page} de {sales.last_page}
                                </span>
                                <Link
                                    href={sales.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        sales.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
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
