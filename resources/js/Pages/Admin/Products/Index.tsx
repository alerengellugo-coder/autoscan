import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import {
    PlusCircleIcon,
    MagnifyingGlassIcon,
    EyeIcon,
    PencilSquareIcon,
    TrashIcon,
    CubeIcon,
    ExclamationTriangleIcon,
    CurrencyDollarIcon,
} from '@heroicons/react/24/outline';
import { PageProps, Product, PaginationData, SelectOption } from '../../../types';

interface ProductsIndexProps extends PageProps {
    products: PaginationData<Product>;
    categories: SelectOption[];
    total_products: number;
    low_stock_count: number;
    total_inventory_value: number;
    filters: {
        search?: string;
        category?: string;
    };
}

function getStockBadge(stock: number, minStock: number) {
    if (stock === 0) {
        return <span className="badge-red">Agotado</span>;
    }
    if (stock < minStock) {
        return <span className="badge-yellow">Bajo</span>;
    }
    return <span className="badge-green">Disponible</span>;
}

export default function ProductsIndex({
    products,
    categories = [],
    total_products,
    low_stock_count,
    total_inventory_value,
    filters,
}: ProductsIndexProps) {
    const [search, setSearch] = useState(filters.search || '');
    const [category, setCategory] = useState(filters.category || '');

    const handleFilter = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/admin/productos', { search, category }, { preserveState: true });
    };

    return (
        <>
            <Head title="Gestión de Productos" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-900">
                            Gestión de Productos
                        </h1>
                        <Link
                            href="/admin/productos/create"
                            className="inline-flex items-center gap-2 btn-primary"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Nuevo Producto
                        </Link>
                    </div>
                }
            >
                {/* Stats */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div className="stat-card">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-100">
                            <CubeIcon className="h-6 w-6 text-primary-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500">Total Productos</p>
                            <p className="text-2xl font-bold text-gray-900">{total_products}</p>
                        </div>
                    </div>
                    <div className="stat-card">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100">
                            <ExclamationTriangleIcon className="h-6 w-6 text-red-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500">Stock Bajo</p>
                            <p className="text-2xl font-bold text-gray-900">{low_stock_count}</p>
                        </div>
                    </div>
                    <div className="stat-card">
                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-green-100">
                            <CurrencyDollarIcon className="h-6 w-6 text-green-600" />
                        </div>
                        <div className="flex-1 min-w-0">
                            <p className="text-sm font-medium text-gray-500">Valor del Inventario</p>
                            <p className="text-2xl font-bold text-gray-900">
                                ${total_inventory_value.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                            </p>
                        </div>
                    </div>
                </div>

                {/* Search and Filter */}
                <div className="card mb-6">
                    <form onSubmit={handleFilter} className="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <div className="relative flex-1">
                            <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Buscar por nombre o SKU..."
                                className="input-field pl-10 py-2.5"
                            />
                        </div>
                        <select
                            value={category}
                            onChange={(e) => setCategory(e.target.value)}
                            className="input-field px-4 py-2.5"
                        >
                            <option value="">Todas las categorías</option>
                            {categories.map((cat) => (
                                <option key={cat.value} value={cat.value}>{cat.label}</option>
                            ))}
                        </select>
                        <button type="submit" className="btn-primary">
                            Buscar
                        </button>
                    </form>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3">Imagen</th>
                                    <th className="table-header px-6 py-3">Nombre</th>
                                    <th className="table-header px-6 py-3">SKU</th>
                                    <th className="table-header px-6 py-3">Categoría</th>
                                    <th className="table-header px-6 py-3">Precio</th>
                                    <th className="table-header px-6 py-3">Costo</th>
                                    <th className="table-header px-6 py-3">Stock</th>
                                    <th className="table-header px-6 py-3">Estado</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {products.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={9} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron productos.
                                        </td>
                                    </tr>
                                ) : (
                                    products.data.map((product) => (
                                        <tr key={product.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                    <CubeIcon className="h-5 w-5 text-gray-400" />
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {product.name}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {product.sku || '—'}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                                    {product.category_label || product.category}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {product.formatted_price || `$${product.price.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {product.formatted_cost || (product.cost ? `$${product.cost.toLocaleString('es-MX', { minimumFractionDigits: 2 })}` : '—')}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center gap-2">
                                                    <span className="text-sm text-gray-900">{product.stock}</span>
                                                    {getStockBadge(product.stock, product.min_stock)}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {product.is_active ? (
                                                    <span className="badge-green">Activo</span>
                                                ) : (
                                                    <span className="badge-red">Inactivo</span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right">
                                                <div className="flex items-center justify-end gap-2">
                                                    <Link
                                                        href={`/admin/productos/${product.id}`}
                                                        className="text-primary-600 hover:text-primary-700 p-1 rounded-lg hover:bg-primary-50 transition-colors"
                                                        title="Ver"
                                                    >
                                                        <EyeIcon className="h-4 w-4" />
                                                    </Link>
                                                    <Link
                                                        href={`/admin/productos/${product.id}/edit`}
                                                        className="text-yellow-600 hover:text-yellow-700 p-1 rounded-lg hover:bg-yellow-50 transition-colors"
                                                        title="Editar"
                                                    >
                                                        <PencilSquareIcon className="h-4 w-4" />
                                                    </Link>
                                                    <button
                                                        onClick={() => {
                                                            if (confirm('¿Estás seguro de eliminar este producto?')) {
                                                                router.delete(`/admin/productos/${product.id}`);
                                                            }
                                                        }}
                                                        className="text-red-600 hover:text-red-700 p-1 rounded-lg hover:bg-red-50 transition-colors"
                                                        title="Eliminar"
                                                    >
                                                        <TrashIcon className="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {products.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {products.from} a {products.to} de {products.total} resultados
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={products.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        products.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {products.current_page} de {products.last_page}
                                </span>
                                <Link
                                    href={products.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        products.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Siguiente
                                </Link>
                            </div>
                        </div>
                    )}
                </div>
            </AuthenticatedLayout>
        </>
    );
}
