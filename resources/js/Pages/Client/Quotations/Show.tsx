import React, { useState } from 'react';
import { usePage, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import Modal from '../../../Components/Modal';
import {
    DocumentTextIcon,
    XMarkIcon,
    CalendarDaysIcon,
    CheckCircleIcon,
    XCircleIcon,
    TruckIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Quotation, QuotationItem } from '../../../types';

interface ClientQuotationShowProps extends PageProps {
    quotation: Quotation;
}

const quotationStatusLabels: Record<string, string> = {
    draft: 'Borrador',
    pending_client: 'Pendiente de Aprobación',
    approved: 'Aprobada',
    rejected: 'Rechazada',
    expired: 'Expirada',
};

const formatDate = (dateStr: string): string => {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};

const formatCurrency = (amount: number): string => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(amount);
};

export default function ClientQuotationShow() {
    const { props } = usePage<ClientQuotationShowProps>();
    const { quotation } = props;

    const [confirmModal, setConfirmModal] = useState<{
        open: boolean;
        action: 'approve' | 'reject';
    }>({ open: false, action: 'approve' });

    const isPendingClient = quotation.status === 'pending_client';
    const items = quotation.items || [];
    const taxRate = quotation.tax_rate || 16;

    const subtotal = items.reduce((sum, item) => sum + item.total, 0);
    const discount = quotation.discount || 0;
    const tax = (subtotal - discount) * (taxRate / 100);
    const total = subtotal - discount + tax;

    const handleApprove = () => {
        setConfirmModal({ open: true, action: 'approve' });
    };

    const handleReject = () => {
        setConfirmModal({ open: true, action: 'reject' });
    };

    const confirmAction = () => {
        const action = confirmModal.action;
        router.patch(
            `/quotations/${quotation.id}/${action}`,
            {},
            {
                onSuccess: () => {
                    setConfirmModal({ open: false, action: 'approve' });
                    router.reload({ only: ['quotation'] });
                },
            }
        );
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <Link
                        href="/quotations"
                        className="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <XMarkIcon className="h-4 w-4" />
                        Volver
                    </Link>
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <DocumentTextIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">{quotation.quotation_number}</h1>
                        <p className="text-sm text-gray-500">Detalle de la cotización</p>
                    </div>
                </div>
            }
        >
            <div className="max-w-4xl mx-auto space-y-6">
                {/* Quotation header */}
                <div className="card">
                    <div className="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <div className="flex flex-wrap items-center gap-3 mb-2">
                                <h2 className="text-2xl font-bold text-gray-900">{quotation.quotation_number}</h2>
                                <StatusBadge
                                    status={quotation.status}
                                    label={quotation.status_label || quotationStatusLabels[quotation.status]}
                                    size="md"
                                />
                            </div>
                            <div className="flex items-center gap-4 text-sm text-gray-500">
                                <div className="flex items-center gap-1.5">
                                    <CalendarDaysIcon className="h-4 w-4" />
                                    <span>Creada: {formatDate(quotation.created_at)}</span>
                                </div>
                                {quotation.valid_until && (
                                    <div className="flex items-center gap-1.5">
                                        <CalendarDaysIcon className="h-4 w-4" />
                                        <span>Válida hasta: {formatDate(quotation.valid_until)}</span>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Vehicle info */}
                    {quotation.vehicle && (
                        <div className="mt-4 flex items-center gap-2 p-3 bg-gray-50 rounded-xl">
                            <TruckIcon className="h-5 w-5 text-gray-400" />
                            <span className="text-sm text-gray-700">
                                Vehículo: {quotation.vehicle.brand} {quotation.vehicle.model} ({quotation.vehicle.year}) —{' '}
                                <span className="font-medium text-primary-600">{quotation.vehicle.plate}</span>
                            </span>
                        </div>
                    )}
                </div>

                {/* Items table */}
                <div className="card overflow-hidden !p-0">
                    <div className="px-6 py-4 border-b border-gray-200">
                        <h3 className="text-base font-semibold text-gray-900">Productos y Servicios</h3>
                    </div>

                    {items.length === 0 ? (
                        <div className="px-6 py-8 text-center">
                            <p className="text-sm text-gray-500">No hay productos en esta cotización.</p>
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="bg-gray-50">
                                        <th className="table-header px-6 py-3">Descripción</th>
                                        <th className="table-header px-4 py-3 text-center">Cantidad</th>
                                        <th className="table-header px-4 py-3 text-right">Precio Unitario</th>
                                        <th className="table-header px-4 py-3 text-right">Descuento</th>
                                        <th className="table-header px-6 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200">
                                    {items.map((item) => (
                                        <tr key={item.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4">
                                                <p className="text-sm font-medium text-gray-900">
                                                    {item.description}
                                                </p>
                                                {item.product && (
                                                    <p className="text-xs text-gray-500 mt-0.5">
                                                        SKU: {item.product.sku || 'N/A'}
                                                    </p>
                                                )}
                                            </td>
                                            <td className="px-4 py-4 text-center">
                                                <span className="text-sm text-gray-700">{item.quantity}</span>
                                            </td>
                                            <td className="px-4 py-4 text-right">
                                                <span className="text-sm text-gray-700">
                                                    {item.formatted_unit_price || formatCurrency(item.unit_price)}
                                                </span>
                                            </td>
                                            <td className="px-4 py-4 text-right">
                                                {item.discount ? (
                                                    <span className="text-sm text-green-600">
                                                        -{item.formatted_discount || formatCurrency(item.discount)}
                                                    </span>
                                                ) : (
                                                    <span className="text-sm text-gray-400">—</span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 text-right">
                                                <span className="text-sm font-medium text-gray-900">
                                                    {item.formatted_total || formatCurrency(item.total)}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}

                    {/* Totals */}
                    <div className="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div className="max-w-xs ml-auto space-y-2">
                            <div className="flex items-center justify-between text-sm">
                                <span className="text-gray-500">Subtotal</span>
                                <span className="text-gray-700 font-medium">
                                    {quotation.formatted_subtotal || formatCurrency(subtotal)}
                                </span>
                            </div>
                            {discount > 0 && (
                                <div className="flex items-center justify-between text-sm">
                                    <span className="text-gray-500">Descuento</span>
                                    <span className="text-green-600 font-medium">
                                        -{quotation.formatted_discount || formatCurrency(discount)}
                                    </span>
                                </div>
                            )}
                            <div className="flex items-center justify-between text-sm">
                                <span className="text-gray-500">IVA ({taxRate}%)</span>
                                <span className="text-gray-700 font-medium">
                                    {quotation.formatted_tax || formatCurrency(tax)}
                                </span>
                            </div>
                            <div className="flex items-center justify-between pt-2 border-t border-gray-300">
                                <span className="text-base font-semibold text-gray-900">TOTAL</span>
                                <span className="text-2xl font-bold text-gray-900">
                                    {quotation.formatted_total || formatCurrency(total)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Notes */}
                {quotation.notes && (
                    <div className="card">
                        <h3 className="text-base font-semibold text-gray-900 mb-2">Notas</h3>
                        <p className="text-sm text-gray-600 whitespace-pre-line">{quotation.notes}</p>
                    </div>
                )}

                {/* Terms and conditions */}
                {quotation.terms_and_conditions && (
                    <div className="card">
                        <h3 className="text-base font-semibold text-gray-900 mb-2">Términos y Condiciones</h3>
                        <p className="text-sm text-gray-600 whitespace-pre-line">{quotation.terms_and_conditions}</p>
                    </div>
                )}

                {/* Approve/Reject buttons */}
                {isPendingClient && (
                    <div className="flex items-center justify-end gap-3">
                        <button
                            type="button"
                            onClick={handleReject}
                            className="flex items-center gap-2 px-6 py-2.5 bg-red-50 text-red-700 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors border border-red-200"
                        >
                            <XCircleIcon className="h-4 w-4" />
                            Rechazar Cotización
                        </button>
                        <button
                            type="button"
                            onClick={handleApprove}
                            className="flex items-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors"
                        >
                            <CheckCircleIcon className="h-4 w-4" />
                            Aprobar Cotización
                        </button>
                    </div>
                )}

                {/* Confirmation Modal */}
                <Modal
                    isOpen={confirmModal.open}
                    onClose={() => setConfirmModal({ open: false, action: 'approve' })}
                    title={confirmModal.action === 'approve' ? 'Aprobar Cotización' : 'Rechazar Cotización'}
                    size="sm"
                >
                    <div className="space-y-4">
                        {confirmModal.action === 'approve' ? (
                            <div className="flex items-start gap-3">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100">
                                    <CheckCircleIcon className="h-5 w-5 text-green-600" />
                                </div>
                                <div>
                                    <p className="text-sm font-medium text-gray-900">
                                        ¿Estás seguro de que deseas aprobar esta cotización?
                                    </p>
                                    <p className="text-sm text-gray-500 mt-1">
                                        Al aprobar la cotización {quotation.quotation_number}, se procederá con el servicio.
                                    </p>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-start gap-3">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                                    <XCircleIcon className="h-5 w-5 text-red-600" />
                                </div>
                                <div>
                                    <p className="text-sm font-medium text-gray-900">
                                        ¿Estás seguro de que deseas rechazar esta cotización?
                                    </p>
                                    <p className="text-sm text-gray-500 mt-1">
                                        Esta acción no se puede deshacer. Podrás solicitar una nueva cotización.
                                    </p>
                                </div>
                            </div>
                        )}

                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => setConfirmModal({ open: false, action: 'approve' })}
                                className="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="button"
                                onClick={confirmAction}
                                className={
                                    confirmModal.action === 'approve'
                                        ? 'bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200'
                                        : 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200'
                                }
                            >
                                {confirmModal.action === 'approve' ? 'Sí, Aprobar' : 'Sí, Rechazar'}
                            </button>
                        </div>
                    </div>
                </Modal>
            </div>
        </AuthenticatedLayout>
    );
}
