import React, { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import Modal from '../../../Components/Modal';
import {
    ArrowLeftIcon,
    UserIcon,
    CurrencyDollarIcon,
    XMarkIcon,
    ExclamationCircleIcon,
    DocumentTextIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Sale } from '../../../types';

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

interface SalesShowProps extends PageProps {
    sale: Sale;
}

export default function SalesShow({ sale }: SalesShowProps) {
    const [showPaymentModal, setShowPaymentModal] = useState(false);
    const [showCancelConfirm, setShowCancelConfirm] = useState(false);

    const { data, setData, post, processing, reset, errors } = useForm({
        amount: '',
        payment_method: '',
        notes: '',
    });

    const paidAmount = sale.paid_amount || 0;
    const total = sale.total;
    const remaining = Math.max(total - paidAmount, 0);
    const paymentPercent = total > 0 ? Math.min((paidAmount / total) * 100, 100) : 0;
    const isFullyPaid = paidAmount >= total;

    const handlePaymentSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(`/admin/ventas/${sale.id}/payments`, {
            onSuccess: () => {
                setShowPaymentModal(false);
                reset();
            },
        });
    };

    const handleCancelSale = () => {
        setShowCancelConfirm(false);
        router.put(`/admin/ventas/${sale.id}/cancel`);
    };

    const formatMoney = (amount: number) => {
        return `$${amount.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
    };

    const formatDate = (date: string) => {
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
            <Head title={`Venta ${sale.sale_number}`} />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/ventas"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Venta {sale.sale_number}
                        </h1>
                    </div>
                }
            >
                {/* Sale Header */}
                <div className="card mb-6">
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div className="flex items-center gap-4">
                            <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100">
                                <DocumentTextIcon className="h-7 w-7 text-green-600" />
                            </div>
                            <div>
                                <h2 className="text-xl font-bold text-gray-900">{sale.sale_number}</h2>
                                <p className="text-sm text-gray-500">
                                    Creada el {formatDate(sale.created_at)}
                                </p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3 flex-wrap">
                            <StatusBadge
                                status={sale.status}
                                label={sale.status_label || saleStatusLabels[sale.status] || sale.status}
                                size="md"
                            />
                        </div>
                    </div>

                    {/* Client Info */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-200">
                        <div className="flex items-start gap-3">
                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100">
                                <UserIcon className="h-5 w-5 text-blue-600" />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</p>
                                <p className="text-sm font-semibold text-gray-900">{sale.client?.name || '—'}</p>
                                <p className="text-xs text-gray-500">{sale.client?.email || '—'}</p>
                                {sale.client?.phone && (
                                    <p className="text-xs text-gray-500">{sale.client.phone}</p>
                                )}
                            </div>
                        </div>
                        {sale.quotation && (
                            <div className="flex items-start gap-3">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-purple-100">
                                    <DocumentTextIcon className="h-5 w-5 text-purple-600" />
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500 uppercase tracking-wider">Cotización</p>
                                    <Link
                                        href={`/admin/cotizaciones/${sale.quotation.id}`}
                                        className="text-sm font-semibold text-primary-600 hover:text-primary-700"
                                    >
                                        {sale.quotation.quotation_number}
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left Column */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Items Table */}
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200">
                                <h3 className="text-lg font-semibold text-gray-900">
                                    Detalle de Ítems
                                </h3>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                Descripción
                                            </th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Cant.
                                            </th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                P. Unit.
                                            </th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Desc.
                                            </th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-200">
                                        {sale.items && sale.items.length > 0 ? (
                                            sale.items.map((item) => (
                                                <tr key={item.id} className="hover:bg-gray-50">
                                                    <td className="px-6 py-4">
                                                        <p className="text-sm font-medium text-gray-900">
                                                            {item.product?.name || item.description || 'Ítem'}
                                                        </p>
                                                        {item.description && item.product?.name && (
                                                            <p className="text-xs text-gray-500 mt-0.5">{item.description}</p>
                                                        )}
                                                    </td>
                                                    <td className="px-6 py-4 text-sm text-right text-gray-900">
                                                        {item.quantity}
                                                    </td>
                                                    <td className="px-6 py-4 text-sm text-right text-gray-900">
                                                        {item.formatted_unit_price || formatMoney(item.unit_price)}
                                                    </td>
                                                    <td className="px-6 py-4 text-sm text-right text-gray-500">
                                                        {item.discount
                                                            ? item.formatted_discount || formatMoney(item.discount)
                                                            : '—'}
                                                    </td>
                                                    <td className="px-6 py-4 text-sm text-right font-medium text-gray-900">
                                                        {item.formatted_total || formatMoney(item.total)}
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan={5} className="px-6 py-8 text-center text-gray-500">
                                                    No hay ítems en esta venta.
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {/* Payment Progress */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Progreso de Pago
                            </h3>
                            <div className="space-y-4">
                                {/* Progress Bar */}
                                <div>
                                    <div className="flex justify-between text-sm mb-2">
                                        <span className="text-gray-600">Progreso</span>
                                        <span className="font-medium text-gray-900">
                                            {paymentPercent.toFixed(1)}%
                                        </span>
                                    </div>
                                    <div className="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div
                                            className={`h-3 rounded-full transition-all duration-500 ${
                                                isFullyPaid
                                                    ? 'bg-green-500'
                                                    : paymentPercent > 0
                                                    ? 'bg-yellow-500'
                                                    : 'bg-red-400'
                                            }`}
                                            style={{ width: `${paymentPercent}%` }}
                                        />
                                    </div>
                                </div>

                                {/* Paid vs Remaining */}
                                <div className="grid grid-cols-2 gap-4">
                                    <div className={`rounded-lg p-4 ${isFullyPaid ? 'bg-green-50 border border-green-200' : 'bg-blue-50 border border-blue-200'}`}>
                                        <p className={`text-xs font-medium uppercase ${isFullyPaid ? 'text-green-600' : 'text-blue-600'}`}>
                                            Pagado
                                        </p>
                                        <p className={`text-xl font-bold mt-1 ${isFullyPaid ? 'text-green-700' : 'text-blue-700'}`}>
                                            {formatMoney(paidAmount)}
                                        </p>
                                        <p className={`text-xs mt-1 ${isFullyPaid ? 'text-green-500' : 'text-blue-500'}`}>
                                            de {formatMoney(total)}
                                        </p>
                                    </div>
                                    <div className={`rounded-lg p-4 ${!isFullyPaid && remaining > 0 ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200'}`}>
                                        <p className={`text-xs font-medium uppercase ${!isFullyPaid && remaining > 0 ? 'text-red-600' : 'text-gray-600'}`}>
                                            Restante
                                        </p>
                                        <p className={`text-xl font-bold mt-1 ${!isFullyPaid && remaining > 0 ? 'text-red-700' : 'text-gray-700'}`}>
                                            {formatMoney(remaining)}
                                        </p>
                                        {isFullyPaid && (
                                            <p className="text-xs text-green-500 mt-1">
                                                Pago completado
                                            </p>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Notes */}
                        {sale.notes && (
                            <div className="card">
                                <h3 className="text-sm font-semibold text-gray-900 mb-2">
                                    Notas
                                </h3>
                                <p className="text-sm text-gray-600 whitespace-pre-wrap">{sale.notes}</p>
                            </div>
                        )}
                    </div>

                    {/* Right Column - Totals & Actions */}
                    <div className="space-y-6">
                        {/* Totals Card */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Resumen de Pago
                            </h3>
                            <div className="space-y-3">
                                <div className="flex justify-between text-sm">
                                    <span className="text-gray-500">Subtotal</span>
                                    <span className="font-medium text-gray-900">
                                        {sale.formatted_subtotal || formatMoney(sale.subtotal)}
                                    </span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span className="text-gray-500">IVA ({sale.tax_rate || 16}%)</span>
                                    <span className="font-medium text-gray-900">
                                        {sale.formatted_tax || formatMoney(sale.tax)}
                                    </span>
                                </div>
                                {sale.discount && sale.discount > 0 && (
                                    <div className="flex justify-between text-sm text-green-600">
                                        <span>Descuento</span>
                                        <span>
                                            -{sale.formatted_discount || formatMoney(sale.discount)}
                                        </span>
                                    </div>
                                )}
                                <hr className="border-gray-200" />
                                <div className="flex justify-between">
                                    <span className="text-base font-semibold text-gray-900">TOTAL</span>
                                    <span className="text-xl font-bold text-primary-600">
                                        {sale.formatted_total || formatMoney(sale.total)}
                                    </span>
                                </div>
                            </div>

                            {/* Payment Method */}
                            {sale.payment_method && (
                                <div className="mt-4 pt-4 border-t border-gray-200">
                                    <p className="text-xs font-medium text-gray-500">Método de Pago</p>
                                    <p className="text-sm text-gray-900 mt-0.5">
                                        {sale.payment_method_label || paymentMethodLabels[sale.payment_method] || sale.payment_method}
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* Action Buttons */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Acciones
                            </h3>
                            <div className="space-y-3">
                                {(sale.status === 'pending' || sale.status === 'partially_paid') && (
                                    <button
                                        onClick={() => setShowPaymentModal(true)}
                                        className="w-full btn-primary py-2.5 flex items-center justify-center gap-2"
                                    >
                                        <CurrencyDollarIcon className="h-4 w-4" />
                                        Registrar Pago
                                    </button>
                                )}
                                {sale.status === 'pending' && (
                                    <button
                                        onClick={() => setShowCancelConfirm(true)}
                                        className="w-full px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors flex items-center justify-center gap-2"
                                    >
                                        <ExclamationCircleIcon className="h-4 w-4" />
                                        Cancelar Venta
                                    </button>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Payment Modal */}
                <Modal
                    isOpen={showPaymentModal}
                    onClose={() => {
                        setShowPaymentModal(false);
                        reset();
                    }}
                    title="Registrar Pago"
                >
                    <form onSubmit={handlePaymentSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Monto a Pagar *
                            </label>
                            <input
                                type="number"
                                value={data.amount}
                                onChange={(e) => setData('amount', e.target.value)}
                                className={`input-field px-4 py-2.5 ${errors.amount ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                placeholder={remaining.toFixed(2)}
                                step="0.01"
                                min="0.01"
                                max={remaining.toFixed(2)}
                                required
                            />
                            {errors.amount && (
                                <p className="mt-1 text-sm text-red-600">{errors.amount}</p>
                            )}
                            <p className="mt-1 text-xs text-gray-500">
                                Restante: {formatMoney(remaining)}
                            </p>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Método de Pago *
                            </label>
                            <select
                                value={data.payment_method}
                                onChange={(e) => setData('payment_method', e.target.value)}
                                className={`input-field px-4 py-2.5 ${errors.payment_method ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                required
                            >
                                <option value="">Seleccionar método...</option>
                                <option value="cash">Efectivo</option>
                                <option value="card">Tarjeta</option>
                                <option value="transfer">Transferencia</option>
                                <option value="credit">Crédito</option>
                            </select>
                            {errors.payment_method && (
                                <p className="mt-1 text-sm text-red-600">{errors.payment_method}</p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Notas
                            </label>
                            <textarea
                                value={data.notes}
                                onChange={(e) => setData('notes', e.target.value)}
                                rows={3}
                                className="input-field px-4 py-2.5"
                                placeholder="Notas del pago..."
                            />
                            {errors.notes && (
                                <p className="mt-1 text-sm text-red-600">{errors.notes}</p>
                            )}
                        </div>

                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => {
                                    setShowPaymentModal(false);
                                    reset();
                                }}
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                disabled={processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Registrando...' : 'Registrar Pago'}
                            </button>
                        </div>
                    </form>
                </Modal>

                {/* Cancel Confirmation Modal */}
                <Modal
                    isOpen={showCancelConfirm}
                    onClose={() => setShowCancelConfirm(false)}
                    title="Cancelar Venta"
                >
                    <div className="space-y-4">
                        <div className="flex items-start gap-3">
                            <ExclamationCircleIcon className="h-6 w-6 text-red-500 mt-0.5" />
                            <div>
                                <p className="text-sm font-medium text-gray-900">
                                    ¿Estás seguro de cancelar esta venta?
                                </p>
                                <p className="text-sm text-gray-500 mt-1">
                                    Esta acción no se puede deshacer. La venta {sale.sale_number} será marcada como cancelada.
                                </p>
                            </div>
                        </div>
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => setShowCancelConfirm(false)}
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                No, Mantener
                            </button>
                            <button
                                type="button"
                                onClick={handleCancelSale}
                                className="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors"
                            >
                                Sí, Cancelar Venta
                            </button>
                        </div>
                    </div>
                </Modal>
            </AuthenticatedLayout>
        </>
    );
}
