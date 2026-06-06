import React from 'react';
import { Head, Link, useForm, router } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { ArrowLeftIcon, TrashIcon, NoSymbolIcon, PhotoIcon } from '@heroicons/react/24/outline';
import { PageProps, Product, SelectOption } from '../../../types';

interface ProductsEditProps extends PageProps {
    product: Product;
    categories: SelectOption[];
    units: SelectOption[];
}

export default function ProductsEdit({ product, categories, units }: ProductsEditProps) {
    const { data, setData, put, processing, errors } = useForm({
        name: product.name,
        sku: product.sku || '',
        category: product.category,
        brand: '',
        description: product.description || '',
        price: product.price.toString(),
        cost: product.cost?.toString() || '',
        stock: product.stock.toString(),
        min_stock: product.min_stock.toString(),
        unit: product.unit || 'pieza',
        is_service: false,
        is_active: product.is_active,
        image: null as File | null,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/products/${product.id}`);
    };

    const handleDelete = () => {
        if (confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.')) {
            router.delete(`/products/${product.id}`);
        }
    };

    const handleDeactivate = () => {
        if (confirm('¿Estás seguro de desactivar este producto?')) {
            router.put(`/products/${product.id}/toggle-active`);
        }
    };

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            setData('image', e.target.files[0]);
        }
    };

    const inputClass = (field: string) =>
        `input-field px-4 py-2.5 ${errors[field as keyof typeof errors] ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`;

    return (
        <>
            <Head title="Editar Producto" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href={`/products/${product.id}`}
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Editar Producto
                        </h1>
                    </div>
                }
            >
                <div className="max-w-3xl mx-auto">
                    <form onSubmit={handleSubmit} className="card space-y-6">
                        {/* Image Upload */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Imagen del Producto
                            </label>
                            <div className="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer">
                                <label htmlFor="image-upload" className="flex flex-col items-center gap-2 cursor-pointer">
                                    <PhotoIcon className="h-10 w-10 text-gray-400" />
                                    <span className="text-sm text-gray-500">
                                        {data.image ? data.image.name : 'Haz clic para cambiar la imagen'}
                                    </span>
                                </label>
                                <input
                                    id="image-upload"
                                    type="file"
                                    accept="image/*"
                                    onChange={handleImageChange}
                                    className="hidden"
                                />
                            </div>
                            {errors.image && (
                                <p className="mt-1 text-sm text-red-600">{errors.image}</p>
                            )}
                        </div>

                        {/* Name and SKU */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nombre *
                                </label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className={inputClass('name')}
                                />
                                {errors.name && (
                                    <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKU
                                </label>
                                <input
                                    type="text"
                                    value={data.sku}
                                    onChange={(e) => setData('sku', e.target.value)}
                                    className={inputClass('sku')}
                                />
                                {errors.sku && (
                                    <p className="mt-1 text-sm text-red-600">{errors.sku}</p>
                                )}
                            </div>
                        </div>

                        {/* Category and Brand */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Categoría *
                                </label>
                                <select
                                    value={data.category}
                                    onChange={(e) => setData('category', e.target.value)}
                                    className={inputClass('category')}
                                >
                                    <option value="">Seleccionar categoría...</option>
                                    {categories.map((cat) => (
                                        <option key={cat.value} value={cat.value}>
                                            {cat.label}
                                        </option>
                                    ))}
                                </select>
                                {errors.category && (
                                    <p className="mt-1 text-sm text-red-600">{errors.category}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Marca
                                </label>
                                <input
                                    type="text"
                                    value={data.brand}
                                    onChange={(e) => setData('brand', e.target.value)}
                                    className={inputClass('brand')}
                                />
                                {errors.brand && (
                                    <p className="mt-1 text-sm text-red-600">{errors.brand}</p>
                                )}
                            </div>
                        </div>

                        {/* Description */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Descripción
                            </label>
                            <textarea
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                rows={3}
                                className={inputClass('description')}
                            />
                            {errors.description && (
                                <p className="mt-1 text-sm text-red-600">{errors.description}</p>
                            )}
                        </div>

                        {/* Price and Cost */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Precio de Venta *
                                </label>
                                <div className="relative">
                                    <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input
                                        type="number"
                                        value={data.price}
                                        onChange={(e) => setData('price', e.target.value)}
                                        className={`input-field pl-7 py-2.5 ${errors.price ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                        step="0.01"
                                        min="0"
                                    />
                                </div>
                                {errors.price && (
                                    <p className="mt-1 text-sm text-red-600">{errors.price}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Costo
                                </label>
                                <div className="relative">
                                    <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                                    <input
                                        type="number"
                                        value={data.cost}
                                        onChange={(e) => setData('cost', e.target.value)}
                                        className={`input-field pl-7 py-2.5 ${errors.cost ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                        step="0.01"
                                        min="0"
                                    />
                                </div>
                                {errors.cost && (
                                    <p className="mt-1 text-sm text-red-600">{errors.cost}</p>
                                )}
                            </div>
                        </div>

                        {/* Stock and Min Stock */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Cantidad en Stock *
                                </label>
                                <input
                                    type="number"
                                    value={data.stock}
                                    onChange={(e) => setData('stock', e.target.value)}
                                    className={inputClass('stock')}
                                    min="0"
                                />
                                {errors.stock && (
                                    <p className="mt-1 text-sm text-red-600">{errors.stock}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Stock Mínimo de Alerta
                                </label>
                                <input
                                    type="number"
                                    value={data.min_stock}
                                    onChange={(e) => setData('min_stock', e.target.value)}
                                    className={inputClass('min_stock')}
                                    min="0"
                                />
                                {errors.min_stock && (
                                    <p className="mt-1 text-sm text-red-600">{errors.min_stock}</p>
                                )}
                            </div>
                        </div>

                        {/* Unit */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Unidad
                            </label>
                            <select
                                value={data.unit}
                                onChange={(e) => setData('unit', e.target.value)}
                                className={inputClass('unit')}
                            >
                                {units.map((u) => (
                                    <option key={u.value} value={u.value}>
                                        {u.label}
                                    </option>
                                ))}
                            </select>
                        </div>

                        {/* Checkboxes */}
                        <div className="flex items-center gap-6">
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.is_service}
                                    onChange={(e) => setData('is_service', e.target.checked)}
                                    className="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                />
                                <span className="text-sm text-gray-700">Es un servicio</span>
                            </label>
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.is_active}
                                    onChange={(e) => setData('is_active', e.target.checked)}
                                    className="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                />
                                <span className="text-sm text-gray-700">Producto activo</span>
                            </label>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div className="flex items-center gap-3">
                                {data.is_active && (
                                    <button
                                        type="button"
                                        onClick={handleDeactivate}
                                        className="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors"
                                    >
                                        <NoSymbolIcon className="h-4 w-4" />
                                        Desactivar
                                    </button>
                                )}
                                <button
                                    type="button"
                                    onClick={handleDelete}
                                    className="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                                >
                                    <TrashIcon className="h-4 w-4" />
                                    Eliminar
                                </button>
                            </div>
                            <div className="flex items-center gap-3">
                                <Link
                                    href={`/products/${product.id}`}
                                    className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {processing ? 'Guardando...' : 'Actualizar Producto'}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
