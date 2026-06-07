import React, { useState, useCallback } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { ArrowLeftIcon, PlusCircleIcon, TrashIcon } from '@heroicons/react/24/outline';
import { PageProps, SelectOption, Product, Quotation, QuotationItem } from '../../../types';

interface SaleItemRow {
    product_id: string;
    item_type: string;
    name: string;
    description: string;
    quantity: number;
    unit_price: number;
    discount: number;
    total: number;
}

interface SalesCreateProps extends PageProps {
    clients: SelectOption[];
    quotations: Quotation[];
    products: Product[];
}

const initialItem: SaleItemRow = {
    product_id: '',
    item_type: 'product',
    name: '',
    description: '',
    quantity: 1,
    unit_price: 0,
    discount: 0,
    total: 0,
};

export default function SalesCreate({
    clients = [],
    quotations = [],
    products = [],
}: SalesCreateProps) {
    const [activeTab, setActiveTab] = useState<'quotation' | 'manual'>('quotation');
    const [items, setItems] = useState<SaleItemRow[]>([{ ...initialItem }]);
    const [selectedQuotationId, setSelectedQuotationId] = useState('');

    const selectedQuotation = quotations.find(
        (q) => q.id === parseInt(selectedQuotationId)
    );

    const { data, setData, post, processing, errors } = useForm({
        client_id: '',
        quotation_id: '',
        payment_method: '',
        notes: '',
        tax_rate: 16,
        items: [] as SaleItemRow[],
    });

    const handleTabChange = (tab: 'quotation' | 'manual') => {
        setActiveTab(tab);
        setItems([{ ...initialItem }]);
        setSelectedQuotationId('');
        setData('quotation_id', '');
        setData('client_id', '');
    };

    const handleQuotationSelect = (quotationId: string) => {
        setSelectedQuotationId(quotationId);
        setData('quotation_id', quotationId);

        const quotation = quotations.find((q) => q.id === parseInt(quotationId));
        if (quotation && quotation.client) {
            setData('client_id', String(quotation.client.id));
        }

        if (quotation && quotation.items && quotation.items.length > 0) {
            const mappedItems: SaleItemRow[] = quotation.items.map((item: QuotationItem) => ({
                product_id: item.product_id ? String(item.product_id) : '',
                item_type: 'product',
                name: item.description || item.product?.name || '',
                description: item.description || '',
                quantity: item.quantity,
                unit_price: item.unit_price,
                discount: item.discount || 0,
                total: item.total,
            }));
            setItems(mappedItems);
        } else {
            setItems([{ ...initialItem }]);
        }
    };

    const handleAddItem = () => {
        setItems((prev) => [...prev, { ...initialItem }]);
    };

    const handleRemoveItem = (index: number) => {
        setItems((prev) => prev.filter((_, i) => i !== index));
    };

    const handleItemChange = useCallback(
        (index: number, field: keyof SaleItemRow, value: string | number) => {
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
                item.total = item.quantity * item.unit_price - item.discount;

                updated[index] = item;
                return updated;
            });
        },
        [products]
    );

    const subtotal = items.reduce((sum, item) => sum + item.quantity * item.unit_price, 0);
    const totalDiscount = items.reduce((sum, item) => sum + item.discount, 0);
    const taxableAmount = subtotal - totalDiscount;
    const tax = taxableAmount * (data.tax_rate / 100);
    const total = taxableAmount + tax;

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/ventas', {
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
            <Head title="Nueva Venta" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/ventas"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Nueva Venta
                        </h1>
                    </div>
                }
            >
                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Left - Form Fields */}
                        <div className="lg:col-span-2 space-y-6">
                            {/* Tab selector */}
                            <div className="card">
                                <div className="flex border-b border-gray-200">
                                    <button
                                        type="button"
                                        onClick={() => handleTabChange('quotation')}
                                        className={`px-6 py-3 text-sm font-medium border-b-2 transition-colors ${
                                            activeTab === 'quotation'
                                                ? 'border-primary-500 text-primary-600'
                                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                        }`}
                                    >
                                        Desde Cotización
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => handleTabChange('manual')}
                                        className={`px-6 py-3 text-sm font-medium border-b-2 transition-colors ${
                                            activeTab === 'manual'
                                                ? 'border-primary-500 text-primary-600'
                                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                        }`}
                                    >
                                        Venta Manual
                                    </button>
                                </div>

                                <div className="p-6">
                                    {activeTab === 'quotation' ? (
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                Seleccionar Cotización Aprobada *
                                            </label>
                                            <select
                                                value={selectedQuotationId}
                                                onChange={(e) => handleQuotationSelect(e.target.value)}
                                                className={inputClass('quotation_id')}
                                            >
                                                <option value="">Seleccionar cotización...</option>
                                                {quotations
                                                    .filter((q) => q.status === 'approved')
                                                    .map((q) => (
                                                        <option key={q.id} value={q.id}>
                                                            {q.quotation_number} — {q.client?.name} — {q.formatted_total || `$${q.total.toFixed(2)}`}
                                                        </option>
                                                    ))}
                                            </select>
                                            {errors.quotation_id && (
                                                <p className="mt-1 text-sm text-red-600">{errors.quotation_id}</p>
                                            )}

                                            {/* Quotation summary */}
                                            {selectedQuotation && (
                                                <div className="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                                    <h4 className="text-sm font-semibold text-gray-900 mb-2">
                                                        Resumen de Cotización
                                                    </h4>
                                                    <div className="grid grid-cols-2 gap-2 text-sm">
                                                        <p className="text-gray-500">Cliente:</p>
                                                        <p className="text-gray-900">{selectedQuotation.client?.name}</p>
                                                        <p className="text-gray-500">Cotización:</p>
                                                        <p className="text-gray-900">{selectedQuotation.quotation_number}</p>
                                                        <p className="text-gray-500">Subtotal:</p>
                                                        <p className="text-gray-900">{selectedQuotation.formatted_subtotal || `$${selectedQuotation.subtotal.toFixed(2)}`}</p>
                                                        <p className="text-gray-500">IVA:</p>
                                                        <p className="text-gray-900">{selectedQuotation.formatted_tax || `$${selectedQuotation.tax.toFixed(2)}`}</p>
                                                        <p className="text-gray-500 font-semibold">Total:</p>
                                                        <p className="text-gray-900 font-semibold">{selectedQuotation.formatted_total || `$${selectedQuotation.total.toFixed(2)}`}</p>
                                                    </div>
                                                    {selectedQuotation.items && selectedQuotation.items.length > 0 && (
                                                        <div className="mt-3 pt-3 border-t border-gray-200">
                                                            <p className="text-xs font-medium text-gray-500 uppercase mb-2">
                                                                Ítems ({selectedQuotation.items.length})
                                                            </p>
                                                            <ul className="space-y-1">
                                                                {selectedQuotation.items.map((item) => (
                                                                    <li key={item.id} className="flex justify-between text-sm">
                                                                        <span className="text-gray-700 truncate max-w-[60%]">
                                                                            {item.description || item.product?.name || 'Ítem'}
                                                                            <span className="text-gray-400"> x{item.quantity}</span>
                                                                        </span>
                                                                        <span className="text-gray-900 font-medium">
                                                                            {item.formatted_total || `$${item.total.toFixed(2)}`}
                                                                        </span>
                                                                    </li>
                                                                ))}
                                                            </ul>
                                                        </div>
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    ) : (
                                        <div className="space-y-4">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                                    Cliente *
                                                </label>
                                                <select
                                                    value={data.client_id}
                                                    onChange={(e) => setData('client_id', e.target.value)}
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
                                                    Notas
                                                </label>
                                                <textarea
                                                    value={data.notes}
                                                    onChange={(e) => setData('notes', e.target.value)}
                                                    rows={3}
                                                    className={inputClass('notes')}
                                                    placeholder="Notas adicionales de la venta..."
                                                />
                                                {errors.notes && (
                                                    <p className="mt-1 text-sm text-red-600">{errors.notes}</p>
                                                )}
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Items Section (for manual tab or auto-filled from quotation) */}
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
                                                            disabled={activeTab === 'quotation' && selectedQuotationId !== ''}
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

                                {/* Payment Method */}
                                <div className="mb-4">
                                    <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                        Método de Pago *
                                    </label>
                                    <select
                                        value={data.payment_method}
                                        onChange={(e) => setData('payment_method', e.target.value)}
                                        className={inputClass('payment_method')}
                                    >
                                        <option value="">Seleccionar método...</option>
                                        <option value="cash">Efectivo</option>
                                        <option value="card">Tarjeta</option>
                                        <option value="transfer">Transferencia</option>
                                        <option value="credit">Crédito</option>
                                    </select>
                                    {errors.payment_method && (
                                        <p className="mt-1 text-sm text-red-600">{errors.payment_method}</p>
                                    )}
                                </div>

                                <div className="space-y-3">
                                    <div className="flex justify-between text-sm">
                                        <span className="text-gray-500">Subtotal</span>
                                        <span className="font-medium text-gray-900">
                                            ${subtotal.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                        </span>
                                    </div>
                                    {totalDiscount > 0 && (
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-500">Descuento</span>
                                            <span className="font-medium text-red-600">
                                                -${totalDiscount.toLocaleString('es-MX', { minimumFractionDigits: 2 })}
                                            </span>
                                        </div>
                                    )}
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
                                        href="/admin/ventas"
                                        className="w-full px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors text-center"
                                    >
                                        Cancelar
                                    </Link>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {processing ? 'Guardando...' : 'Registrar Venta'}
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
