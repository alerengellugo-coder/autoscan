import React, { useState } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import {
    BellIcon,
    CheckCircleIcon,
    TrashIcon,
    ClipboardDocumentListIcon,
    DocumentTextIcon,
    BanknotesIcon,
    ExclamationCircleIcon,
    MagnifyingGlassIcon,
} from '@heroicons/react/24/outline';
import { PageProps, AppNotification, PaginationData } from '../../types';

interface NotificationsIndexProps extends PageProps {
    notifications: PaginationData<AppNotification>;
    unread_count: number;
}

const notificationTypeConfig: Record<string, { icon: React.ElementType; color: string; bgColor: string }> = {
    order_status: {
        icon: ClipboardDocumentListIcon,
        color: 'text-blue-600',
        bgColor: 'bg-blue-100',
    },
    report_created: {
        icon: DocumentTextIcon,
        color: 'text-purple-600',
        bgColor: 'bg-purple-100',
    },
    quotation_status: {
        icon: BanknotesIcon,
        color: 'text-green-600',
        bgColor: 'bg-green-100',
    },
    sale_status: {
        icon: BanknotesIcon,
        color: 'text-emerald-600',
        bgColor: 'bg-emerald-100',
    },
    general: {
        icon: BellIcon,
        color: 'text-gray-600',
        bgColor: 'bg-gray-100',
    },
};

function formatRelativeTime(dateString: string): string {
    const now = new Date();
    const date = new Date(dateString);
    const diffMs = now.getTime() - date.getTime();
    const diffSeconds = Math.floor(diffMs / 1000);
    const diffMinutes = Math.floor(diffSeconds / 60);
    const diffHours = Math.floor(diffMinutes / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffSeconds < 60) return 'hace un momento';
    if (diffMinutes < 60) return `hace ${diffMinutes} minuto${diffMinutes !== 1 ? 's' : ''}`;
    if (diffHours < 24) return `hace ${diffHours} hora${diffHours !== 1 ? 's' : ''}`;
    if (diffDays < 7) return `hace ${diffDays} día${diffDays !== 1 ? 's' : ''}`;

    return date.toLocaleDateString('es-MX', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

export default function NotificationsIndex({
    notifications,
    unread_count,
}: NotificationsIndexProps) {
    const [activeFilter, setActiveFilter] = useState<'all' | 'unread'>('all');

    const handleMarkAllRead = () => {
        router.post('/notifications/mark-all-read', {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleMarkRead = (id: string) => {
        router.post(`/notifications/${id}/mark-read`, {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleDelete = (id: string) => {
        if (confirm('¿Deseas eliminar esta notificación?')) {
            router.delete(`/notifications/${id}`, {
                preserveState: true,
                preserveScroll: true,
            });
        }
    };

    const getNotificationLink = (notification: AppNotification): string | null => {
        if (notification.type === 'order_status' && notification.data.order_number) {
            return `/orders?search=${notification.data.order_number}`;
        }
        if (notification.type === 'quotation_status' && notification.data.quotation_number) {
            return `/quotations?search=${notification.data.quotation_number}`;
        }
        if (notification.type === 'report_created' && notification.data.order_number) {
            return `/orders?search=${notification.data.order_number}`;
        }
        return null;
    };

    const config = notificationTypeConfig;

    return (
        <>
            <Head title="Notificaciones" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-3">
                            <h1 className="text-xl font-semibold text-gray-900">
                                Notificaciones
                            </h1>
                            {unread_count > 0 && (
                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                    {unread_count} sin leer
                                </span>
                            )}
                        </div>
                        {unread_count > 0 && (
                            <button
                                onClick={handleMarkAllRead}
                                className="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 hover:text-primary-700"
                            >
                                <CheckCircleIcon className="h-4 w-4" />
                                Marcar todas como leídas
                            </button>
                        )}
                    </div>
                }
            >
                {/* Filter Tabs */}
                <div className="mb-6">
                    <div className="flex border-b border-gray-200">
                        <button
                            type="button"
                            onClick={() => setActiveFilter('all')}
                            className={`px-5 py-2.5 text-sm font-medium border-b-2 transition-colors ${
                                activeFilter === 'all'
                                    ? 'border-primary-500 text-primary-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            }`}
                        >
                            Todas ({notifications.total})
                        </button>
                        <button
                            type="button"
                            onClick={() => setActiveFilter('unread')}
                            className={`px-5 py-2.5 text-sm font-medium border-b-2 transition-colors ${
                                activeFilter === 'unread'
                                    ? 'border-primary-500 text-primary-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            }`}
                        >
                            No leídas {unread_count > 0 && `(${unread_count})`}
                        </button>
                    </div>
                </div>

                {/* Notification List */}
                <div className="card p-0 overflow-hidden">
                    {notifications.data.length === 0 ? (
                        <div className="px-6 py-16 text-center">
                            <div className="flex justify-center mb-4">
                                <div className="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                    <BellIcon className="h-8 w-8 text-gray-400" />
                                </div>
                            </div>
                            <h3 className="text-base font-medium text-gray-900 mb-1">
                                No hay notificaciones
                            </h3>
                            <p className="text-sm text-gray-500">
                                {activeFilter === 'unread'
                                    ? 'No tienes notificaciones sin leer.'
                                    : 'Las notificaciones aparecerán aquí cuando tengas actividad nueva.'}
                            </p>
                        </div>
                    ) : (
                        <div className="divide-y divide-gray-200">
                            {notifications.data.map((notification) => {
                                const isUnread = !notification.read_at;
                                const typeConf = config[notification.type] || config.general;
                                const IconComponent = typeConf.icon;
                                const link = getNotificationLink(notification);

                                return (
                                    <div
                                        key={notification.id}
                                        className={`px-6 py-4 hover:bg-gray-50 transition-colors ${
                                            isUnread ? 'bg-primary-50/50' : ''
                                        }`}
                                    >
                                        <div className="flex items-start gap-4">
                                            {/* Unread indicator */}
                                            {isUnread && (
                                                <div className="mt-2 shrink-0">
                                                    <div className="h-2.5 w-2.5 rounded-full bg-primary-500" />
                                                </div>
                                            )}

                                            {/* Icon */}
                                            <div className={`flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ${typeConf.bgColor}`}>
                                                <IconComponent className={`h-5 w-5 ${typeConf.color}`} />
                                            </div>

                                            {/* Content */}
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-start justify-between gap-4">
                                                    <div>
                                                        <p className={`text-sm ${isUnread ? 'font-semibold text-gray-900' : 'font-medium text-gray-700'}`}>
                                                            {notification.data.title || 'Notificación'}
                                                        </p>
                                                        <p className="text-sm text-gray-500 mt-0.5 line-clamp-2">
                                                            {notification.data.message || notification.data.body || ''}
                                                        </p>
                                                    </div>
                                                    <div className="shrink-0 text-right">
                                                        <p className="text-xs text-gray-400 whitespace-nowrap">
                                                            {formatRelativeTime(notification.created_at)}
                                                        </p>
                                                    </div>
                                                </div>

                                                {/* Actions */}
                                                <div className="flex items-center gap-3 mt-2">
                                                    {link && (
                                                        <Link
                                                            href={link}
                                                            className="text-xs font-medium text-primary-600 hover:text-primary-700"
                                                        >
                                                            Ver detalle →
                                                        </Link>
                                                    )}
                                                    <div className="flex items-center gap-2 ml-auto">
                                                        {isUnread && (
                                                            <button
                                                                onClick={() => handleMarkRead(notification.id)}
                                                                className="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors"
                                                            >
                                                                <CheckCircleIcon className="h-3.5 w-3.5" />
                                                                Leer
                                                            </button>
                                                        )}
                                                        <button
                                                            onClick={() => handleDelete(notification.id)}
                                                            className="inline-flex items-center gap-1 text-xs font-medium text-gray-400 hover:text-red-600 transition-colors"
                                                        >
                                                            <TrashIcon className="h-3.5 w-3.5" />
                                                            Eliminar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}

                    {/* Pagination */}
                    {notifications.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {notifications.from} a {notifications.to} de {notifications.total} notificaciones
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={notifications.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        notifications.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {notifications.current_page} de {notifications.last_page}
                                </span>
                                <Link
                                    href={notifications.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        notifications.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
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
