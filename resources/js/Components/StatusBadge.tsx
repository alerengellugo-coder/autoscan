import React from 'react';

type BadgeVariant =
    | 'green'
    | 'yellow'
    | 'red'
    | 'blue'
    | 'gray'
    | 'orange'
    | 'purple';

interface StatusBadgeProps {
    status: string;
    label?: string;
    variant?: BadgeVariant;
    size?: 'sm' | 'md';
}

const statusColorMap: Record<string, BadgeVariant> = {
    // Order statuses
    pending: 'yellow',
    diagnosing: 'blue',
    in_progress: 'blue',
    waiting_parts: 'orange',
    quality_check: 'purple',
    completed: 'green',
    delivered: 'green',
    cancelled: 'red',
    // Quotation statuses
    draft: 'gray',
    pending_client: 'yellow',
    approved: 'green',
    rejected: 'red',
    expired: 'gray',
    // Sale statuses
    paid: 'green',
    partially_paid: 'blue',
    // Vehicle statuses
    active: 'green',
    in_service: 'blue',
    sold: 'gray',
    inactive: 'red',
    // Stock statuses
    available: 'green',
    low: 'yellow',
    out: 'red',
};

const variantClasses: Record<BadgeVariant, string> = {
    green: 'bg-green-100 text-green-800',
    yellow: 'bg-yellow-100 text-yellow-800',
    red: 'bg-red-100 text-red-800',
    blue: 'bg-blue-100 text-blue-800',
    gray: 'bg-gray-100 text-gray-800',
    orange: 'bg-orange-100 text-orange-800',
    purple: 'bg-purple-100 text-purple-800',
};

export default function StatusBadge({
    status,
    label,
    variant,
    size = 'sm',
}: StatusBadgeProps) {
    const resolvedVariant =
        variant || statusColorMap[status] || 'gray';

    const sizeClasses =
        size === 'sm'
            ? 'px-2 py-0.5 text-xs'
            : 'px-3 py-1 text-sm';

    return (
        <span
            className={`inline-flex items-center font-medium rounded-full ${variantClasses[resolvedVariant]} ${sizeClasses}`}
        >
            {label || status}
        </span>
    );
}
