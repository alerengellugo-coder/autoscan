import React from 'react';

interface EmptyStateProps {
    title: string;
    description?: string;
    icon?: React.ElementType;
    action?: {
        label: string;
        href?: string;
        onClick?: () => void;
    };
}

export default function EmptyState({
    title,
    description,
    icon: Icon,
    action,
}: EmptyStateProps) {
    return (
        <div className="flex flex-col items-center justify-center py-16 px-4">
            {Icon && (
                <div className="flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 mb-6">
                    <Icon className="h-10 w-10 text-gray-400" />
                </div>
            )}
            <h3 className="text-lg font-semibold text-gray-900 mb-2">
                {title}
            </h3>
            {description && (
                <p className="text-sm text-gray-500 text-center max-w-md mb-6">
                    {description}
                </p>
            )}
            {action && (
                <button
                    onClick={action.onClick}
                    className="btn-primary"
                >
                    {action.label}
                </button>
            )}
        </div>
    );
}
