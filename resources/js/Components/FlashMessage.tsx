import React, { useEffect, useState } from 'react';
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import { FlashMessage as FlashMessageType } from '../types';

interface FlashMessageProps {
    flash: FlashMessageType;
}

export default function FlashMessage({ flash }: FlashMessageProps) {
    const [visible, setVisible] = useState(false);
    const [message, setMessage] = useState<{
        type: 'success' | 'error' | 'warning' | 'info';
        text: string;
    } | null>(null);

    useEffect(() => {
        if (flash.success) {
            setMessage({ type: 'success', text: flash.success });
            setVisible(true);
        } else if (flash.error) {
            setMessage({ type: 'error', text: flash.error });
            setVisible(true);
        } else if (flash.warning) {
            setMessage({ type: 'warning', text: flash.warning });
            setVisible(true);
        } else if (flash.info) {
            setMessage({ type: 'info', text: flash.info });
            setVisible(true);
        }
    }, [flash]);

    useEffect(() => {
        if (visible) {
            const timer = setTimeout(() => {
                setVisible(false);
                setTimeout(() => setMessage(null), 300);
            }, 5000);
            return () => clearTimeout(timer);
        }
    }, [visible]);

    if (!message) return null;

    const config = {
        success: {
            icon: CheckCircleIcon,
            bg: 'bg-green-50',
            border: 'border-green-200',
            text: 'text-green-800',
            iconColor: 'text-green-500',
        },
        error: {
            icon: XCircleIcon,
            bg: 'bg-red-50',
            border: 'border-red-200',
            text: 'text-red-800',
            iconColor: 'text-red-500',
        },
        warning: {
            icon: ExclamationTriangleIcon,
            bg: 'bg-yellow-50',
            border: 'border-yellow-200',
            text: 'text-yellow-800',
            iconColor: 'text-yellow-500',
        },
        info: {
            icon: InformationCircleIcon,
            bg: 'bg-blue-50',
            border: 'border-blue-200',
            text: 'text-blue-800',
            iconColor: 'text-blue-500',
        },
    };

    const { icon: Icon, bg, border, text, iconColor } = config[message.type];

    return (
        <div
            className={`fixed top-4 right-4 z-[100] max-w-md transition-all duration-300 ${
                visible
                    ? 'opacity-100 translate-x-0'
                    : 'opacity-0 translate-x-4'
            }`}
        >
            <div
                className={`${bg} ${border} border rounded-xl p-4 shadow-lg`}
            >
                <div className="flex items-start gap-3">
                    <Icon className={`h-5 w-5 ${iconColor} shrink-0 mt-0.5`} />
                    <p className={`text-sm font-medium ${text} flex-1`}>
                        {message.text}
                    </p>
                    <button
                        onClick={() => {
                            setVisible(false);
                            setTimeout(() => setMessage(null), 300);
                        }}
                        className={`shrink-0 ${iconColor} hover:opacity-70`}
                    >
                        <XMarkIcon className="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    );
}
