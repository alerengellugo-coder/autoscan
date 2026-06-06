import React from 'react';
import {
    CheckCircleIcon,
    ClockIcon,
    XCircleIcon,
} from '@heroicons/react/24/solid';

interface TimelineEntry {
    status: string;
    label: string;
    description?: string;
    date?: string;
    completed: boolean;
    current?: boolean;
}

interface OrderTimelineProps {
    entries: TimelineEntry[];
}

export default function OrderTimeline({ entries }: OrderTimelineProps) {
    return (
        <div className="flow-root">
            <ul className="-mb-8">
                {entries.map((entry, index) => (
                    <li key={entry.status}>
                        <div className="relative pb-8">
                            {index < entries.length - 1 && (
                                <span
                                    className={`absolute top-4 left-4 -ml-px h-full w-0.5 ${
                                        entry.completed
                                            ? 'bg-primary-500'
                                            : 'bg-gray-200'
                                    }`}
                                    aria-hidden="true"
                                />
                            )}
                            <div className="relative flex space-x-3">
                                <div>
                                    {entry.completed ? (
                                        <span
                                            className={`flex h-8 w-8 items-center justify-center rounded-full ring-8 ring-white ${
                                                entry.current
                                                    ? 'bg-primary-500'
                                                    : 'bg-green-500'
                                            }`}
                                        >
                                            <CheckCircleIcon
                                                className="h-5 w-5 text-white"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    ) : (
                                        <span className="flex h-8 w-8 items-center justify-center rounded-full ring-8 ring-white bg-gray-300">
                                            {entry.current ? (
                                                <ClockIcon
                                                    className="h-5 w-5 text-white"
                                                    aria-hidden="true"
                                                />
                                            ) : (
                                                <XCircleIcon
                                                    className="h-5 w-5 text-gray-400"
                                                    aria-hidden="true"
                                                />
                                            )}
                                        </span>
                                    )}
                                </div>
                                <div
                                    className={`flex min-w-0 flex-1 justify-between space-x-4 pt-1.5 ${
                                        entry.current ? '' : 'opacity-50'
                                    }`}
                                >
                                    <div>
                                        <p
                                            className={`text-sm font-medium ${
                                                entry.completed
                                                    ? 'text-gray-900'
                                                    : 'text-gray-500'
                                            }`}
                                        >
                                            {entry.label}
                                        </p>
                                        {entry.description && (
                                            <p className="mt-1 text-sm text-gray-500">
                                                {entry.description}
                                            </p>
                                        )}
                                    </div>
                                    {entry.date && (
                                        <div className="whitespace-nowrap text-right text-sm text-gray-500">
                                            <time>
                                                {new Date(
                                                    entry.date
                                                ).toLocaleDateString('es-ES', {
                                                    day: 'numeric',
                                                    month: 'short',
                                                    year: 'numeric',
                                                })}
                                            </time>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
}
