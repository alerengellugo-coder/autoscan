import React, { useState, Fragment, useRef } from 'react';
import { Link, router } from '@inertiajs/react';
import { Dialog, Transition } from '@headlessui/react';
import { BellIcon, CheckIcon } from '@heroicons/react/24/outline';
import { PageProps, AppNotification } from '../types';

export default function NotificationBell() {
    const { notifications = [], unread_count = 0 } =
        (typeof window !== 'undefined'
            ? (document.querySelector('[data-page]')?.getAttribute('data-page')
                ? {}
                : {})
            : {}) as Record<string, unknown>;

    const [isOpen, setIsOpen] = useState(false);

    const markAsRead = (id: string) => {
        router.post(`/notifications/${id}/read`, {}, { preserveScroll: true });
    };

    const markAllAsRead = () => {
        router.post('/notifications/read-all', {}, { preserveScroll: true });
    };

    return (
        <div className="relative">
            <button
                type="button"
                onClick={() => setIsOpen(!isOpen)}
                className="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
            >
                <span className="sr-only">Notificaciones</span>
                <BellIcon className="h-6 w-6" />
                {unread_count > 0 && (
                    <span className="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                        {unread_count > 9 ? '9+' : unread_count}
                    </span>
                )}
            </button>

            <Transition
                show={isOpen}
                as={Fragment}
                enter="transition ease-out duration-200"
                enterFrom="opacity-0 translate-y-1"
                enterTo="opacity-100 translate-y-0"
                leave="transition ease-in duration-150"
                leaveFrom="opacity-100 translate-y-0"
                leaveTo="opacity-0 translate-y-1"
            >
                <div className="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black/5">
                    <div className="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h3 className="text-sm font-semibold text-gray-900">
                            Notificaciones
                        </h3>
                        {unread_count > 0 && (
                            <button
                                onClick={markAllAsRead}
                                className="text-xs text-primary-600 hover:text-primary-700 font-medium"
                            >
                                Marcar todas como leídas
                            </button>
                        )}
                    </div>

                    <div className="max-h-80 overflow-y-auto">
                        {notifications.length === 0 ? (
                            <div className="py-8 text-center">
                                <BellIcon className="mx-auto h-8 w-8 text-gray-300 mb-2" />
                                <p className="text-sm text-gray-500">
                                    No hay notificaciones
                                </p>
                            </div>
                        ) : (
                            <div className="divide-y divide-gray-100">
                                {notifications.slice(0, 10).map((notif: AppNotification) => (
                                    <div
                                        key={notif.id}
                                        className={`px-4 py-3 hover:bg-gray-50 transition-colors ${
                                            !notif.read_at ? 'bg-primary-50/50' : ''
                                        }`}
                                    >
                                        <div className="flex items-start gap-3">
                                            <div
                                                className={`mt-0.5 h-2 w-2 rounded-full shrink-0 ${
                                                    !notif.read_at
                                                        ? 'bg-primary-500'
                                                        : 'bg-transparent'
                                                }`}
                                            />
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm text-gray-900 font-medium">
                                                    {notif.data.title || 'Notificación'}
                                                </p>
                                                <p className="text-xs text-gray-500 mt-0.5 line-clamp-2">
                                                    {notif.data.message ||
                                                        notif.data.body}
                                                </p>
                                                <p className="text-xs text-gray-400 mt-1">
                                                    {new Date(
                                                        notif.created_at
                                                    ).toLocaleDateString('es-ES', {
                                                        day: 'numeric',
                                                        month: 'short',
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                    })}
                                                </p>
                                            </div>
                                            {!notif.read_at && (
                                                <button
                                                    onClick={() => markAsRead(notif.id)}
                                                    className="shrink-0 p-1 text-gray-400 hover:text-primary-600"
                                                    title="Marcar como leída"
                                                >
                                                    <CheckIcon className="h-4 w-4" />
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    <div className="px-4 py-2 border-t border-gray-100 text-center">
                        <Link
                            href="/notifications"
                            className="text-xs font-medium text-primary-600 hover:text-primary-700"
                        >
                            Ver todas las notificaciones
                        </Link>
                    </div>
                </div>
            </Transition>
        </div>
    );
}
