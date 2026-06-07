import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import StatsCard from '../../Components/StatsCard';
import StatusBadge from '../../Components/StatusBadge';
import {
    ClipboardDocumentListIcon,
    ExclamationTriangleIcon,
    DocumentTextIcon,
    PlusCircleIcon,
    CubeIcon,
    CurrencyDollarIcon,
    CheckCircleIcon,
    WrenchScrewdriverIcon,
    ShoppingBagIcon,
    TruckIcon,
    ArrowTrendingUpIcon,
} from '@heroicons/react/24/outline';
import { PageProps, DashboardStats, ServiceOrder, Quotation, Product } from '../../types';

interface AdminDashboardProps extends PageProps {
    stats: DashboardStats;
    recent_orders: ServiceOrder[];
    low_stock_products: Product[];
    recent_quotations: Quotation[];
}

export default function AdminDashboard({
    stats,
    recent_orders,
    low_stock_products,
    recent_quotations,
}: AdminDashboardProps) {
    return (
        <>
            <Head title="Panel de Administración" />
            <AuthenticatedLayout
                header={
                    <h1 className="text-xl font-semibold text-gray-900">
                        Panel de Administración
                    </h1>
                }
            >
                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <StatsCard
                        title="Total Órdenes"
                        value={stats.total_orders}
                        icon={ClipboardDocumentListIcon}
                        iconBg="bg-primary-100"
                        iconColor="text-primary-600"
                    />
                    <StatsCard
                        title="En Progreso"
                        value={stats.active_orders}
                        icon={WrenchScrewdriverIcon}
                        iconBg="bg-yellow-100"
                        iconColor="text-yellow-600"
                    />
                    <StatsCard
                        title="Completadas Mes"
                        value={stats.completed_this_month}
                        icon={CheckCircleIcon}
                        iconBg="bg-green-100"
                        iconColor="text-green-600"
                    />
                    <StatsCard
                        title="Ingresos Mes"
                        value={`$${stats.monthly_revenue.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`}
                        icon={CurrencyDollarIcon}
                        iconBg="bg-orange-100"
                        iconColor="text-orange-600"
                    />
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    {/* Recent Orders */}
                    <div className="lg:col-span-2 card p-0 overflow-hidden">
                        <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h2 className="text-lg font-semibold text-gray-900">
                                Órdenes Recientes
                            </h2>
                            <Link
                                href="/admin/ordenes"
                                className="text-sm font-medium text-primary-600 hover:text-primary-700"
                            >
                                Ver todas
                            </Link>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th className="table-header px-6 py-3">Orden</th>
                                        <th className="table-header px-6 py-3">Vehículo</th>
                                        <th className="table-header px-6 py-3">Cliente</th>
                                        <th className="table-header px-6 py-3">Estado</th>
                                        <th className="table-header px-6 py-3">Prioridad</th>
                                        <th className="table-header px-6 py-3">Fecha</th>
                                        <th className="table-header px-6 py-3 text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {recent_orders.length === 0 ? (
                                        <tr>
                                            <td colSpan={7} className="px-6 py-12 text-center text-gray-500">
                                                No hay órdenes recientes.
                                            </td>
                                        </tr>
                                    ) : (
                                        recent_orders.map((order) => (
                                            <tr key={order.id} className="hover:bg-gray-50 transition-colors">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {order.order_number}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {order.vehicle?.plate || '—'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {order.client?.name || '—'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <StatusBadge
                                                        status={order.status}
                                                        label={order.status_label}
                                                    />
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                                            order.priority === 'high'
                                                                ? 'bg-red-100 text-red-800'
                                                                : order.priority === 'medium'
                                                                ? 'bg-yellow-100 text-yellow-800'
                                                                : 'bg-gray-100 text-gray-800'
                                                        }`}
                                                    >
                                                        {order.priority_label || order.priority}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {new Date(order.created_at).toLocaleDateString('es-MX', {
                                                        day: '2-digit',
                                                        month: 'short',
                                                        year: 'numeric',
                                                    })}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                    <Link
                                                        href={`/orders/${order.id}`}
                                                        className="text-primary-600 hover:text-primary-700 font-medium"
                                                    >
                                                        Ver
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Right Column */}
                    <div className="space-y-6">
                        {/* Low Stock Alerts */}
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                                <ExclamationTriangleIcon className="h-5 w-5 text-red-500" />
                                <h2 className="text-lg font-semibold text-gray-900">
                                    Alertas de Stock
                                </h2>
                            </div>
                            <div className="p-4">
                                {low_stock_products.length === 0 ? (
                                    <p className="text-sm text-gray-500 text-center py-4">
                                        No hay alertas de stock bajo.
                                    </p>
                                ) : (
                                    <ul className="space-y-3">
                                        {low_stock_products.map((product) => (
                                            <li key={product.id} className="flex items-center justify-between p-2 rounded-lg bg-red-50 border border-red-100">
                                                <div className="min-w-0">
                                                    <p className="text-sm font-medium text-gray-900 truncate">
                                                        {product.name}
                                                    </p>
                                                    <p className="text-xs text-gray-500">
                                                        Stock: {product.stock} / Mín: {product.min_stock}
                                                    </p>
                                                </div>
                                                <span className="badge-red ml-2 shrink-0">
                                                    {product.stock === 0 ? 'Agotado' : 'Bajo'}
                                                </span>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </div>
                        </div>

                        {/* Recent Quotations */}
                        <div className="card p-0 overflow-hidden">
                            <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <h2 className="text-lg font-semibold text-gray-900">
                                    Cotizaciones Recientes
                                </h2>
                                <Link
                                    href="/admin/cotizaciones"
                                    className="text-sm font-medium text-primary-600 hover:text-primary-700"
                                >
                                    Ver todas
                                </Link>
                            </div>
                            <div className="p-4">
                                {recent_quotations.length === 0 ? (
                                    <p className="text-sm text-gray-500 text-center py-4">
                                        No hay cotizaciones recientes.
                                    </p>
                                ) : (
                                    <ul className="space-y-3">
                                        {recent_quotations.slice(0, 5).map((quotation) => (
                                            <li key={quotation.id} className="flex items-center justify-between">
                                                <div>
                                                    <p className="text-sm font-medium text-gray-900">
                                                        {quotation.quotation_number}
                                                    </p>
                                                    <p className="text-xs text-gray-500">
                                                        {quotation.client?.name || '—'}
                                                    </p>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <StatusBadge
                                                        status={quotation.status}
                                                        label={quotation.status_label}
                                                    />
                                                    <span className="text-sm font-semibold text-gray-900">
                                                        ${quotation.total.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                                    </span>
                                                </div>
                                            </li>
                                        ))}
                                    </ul>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="card">
                    <h2 className="text-lg font-semibold text-gray-900 mb-4">
                        Acciones Rápidas
                    </h2>
                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <Link
                            href="/admin/ordenes/crear"
                            className="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-primary-300 hover:border-primary-500 hover:bg-primary-50 transition-all group"
                        >
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 group-hover:bg-primary-200 transition-colors">
                                <PlusCircleIcon className="h-5 w-5 text-primary-600" />
                            </div>
                            <span className="text-sm font-medium text-gray-700 group-hover:text-primary-700">
                                Nueva Orden
                            </span>
                        </Link>
                        <Link
                            href="/admin/productos/crear"
                            className="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-secondary-300 hover:border-secondary-500 hover:bg-secondary-50 transition-all group"
                        >
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-secondary-100 group-hover:bg-secondary-200 transition-colors">
                                <CubeIcon className="h-5 w-5 text-secondary-600" />
                            </div>
                            <span className="text-sm font-medium text-gray-700 group-hover:text-secondary-700">
                                Nuevo Producto
                            </span>
                        </Link>
                        <Link
                            href="/admin/cotizaciones/crear"
                            className="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-green-300 hover:border-green-500 hover:bg-green-50 transition-all group"
                        >
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 group-hover:bg-green-200 transition-colors">
                                <DocumentTextIcon className="h-5 w-5 text-green-600" />
                            </div>
                            <span className="text-sm font-medium text-gray-700 group-hover:text-green-700">
                                Nueva Cotización
                            </span>
                        </Link>
                        <Link
                            href="/admin/ventas/crear"
                            className="flex items-center gap-3 p-4 rounded-xl border-2 border-dashed border-purple-300 hover:border-purple-500 hover:bg-purple-50 transition-all group"
                        >
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100 group-hover:bg-purple-200 transition-colors">
                                <ShoppingBagIcon className="h-5 w-5 text-purple-600" />
                            </div>
                            <span className="text-sm font-medium text-gray-700 group-hover:text-purple-700">
                                Nueva Venta
                            </span>
                        </Link>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
