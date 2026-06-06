import React from 'react';

interface StatsCardProps {
    title: string;
    value: string | number;
    icon: React.ElementType;
    trend?: {
        value: string;
        positive: boolean;
    };
    iconBg?: string;
    iconColor?: string;
}

export default function StatsCard({
    title,
    value,
    icon: Icon,
    trend,
    iconBg = 'bg-primary-100',
    iconColor = 'text-primary-600',
}: StatsCardProps) {
    return (
        <div className="stat-card hover:shadow-card-hover transition-shadow duration-200">
            <div
                className={`flex h-12 w-12 shrink-0 items-center justify-center rounded-xl ${iconBg}`}
            >
                <Icon className={`h-6 w-6 ${iconColor}`} />
            </div>
            <div className="flex-1 min-w-0">
                <p className="text-sm font-medium text-gray-500 truncate">
                    {title}
                </p>
                <div className="flex items-baseline gap-2">
                    <p className="text-2xl font-bold text-gray-900">
                        {typeof value === 'number' ? value.toLocaleString() : value}
                    </p>
                    {trend && (
                        <span
                            className={`text-xs font-medium ${
                                trend.positive
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            }`}
                        >
                            {trend.positive ? '↑' : '↓'} {trend.value}
                        </span>
                    )}
                </div>
            </div>
        </div>
    );
}
