import React from 'react';
import { usePage, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import EmptyState from '../../../Components/EmptyState';
import {
    ShoppingBagIcon,
    EyeIcon,
    CalendarDaysIcon,
    CurrencyDollarIcon,
    CreditCardIcon,
    CubeIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Sale } from '../../../types';

interface ClientSalesPageProps extends PageProps {
    sales: Sale[];
}

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

const paymentMethodIcons: Record<string, string> = {
    cash: '💵',
    card: '💳',
    transfer: '🏦',
    credit: '📄',
};

const formatDate = (dateStr: string): string => {
    return new Date(dateStr).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(amount);
};

export default function ClientSalesIndex() {
    const { props } = usePage<ClientSalesPageProps>();
    const { sales } = props;

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <ShoppingBagIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Mis Compras</h1>
                        <p className="text-sm text-gray-500">Historial de compras y ventas</p>
                    </div>
                </div>
            }
        >
            <div className="space-y-6">
                {sales.length === 0 ? (
                    <EmptyState
                        title="No hay compras registradas"
                        description="No tienes compras registradas. Las ventas aparecerán cuando se complete una cotización aprobada o se registre una venta directamente."
                        icon={ShoppingBagIcon}
                    />
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        {sales.map((sale) => (
                            <div
                                key={sale.id}
                                className="card hover:shadow-card-hover transition-shadow duration-200 flex flex-col"
                            >
                                {/* Card header */}
                                <div className="flex items-start justify-between mb-3">
                                    <div>
                                        <Link
                                            href={`/sales/${sale.id}`}
                                            className="text-lg font-bold text-primary-600 hover:text-primary-700 transition-colors"
                                        >
                                            {sale.sale_number}
                                        </Link>
                                        <div className="flex items-center gap-1.5 mt-1">
                                            <CalendarDaysIcon className="h-3.5 w-3.5 text-gray-400" />
                                            <span className="text-xs text-gray-500">
                                                {formatDate(sale.created_at)}
                                            </span>
                                        </div>
                                    </div>
                                    <StatusBadge
                                        status={sale.status}
                                        label={sale.status_label || saleStatusLabels[sale.status]}
                                        size="md"
                                    />
                                </div>

                                {/* Payment method */}
                                {sale.payment_method && (
                                    <div className="flex items-center gap-2 mb-3 px-3 py-2 bg-gray-50 rounded-lg">
                                        <span className="text-base">
                                            {paymentMethodIcons[sale.payment_method] || '💳'}
                                        </span>
                                        <span className="text-sm text-gray-700">
                                            {sale.payment_method_label || paymentMethodLabels[sale.payment_method]}
                                        </span>
                                    </div>
                                )}

                                {/* Totals */}
                                <div className="grid grid-cols-2 gap-3 mb-3">
                                    <div className="p-3 bg-blue-50 rounded-lg">
                                        <p className="text-xs text-blue-600 mb-0.5">Total</p>
                                        <p className="text-lg font-bold text-blue-900">
                                            {sale.formatted_total || formatCurrency(sale.total)}
                                        </p>
                                    </div>
                                    <div className="p-3 bg-green-50 rounded-lg">
                                        <p className="text-xs text-green-600 mb-0.5">Pagado</p>
                                        <p className="text-lg font-bold text-green-900">
                                            {sale.formatted_paid_amount || formatCurrency(sale.paid_amount || 0)}
                                        </p>
                                    </div>
                                </div>

                                {/* Remaining balance */}
                                {sale.remaining_amount != null && sale.remaining_amount > 0 && (
                                    <div className="mb-3 px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p className="text-xs text-yellow-700">
                                            Saldo pendiente:{' '}
                                            <span className="font-bold">
                                                {sale.formatted_remaining_amount || formatCurrency(sale.remaining_amount)}
                                            </span>
                                        </p>
                                    </div>
                                )}

                                {/* Items count */}
                                <div className="flex items-center gap-1.5 text-sm text-gray-500 mb-4">
                                    <CubeIcon className="h-4 w-4" />
                                    <span>{sale.item_count || 0} artículo(s)</span>
                                </div>

                                {/* Spacer */}
                                <div className="flex-1" />

                                {/* Action */}
                                <div className="pt-4 border-t border-gray-100">
                                    <Link
                                        href={`/sales/${sale.id}`}
                                        className="flex items-center justify-center gap-1.5 w-full px-3 py-2 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium hover:bg-primary-100 transition-colors"
                                    >
                                        <EyeIcon className="h-4 w-4" />
                                        Ver Detalles
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
