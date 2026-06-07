import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { ArrowLeftIcon, PhotoIcon } from '@heroicons/react/24/outline';
import { PageProps, SelectOption } from '../../../types';

interface ProductsCreateProps extends PageProps {
    categories: SelectOption[];
    units: SelectOption[];
}

export default function ProductsCreate({ categories, units }: ProductsCreateProps) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        sku: '',
        category: '',
        brand: '',
        description: '',
        price: '',
        cost: '',
        stock: '0',
        min_stock: '5',
        unit: 'pieza',
        is_service: false,
        is_active: true,
        image: null as File | null,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/productos');
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
            <Head title="Nuevo Producto" />
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
                            Nuevo Producto
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
                                        Haz clic para subir una imagen
                                    </span>
                                    <span className="text-xs text-gray-400">
                                        PNG, JPG hasta 2MB
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
                                    placeholder="Nombre del producto"
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
                                    placeholder="Código SKU"
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
                                    placeholder="Marca del producto"
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
                                placeholder="Descripción del producto..."
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
                                        placeholder="0.00"
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
                                        placeholder="0.00"
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
                            {errors.unit && (
                                <p className="mt-1 text-sm text-red-600">{errors.unit}</p>
                            )}
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
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <Link
                                href="/admin/productos"
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Guardando...' : 'Guardar Producto'}
                            </button>
                        </div>
                    </form>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
