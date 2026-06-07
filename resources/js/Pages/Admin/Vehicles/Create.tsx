import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import { PageProps, SelectOption, User } from '../../../types';

interface VehiclesCreateProps extends PageProps {
    clients: SelectOption[];
}

export default function VehiclesCreate({ clients = [] }: VehiclesCreateProps) {
    const { data, setData, post, processing, errors } = useForm({
        client_id: '',
        brand: '',
        model: '',
        year: new Date().getFullYear(),
        plate: '',
        color: '',
        vin: '',
        mileage: '',
        engine_type: '',
        transmission: '',
        notes: '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/vehiculos');
    };

    const inputClass = (field: string) =>
        `input-field px-4 py-2.5 ${errors[field as keyof typeof errors] ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`;

    return (
        <>
            <Head title="Nuevo Vehículo" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/vehiculos"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Nuevo Vehículo
                        </h1>
                    </div>
                }
            >
                <div className="max-w-3xl mx-auto">
                    <form onSubmit={handleSubmit} className="card space-y-6">
                        {/* Client */}
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
                                {clients.map((client) => (
                                    <option key={client.value} value={client.value}>
                                        {client.label}
                                    </option>
                                ))}
                            </select>
                            {errors.client_id && (
                                <p className="mt-1 text-sm text-red-600">{errors.client_id}</p>
                            )}
                        </div>

                        {/* Brand and Model */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Marca *
                                </label>
                                <input
                                    type="text"
                                    value={data.brand}
                                    onChange={(e) => setData('brand', e.target.value)}
                                    className={inputClass('brand')}
                                    placeholder="Ej: Toyota"
                                />
                                {errors.brand && (
                                    <p className="mt-1 text-sm text-red-600">{errors.brand}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Modelo *
                                </label>
                                <input
                                    type="text"
                                    value={data.model}
                                    onChange={(e) => setData('model', e.target.value)}
                                    className={inputClass('model')}
                                    placeholder="Ej: Camry"
                                />
                                {errors.model && (
                                    <p className="mt-1 text-sm text-red-600">{errors.model}</p>
                                )}
                            </div>
                        </div>

                        {/* Year and Plate */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Año *
                                </label>
                                <input
                                    type="number"
                                    value={data.year}
                                    onChange={(e) => setData('year', parseInt(e.target.value) || 0)}
                                    className={inputClass('year')}
                                    min={1900}
                                    max={new Date().getFullYear() + 1}
                                />
                                {errors.year && (
                                    <p className="mt-1 text-sm text-red-600">{errors.year}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Placa *
                                </label>
                                <input
                                    type="text"
                                    value={data.plate}
                                    onChange={(e) => setData('plate', e.target.value)}
                                    className={inputClass('plate')}
                                    placeholder="Ej: ABC-123"
                                />
                                {errors.plate && (
                                    <p className="mt-1 text-sm text-red-600">{errors.plate}</p>
                                )}
                            </div>
                        </div>

                        {/* Color and VIN */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Color
                                </label>
                                <input
                                    type="text"
                                    value={data.color}
                                    onChange={(e) => setData('color', e.target.value)}
                                    className={inputClass('color')}
                                    placeholder="Ej: Blanco"
                                />
                                {errors.color && (
                                    <p className="mt-1 text-sm text-red-600">{errors.color}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    VIN
                                </label>
                                <input
                                    type="text"
                                    value={data.vin}
                                    onChange={(e) => setData('vin', e.target.value)}
                                    className={inputClass('vin')}
                                    placeholder="Número de identificación del vehículo"
                                />
                                {errors.vin && (
                                    <p className="mt-1 text-sm text-red-600">{errors.vin}</p>
                                )}
                            </div>
                        </div>

                        {/* Mileage */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Kilometraje
                            </label>
                            <input
                                type="number"
                                value={data.mileage}
                                onChange={(e) => setData('mileage', e.target.value)}
                                className={inputClass('mileage')}
                                placeholder="Ej: 50000"
                            />
                            {errors.mileage && (
                                <p className="mt-1 text-sm text-red-600">{errors.mileage}</p>
                            )}
                        </div>

                        {/* Engine Type and Transmission */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tipo de Motor
                                </label>
                                <select
                                    value={data.engine_type}
                                    onChange={(e) => setData('engine_type', e.target.value)}
                                    className={inputClass('engine_type')}
                                >
                                    <option value="">Seleccionar...</option>
                                    <option value="gasoline">Gasolina</option>
                                    <option value="diesel">Diésel</option>
                                    <option value="electric">Eléctrico</option>
                                    <option value="hybrid">Híbrido</option>
                                </select>
                                {errors.engine_type && (
                                    <p className="mt-1 text-sm text-red-600">{errors.engine_type}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Transmisión
                                </label>
                                <select
                                    value={data.transmission}
                                    onChange={(e) => setData('transmission', e.target.value)}
                                    className={inputClass('transmission')}
                                >
                                    <option value="">Seleccionar...</option>
                                    <option value="automatic">Automática</option>
                                    <option value="manual">Manual</option>
                                    <option value="cvt">CVT</option>
                                    <option value="dsg">DSG</option>
                                </select>
                                {errors.transmission && (
                                    <p className="mt-1 text-sm text-red-600">{errors.transmission}</p>
                                )}
                            </div>
                        </div>

                        {/* Notes */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Notas
                            </label>
                            <textarea
                                value={data.notes}
                                onChange={(e) => setData('notes', e.target.value)}
                                rows={3}
                                className={inputClass('notes')}
                                placeholder="Observaciones adicionales sobre el vehículo..."
                            />
                            {errors.notes && (
                                <p className="mt-1 text-sm text-red-600">{errors.notes}</p>
                            )}
                        </div>

                        {/* Actions */}
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <Link
                                href="/admin/vehiculos"
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Guardando...' : 'Guardar Vehículo'}
                            </button>
                        </div>
                    </form>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
