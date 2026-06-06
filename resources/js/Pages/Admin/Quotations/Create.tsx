import React, { useState, useCallback } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { ArrowLeftIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline';
import { PageProps, Vehicle, SelectOption, Product } from '../../../types';

interface QuotationItemRow {
    product_id: string;
    item_type: string;
    name: string;
    description: string;
    quantity: number;
    unit_price: number;
    discount: number;
    total: number;
}

interface QuotationsCreateProps extends PageProps {
    clients: SelectOption[];
    vehicles: Vehicle[];
    service_orders: SelectOption[];
    products: Product[];
}

const initialItem: QuotationItemRow = {
    product_id: '',
    item_type: 'product',
    name: '',
    description: '',
    quantity: 1,
    unit_price: 0,
    discount: 0,
    total: 0,
};

export default function QuotationsCreate({
    clients,
    vehicles,
    service_orders,
    products,
}: QuotationsCreateProps) {
    const [items, setItems] = useState<QuotationItemRow[]>([{ ...initialItem }]);
    const [filteredVehicles, setFilteredVehicles] = useState<Vehicle[]>([]);

    const { data, setData, post, processing, errors } = useForm({
        client_id: '',
        vehicle_id: '',
        service_order_id: '',
        notes: '',
        valid_until: '',
        items: [] as QuotationItemRow[],
        tax_rate: 16,
    });

    const handleClientChange = (clientId: string) => {
        setData('client_id', clientId);
        const clientVehicles = vehicles.filter((v) => v.client_id === parseInt(clientId));
        setFilteredVehicles(clientVehicles);
        setData('vehicle_id', '');
    };

    const handleAddItem = () => {
        setItems((prev) => [...prev, { ...initialItem }]);
    };

    const handleRemoveItem = (index: number) => {
        setItems((prev) => prev.filter((_, i) => i !== index));
    };

    const handleItemChange = useCallback((index: number, field: keyof QuotationItemRow, value: string | number) => {
        setItems((prev) => {
            const updated = [...prev];
            const item = { ...updated[index], [field]: value };

            // Auto-fill product data
            if (field === 'product_id' && value) {
                const product = products.find((p) => p.id === parseInt(value as string));
                if (product) {
                    item.name = product.name;
                    item.unit_price = product.price;
                    item.description = product.description || '';
                }
            }

            // Calculate total
            item.total = (item.quantity * item.unit_price) - item.discount;

            updated[index] = item;
            return updated;
        });
    }, [products]);

    const subtotal = items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
    const totalDiscount = items.reduce((sum, item) => sum + item.discount, 0);
    const taxableAmount = subtotal - totalDiscount;
    const tax = taxableAmount * (data.tax_rate / 100);
    const total = taxableAmount + tax;

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/quotations', {
            ...data,
            items: items.map((item) => ({
                ...item,
                product_id: item.product_id ? parseInt(item.product_id) : null,
            })),
        });
    };

    const inputClass = (field: string) =>
        `input-field px-4 py-2.5 ${errors[field as keyof typeof errors] ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`;

    return (
        <>
            <Head title="Nueva Cotización" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/quotations"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Nueva Cotización
                        </h1>
                    </div>
                }
            >
                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left - Form Fields */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Client, Vehicle, Service Order */}
                            <div className="card space-y-4">
                                <h3 className="text-lg font-semibold text-gray-900">
                                    Información General
                                </h3>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                            Cliente *
                                        </label>
                                        <select
                                            value={data.client_id}
                                            onChange={(e) => handleClientChange(e.target.value)}
                                            className={inputClass('client_id')}
                                        >
                                            <option value="">Seleccionar cliente...</option>
                                            {clients.map((c) => (
                                                <option key={c.value} value={c.value}>{c.label}</option>
                                            ))}
                                        </select>
                                        {errors.client_id && (
                                            <p className="mt-1 text-sm text-red-600">{errors.client_id}</p>
                                        )}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                            Vehículo
                                        </label>
                                        <select
                                            value={data.vehicle_id}
                                            onChange={(e) => setData('vehicle_id', e.target.value)}
                                            className={inputClass('vehicle_id')}
                                        >
                                            <option value="">Seleccionar vehículo...</option>
                                            {filteredVehicles.map((v) => (
                                                <option key={v.id} value={v.id}>
                                                    {v.brand} {v.model} ({v.plate})
                                                </option>
                                            ))}
                                        </select>
                                        {errors.vehicle_id && (
                                            <p className="mt-1 text-sm text-red-600">{errors.vehicle_id}</p>
                                        )}
                                    </div>
                                </div>

                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                            Orden de Servicio (Opcional)
                                        </label>
                                        <select
                                            value={data.service_order_id}
                                            onChange={(e) => setData('service_order_id', e.target.value)}
                                            className={inputClass('service_order_id')}
                                        >
                                            <option value="">Seleccionar orden...</option>
                                            {service_orders.map((so) => (
                                                <option key={so.value} value={so.value}>{so.label}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                            Válido Hasta
                                        </label>
                                        <input
                                            type="date"
                                            value={data.valid_until}
                                            onChange={(e) => setData('valid_until', e.target.value)}
                                            className={inputClass('valid_until')}
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                        Notas
                                    </label>
                                    <textarea
                                        value={data.notes}
                                        onChange={(e) => setData('notes', e.target.value)}
                                        rows={3}
                                        className={inputClass('notes')}
                                        placeholder="Notas adicionales..."
                                    />
                                </div>
                            </div>

                            {/* Items Section */}
                            <div className="card p-0 overflow-hidden">
                                <div className="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                    <h3 className="text-lg font-semibold text-gray-900">
                                        Productos y Servicios
                                    </h3>
                                    <button
                                        type="button"
                                        onClick={handleAddItem}
                                        className="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700"
                                    >
                                        <PlusCircleIcon className="h-4 w-4" />
                                        Agregar Ítem
                                    </button>
                                </div>
                                <div className="overflow-x-auto">
                                    <table className="min-w-full">
                                        <thead>
                                            <tr className="bg-gray-50">
                                                <th className="table-header px-4 py-3 text-left">Producto / Manual</th>
                                                <th className="table-header px-4 py-3 text-left">Nombre</th>
                                                <th className="table-header px-4 py-3 text-left">Descripción</th>
                                                <th className="table-header px-4 py-3 text-center">Cant.</th>
                                                <th className="table-header px-4 py-3 text-right">Precio Unit.</th>
                                                <th className="table-header px-4 py-3 text-right">Descuento</th>
                                                <th className="table-header px-4 py-3 text-right">Total</th>
                                                <th className="table-header px-4 py-3 text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-gray-200">
                                            {items.map((item, index) => (
                                                <tr key={index}>
                                                    <td className="px-4 py-3">
                                                        <select
                                                            value={item.product_id}
                                                            onChange={(e) => handleItemChange(index, 'product_id', e.target.value)}
                                                            className="input-field px-2 py-1.5 text-sm min-w-[140px]"
                                                        >
                                                            <option value="">— Entrada manual —</option>
                                                            {products.map((p) => (
                                                                <option key={p.id} value={p.id}>
                                                                    {p.name} (${p.price})
                                                                </option>
                                                            ))}
                                                        </select>
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <input
                                                            type="text"
                                                            value={item.name}
                                                            onChange={(e) => handleItemChange(index, 'name', e.target.value)}
                                                            className="input-field px-2 py-1.5 text-sm min-w-[120px]"
                                                            placeholder="Nombre"
                                                        />
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <input
                                                            type="text"
                                                            value={item.description}
                                                            onChange={(e) => handleItemChange(index, 'description', e.target.value)}
                                                            className="input-field px-2 py-1.5 text-sm min-w-[120px]"
                                                            placeholder="Descripción"
                                                        />
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <input
                                                            type="number"
                                                            value={item.quantity}
                                                            onChange={(e) => handleItemChange(index, 'quantity', parseInt(e.target.value) || 0)}
                                                            className="input-field px-2 py-1.5 text-sm w-16 text-center"
                                                            min="1"
                                                        />
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <input
                                                            type="number"
                                                            value={item.unit_price}
                                                            onChange={(e) => handleItemChange(index, 'unit_price', parseFloat(e.target.value) || 0)}
                                                            className="input-field px-2 py-1.5 text-sm w-24 text-right"
                                                            step="0.01"
                                                            min="0"
                                                        />
                                                    </td>
                                                    <td className="px-4 py-3">
                                                        <input
                                                            type="number"
                                                            value={item.discount}
                                                            onChange={(e) => handleItemChange(index, 'discount', parseFloat(e.target.value) || 0)}
                                                            className="input-field px-2 py-1.5 text-sm w-20 text-right"
                                                            step="0.01"
                                                            min="0"
                                                        />
                                                    </td>
                                                    <td className="px-4 py-3 text-right">
                                                        <span className="text-sm font-semibold text-gray-900">
                                                            ${item.total.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                                        </span>
                                                    </td>
                                                    <td className="px-4 py-3 text-center">
                                                        {items.length > 1 && (
                                                            <button
                                                                type="button"
                                                                onClick={() => handleRemoveItem(index)}
                                                                className="text-red-500 hover:text-red-700 p-1"
                                                            >
                                                                <TrashIcon className="h-4 w-4" />
                                                            </button>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {/* Right - Totals */}
                        <div className="lg:col-span-1">
                            <div className="card sticky top-24">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                    Resumen
                                </h3>
                                <div className="space-y-3">
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500">Subtotal</span>
                                        <span className="font-medium text-gray-900">
                                            ${subtotal.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500">Descuento</span>
                                        <span className="font-medium text-red-600">
                                            -${totalDiscount.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                        </span>
                                    </div>
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500">IVA ({data.tax_rate}%)</span>
                                        <span className="font-medium text-gray-900">
                                            ${tax.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                        </span>
                                    </div>
                                    <hr className="border-gray-200" />
                                    <div className="flex justify-between">
                                        <span className="text-base font-semibold text-gray-900">TOTAL</span>
                                        <span className="text-xl font-bold text-primary-600">
                                            ${total.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                        </span>
                                    </div>
                                </div>

                                {/* Actions */}
                                <div className="flex flex-col gap-3 mt-6 pt-4 border-t border-gray-200">
                                    <Link
                                        href="/quotations"
                                        className="w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-center"
                                    >
                                        Cancelar
                                    </Link>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {processing ? 'Guardando...' : 'Guardar Cotización'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </AuthenticatedLayout>
        </>
    );
}
