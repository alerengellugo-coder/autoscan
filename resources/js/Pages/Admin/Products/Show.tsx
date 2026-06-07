import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import StatusBadge from '../../../Components/StatusBadge';
import {
    ArrowLeftIcon,
    PencilSquareIcon,
    PhotoIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Product } from '../../../types';

interface ProductsShowProps extends PageProps {
    product: Product;
}

export default function ProductsShow({ product }: ProductsShowProps) {
    const profitMargin = product.profit_margin !== undefined
        ? product.profit_margin
        : product.cost && product.cost > 0
        ? ((product.price - product.cost) / product.price) * 100
        : 0;

    return (
        <>
            <Head title={product.name} />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/productos"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            {product.name}
                        </h1>
                    </div>
                }
            >
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left - Product Details */}
                    <div className="lg:col-span-1 space-y-6">
                        {/* Image */}
                        <div className="card">
                            <div className="flex items-center justify-center h-48 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                <PhotoIcon className="h-16 w-16 text-gray-300" />
                            </div>
                        </div>

                        {/* Details Card */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Información del Producto
                            </h3>
                            <div className="space-y-3">
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Nombre</span>
                                    <span className="text-sm font-semibold text-gray-900">{product.name}</span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">SKU</span>
                                    <span className="text-sm font-semibold text-gray-900">{product.sku || '—'}</span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Categoría</span>
                                    <span className="text-sm font-semibold text-gray-900">
                                        {product.category_label || product.category}
                                    </span>
                                </div>
                                <hr className="border-gray-200" />
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Precio</span>
                                    <span className="text-sm font-bold text-primary-600">
                                        {product.formatted_price || `$${product.price.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`}
                                    </span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Costo</span>
                                    <span className="text-sm font-semibold text-gray-900">
                                        {product.formatted_cost || (product.cost ? `$${product.cost.toLocaleString('es-MX', { minimumFractionDigits: 2 })}` : '—')}
                                    </span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Margen de Ganancia</span>
                                    <span className={`text-sm font-semibold ${profitMargin >= 30 ? 'text-green-600' : profitMargin >= 15 ? 'text-yellow-600' : 'text-red-600'}`}>
                                        {profitMargin.toFixed(1)}%
                                    </span>
                                </div>
                                <hr className="border-gray-200" />
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Stock Actual</span>
                                    <span className="text-sm font-bold text-gray-900">{product.stock}</span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Stock Mínimo</span>
                                    <span className="text-sm font-semibold text-gray-900">{product.min_stock}</span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Unidad</span>
                                    <span className="text-sm font-semibold text-gray-900">{product.unit || '—'}</span>
                                </div>
                                <div className="flex justify-between items-center">
                                    <span className="text-sm text-gray-500">Estado</span>
                                    <div className="flex items-center gap-2">
                                        <StatusBadge status={product.is_active ? 'available' : 'out'} label={product.is_active ? 'Activo' : 'Inactivo'} />
                                    </div>
                                </div>
                            </div>

                            {/* Actions */}
                            <div className="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
                                <Link
                                    href={`/admin/productos/${product.id}/edit`}
                                    className="flex-1 btn-primary py-2 text-center text-sm inline-flex items-center justify-center gap-1"
                                >
                                    <PencilSquareIcon className="h-4 w-4" />
                                    Editar
                                </Link>
                            </div>
                        </div>
                    </div>

                    {/* Right Column */}
                    <div className="lg:col-span-2 space-y-6">
                        {/* Description */}
                        {product.description && (
                            <div className="card">
                                <h3 className="text-lg font-semibold text-gray-900 mb-3">
                                    Descripción
                                </h3>
                                <p className="text-sm text-gray-600 leading-relaxed">
                                    {product.description}
                                </p>
                            </div>
                        )}

                        {/* Stock Info Card */}
                        <div className="card">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Información de Stock
                            </h3>
                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div className="p-4 rounded-xl bg-gray-50 text-center">
                                    <p className="text-xs text-gray-500 mb-1">Stock Actual</p>
                                    <p className="text-3xl font-bold text-gray-900">{product.stock}</p>
                                    <p className="text-xs text-gray-500 mt-1">{product.unit || 'piezas'}</p>
                                </div>
                                <div className="p-4 rounded-xl bg-gray-50 text-center">
                                    <p className="text-xs text-gray-500 mb-1">Stock Mínimo</p>
                                    <p className="text-3xl font-bold text-gray-900">{product.min_stock}</p>
                                    <p className="text-xs text-gray-500 mt-1">nivel de alerta</p>
                                </div>
                                <div className="p-4 rounded-xl bg-gray-50 text-center">
                                    <p className="text-xs text-gray-500 mb-1">Estado</p>
                                    <p className={`text-lg font-bold ${product.stock_status_color === 'success' ? 'text-green-600' : product.stock_status_color === 'warning' ? 'text-yellow-600' : 'text-red-600'}`}>
                                        {product.stock_status || 'N/A'}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
