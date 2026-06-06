import React, { useState } from 'react';
import { usePage, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import EmptyState from '../../../Components/EmptyState';
import Modal from '../../../Components/Modal';
import {
    DocumentTextIcon,
    EyeIcon,
    CheckCircleIcon,
    XCircleIcon,
    TruckIcon,
    CalendarDaysIcon,
    ClockIcon,
    ExclamationTriangleIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Quotation } from '../../../types';

interface ClientQuotationsPageProps extends PageProps {
    quotations: Quotation[];
}

const quotationStatusLabels: Record<string, string> = {
    draft: 'Borrador',
    pending_client: 'Pendiente de Aprobación',
    approved: 'Aprobada',
    rejected: 'Rechazada',
    expired: 'Expirada',
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

export default function ClientQuotationsIndex() {
    const { props } = usePage<ClientQuotationsPageProps>();
    const { quotations } = props;

    const [confirmModal, setConfirmModal] = useState<{
        open: boolean;
        quotationId: number;
        action: 'approve' | 'reject';
    }>({ open: false, quotationId: 0, action: 'approve' });

    const handleApprove = (quotationId: number) => {
        setConfirmModal({ open: true, quotationId, action: 'approve' });
    };

    const handleReject = (quotationId: number) => {
        setConfirmModal({ open: true, quotationId, action: 'reject' });
    };

    const confirmAction = () => {
        const { quotationId, action } = confirmModal;
        const method = action === 'approve' ? 'patch' : 'patch';
        router[method](
            `/quotations/${quotationId}/${action}`,
            {},
            {
                onSuccess: () => {
                    setConfirmModal({ open: false, quotationId: 0, action: 'approve' });
                    router.reload({ only: ['quotations'] });
                },
            }
        );
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <DocumentTextIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Mis Cotizaciones</h1>
                        <p className="text-sm text-gray-500">Revisa y gestiona tus cotizaciones</p>
                    </div>
                </div>
            }
        >
            <div className="space-y-6">
                {quotations.length === 0 ? (
                    <EmptyState
                        title="No hay cotizaciones"
                        description="No tienes cotizaciones registradas. Las cotizaciones aparecerán cuando un administrador te envíe una."
                        icon={DocumentTextIcon}
                    />
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        {quotations.map((quotation) => {
                            const isPendingClient = quotation.status === 'pending_client';

                            return (
                                <div
                                    key={quotation.id}
                                    className="card hover:shadow-card-hover transition-shadow duration-200 flex flex-col"
                                >
                                    {/* Card header */}
                                    <div className="flex items-start justify-between mb-3">
                                        <div>
                                            <Link
                                                href={`/quotations/${quotation.id}`}
                                                className="text-lg font-bold text-primary-600 hover:text-primary-700 transition-colors"
                                            >
                                                {quotation.quotation_number}
                                            </Link>
                                            <div className="flex items-center gap-1.5 mt-1">
                                                <CalendarDaysIcon className="h-3.5 w-3.5 text-gray-400" />
                                                <span className="text-xs text-gray-500">
                                                    {formatDate(quotation.created_at)}
                                                </span>
                                            </div>
                                        </div>
                                        <StatusBadge
                                            status={quotation.status}
                                            label={quotation.status_label || quotationStatusLabels[quotation.status]}
                                            size="md"
                                        />
                                    </div>

                                    {/* Vehicle info (if linked) */}
                                    {quotation.vehicle && (
                                        <div className="flex items-center gap-2 p-3 bg-gray-50 rounded-lg mb-3">
                                            <TruckIcon className="h-4 w-4 text-gray-400" />
                                            <span className="text-sm text-gray-700">
                                                {quotation.vehicle.brand} {quotation.vehicle.model} —{' '}
                                                <span className="font-medium text-primary-600">
                                                    {quotation.vehicle.plate}
                                                </span>
                                            </span>
                                        </div>
                                    )}

                                    {/* Total amount */}
                                    <div className="mb-3">
                                        <p className="text-xs text-gray-500 mb-1">Total</p>
                                        <p className="text-2xl font-bold text-gray-900">
                                            {quotation.formatted_total || formatCurrency(quotation.total)}
                                        </p>
                                    </div>

                                    {/* Valid until */}
                                    {quotation.valid_until && (
                                        <div className="flex items-center gap-1.5 text-sm text-gray-500 mb-4">
                                            <ClockIcon className="h-4 w-4" />
                                            <span>Válida hasta: {formatDate(quotation.valid_until)}</span>
                                            {quotation.is_expired && !['approved', 'rejected'].includes(quotation.status) && (
                                                <ExclamationTriangleIcon className="h-4 w-4 text-yellow-500" />
                                            )}
                                        </div>
                                    )}

                                    {/* Spacer */}
                                    <div className="flex-1" />

                                    {/* Actions */}
                                    <div className="flex items-center gap-2 pt-4 border-t border-gray-100">
                                        <Link
                                            href={`/quotations/${quotation.id}`}
                                            className="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 bg-primary-50 text-primary-700 rounded-lg text-sm font-medium hover:bg-primary-100 transition-colors"
                                        >
                                            <EyeIcon className="h-4 w-4" />
                                            Ver Detalles
                                        </Link>

                                        {isPendingClient && (
                                            <>
                                                <button
                                                    type="button"
                                                    onClick={() => handleApprove(quotation.id)}
                                                    className="flex items-center justify-center gap-1.5 px-3 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors"
                                                >
                                                    <CheckCircleIcon className="h-4 w-4" />
                                                    Aprobar
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => handleReject(quotation.id)}
                                                    className="flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 text-red-700 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors"
                                                >
                                                    <XCircleIcon className="h-4 w-4" />
                                                    Rechazar
                                                </button>
                                            </>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}

                {/* Confirmation Modal */}
                <Modal
                    isOpen={confirmModal.open}
                    onClose={() => setConfirmModal({ open: false, quotationId: 0, action: 'approve' })}
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
                                        Al aprobar, se generará una orden de servicio o venta asociada.
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
                                        Esta acción no se puede deshacer.
                                    </p>
                                </div>
                            </div>
                        )}

                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => setConfirmModal({ open: false, quotationId: 0, action: 'approve' })}
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


