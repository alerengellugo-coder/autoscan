import React, { useState, useMemo } from 'react';
import { Link } from '@inertiajs/react';
import {
    MagnifyingGlassIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    FunnelIcon,
} from '@heroicons/react/24/outline';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
    render?: (item: Record<string, unknown>) => React.ReactNode;
}

interface DataTableProps<T extends Record<string, unknown>> {
    title?: string;
    columns: Column[];
    data: T[];
    searchable?: boolean;
    searchPlaceholder?: string;
    pagination?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    actions?: (item: T) => React.ReactNode;
    emptyMessage?: string;
    onSearch?: (term: string) => void;
    filters?: React.ReactNode;
}

export default function DataTable<T extends Record<string, unknown>>({
    title,
    columns,
    data,
    searchable = true,
    searchPlaceholder = 'Buscar...',
    pagination,
    actions,
    emptyMessage = 'No se encontraron registros.',
    onSearch,
    filters,
}: DataTableProps<T>) {
    const [search, setSearch] = useState('');

    const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setSearch(e.target.value);
        onSearch?.(e.target.value);
    };

    return (
        <div className="card p-0 overflow-hidden">
            {/* Header */}
            {(title || searchable || filters) && (
                <div className="px-6 py-4 border-b border-gray-200">
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        {title && (
                            <h3 className="text-lg font-semibold text-gray-900">
                                {title}
                            </h3>
                        )}
                        <div className="flex items-center gap-3">
                            {searchable && (
                                <div className="relative flex-1 sm:flex-initial">
                                    <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                                    <input
                                        type="text"
                                        value={search}
                                        onChange={handleSearchChange}
                                        placeholder={searchPlaceholder}
                                        className="input-field pl-10 py-2 text-sm sm:w-64"
                                    />
                                </div>
                            )}
                            {filters && (
                                <div className="flex items-center gap-2">
                                    <FunnelIcon className="h-4 w-4 text-gray-400" />
                                    {filters}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            )}

            {/* Table */}
            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            {columns.map((col) => (
                                <th
                                    key={col.key}
                                    className="table-header px-6 py-3"
                                >
                                    {col.label}
                                </th>
                            ))}
                            {actions && (
                                <th className="table-header px-6 py-3 text-right">
                                    Acciones
                                </th>
                            )}
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {data.length === 0 ? (
                            <tr>
                                <td
                                    colSpan={columns.length + (actions ? 1 : 0)}
                                    className="px-6 py-12 text-center text-gray-500"
                                >
                                    <div className="flex flex-col items-center gap-2">
                                        <svg
                                            className="h-12 w-12 text-gray-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={1}
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                        <p>{emptyMessage}</p>
                                    </div>
                                </td>
                            </tr>
                        ) : (
                            data.map((item, index) => (
                                <tr
                                    key={(item as Record<string, unknown>).id as number || index}
                                    className="hover:bg-gray-50 transition-colors"
                                >
                                    {columns.map((col) => (
                                        <td
                                            key={col.key}
                                            className="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                        >
                                            {col.render
                                                ? col.render(item)
                                                : (item[col.key] as React.ReactNode) || '—'}
                                        </td>
                                    ))}
                                    {actions && (
                                        <td className="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            {actions(item)}
                                        </td>
                                    )}
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>

            {/* Pagination */}
            {pagination && pagination.last_page > 1 && (
                <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <div className="text-sm text-gray-500">
                        Mostrando{' '}
                        <span className="font-medium">
                            {(pagination.current_page - 1) * pagination.per_page + 1}
                        </span>{' '}
                        a{' '}
                        <span className="font-medium">
                            {Math.min(
                                pagination.current_page * pagination.per_page,
                                pagination.total
                            )}
                        </span>{' '}
                        de{' '}
                        <span className="font-medium">
                            {pagination.total}
                        </span>{' '}
                        resultados
                    </div>
                    <div className="flex items-center gap-2">
                        <Link
                            href={pagination.prev_page_url || '#'}
                            className={`inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                pagination.prev_page_url
                                    ? 'text-gray-700 hover:bg-gray-50'
                                    : 'text-gray-300 cursor-not-allowed pointer-events-none'
                            }`}
                            preserveState
                        >
                            <ChevronLeftIcon className="h-4 w-4" />
                            Anterior
                        </Link>
                        <span className="text-sm text-gray-700">
                            Página {pagination.current_page} de{' '}
                            {pagination.last_page}
                        </span>
                        <Link
                            href={pagination.next_page_url || '#'}
                            className={`inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                pagination.next_page_url
                                    ? 'text-gray-700 hover:bg-gray-50'
                                    : 'text-gray-300 cursor-not-allowed pointer-events-none'
                            }`}
                            preserveState
                        >
                            Siguiente
                            <ChevronRightIcon className="h-4 w-4" />
                        </Link>
                    </div>
                </div>
            )}
        </div>
    );
}
