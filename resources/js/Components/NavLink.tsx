import React from 'react';
import { Link, usePage } from '@inertiajs/react';

interface NavLinkProps {
    href: string;
    active?: string;
    children: React.ReactNode;
    className?: string;
}

export default function NavLink({
    href,
    active,
    children,
    className = '',
}: NavLinkProps) {
    const { ziggy } = usePage().props as Record<string, unknown>;
    const currentUrl = typeof window !== 'undefined' ? window.location.pathname : '';

    const isActive =
        active !== undefined
            ? currentUrl.startsWith(active)
            : currentUrl === href;

    return (
        <Link
            href={href}
            className={`inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 ${
                isActive
                    ? 'border-primary-500 text-primary-600'
                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
            } ${className}`}
        >
            {children}
        </Link>
    );
}
