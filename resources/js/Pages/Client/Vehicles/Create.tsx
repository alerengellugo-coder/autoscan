import React from 'react';
import { usePage, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import {
    TruckIcon,
    XMarkIcon,
    InformationCircleIcon,
} from '@heroicons/react/24/outline';
import { PageProps } from '../../../types';

interface ClientVehicleCreateProps extends PageProps {
    [key: string]: unknown;
}

export default function ClientVehicleCreate() {
    const { props } = usePage<ClientVehicleCreateProps>();
    const form = useForm({
        brand: '',
        model: '',
        year: '',
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
        form.post('/mi-cuenta/vehiculos', {
            onSuccess: () => {
                // Redirect handled by backend
            },
        });
    };

    const currentYear = new Date().getFullYear();

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center gap-3">
                    <Link
                        href="/mi-cuenta/vehiculos"
                        className="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 transition-colors"
                    >
                        <XMarkIcon className="h-4 w-4" />
                        Volver
                    </Link>
                    <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-100">
                        <TruckIcon className="h-6 w-6 text-primary-600" />
                    </div>
                    <div>
                        <h1 className="text-xl font-bold text-gray-900">Registrar Vehículo</h1>
                        <p className="text-sm text-gray-500">Agrega un nuevo vehículo a tu cuenta</p>
                    </div>
                </div>
            }
        >
            <div className="max-w-2xl mx-auto space-y-6">
                {/* Info callout */}
                <div className="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <InformationCircleIcon className="h-5 w-5 text-blue-600 mt-0.5 shrink-0" />
                    <div>
                        <p className="text-sm font-medium text-blue-800">
                            Registra tu vehículo para poder crear órdenes de servicio
                        </p>
                        <p className="text-xs text-blue-600 mt-1">
                            Los datos del vehículo ayudarán al técnico a prepararse para el servicio.
                        </p>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Basic info card */}
                    <div className="card">
                        <h3 className="text-base font-semibold text-gray-900 mb-4">Información Básica</h3>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Marca <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={form.data.brand}
                                    onChange={(e) => form.setData('brand', e.target.value)}
                                    className="input-field"
                                    placeholder="Ej: Toyota"
                                    required
                                />
                                {form.errors.brand && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.brand}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Modelo <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={form.data.model}
                                    onChange={(e) => form.setData('model', e.target.value)}
                                    className="input-field"
                                    placeholder="Ej: Corolla"
                                    required
                                />
                                {form.errors.model && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.model}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Año <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="number"
                                    value={form.data.year}
                                    onChange={(e) => form.setData('year', e.target.value)}
                                    className="input-field"
                                    min="2000"
                                    max={currentYear + 1}
                                    placeholder="Ej: 2023"
                                    required
                                />
                                {form.errors.year && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.year}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Placa <span className="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    value={form.data.plate}
                                    onChange={(e) => form.setData('plate', e.target.value.toUpperCase())}
                                    className="input-field"
                                    placeholder="Ej: ABC-123"
                                    required
                                />
                                {form.errors.plate && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.plate}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Color
                                </label>
                                <input
                                    type="text"
                                    value={form.data.color}
                                    onChange={(e) => form.setData('color', e.target.value)}
                                    className="input-field"
                                    placeholder="Ej: Blanco"
                                />
                                {form.errors.color && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.color}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    VIN (Número de Serie)
                                </label>
                                <input
                                    type="text"
                                    value={form.data.vin}
                                    onChange={(e) => form.setData('vin', e.target.value.toUpperCase())}
                                    className="input-field"
                                    placeholder="Opcional"
                                />
                                {form.errors.vin && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.vin}</p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Technical info card */}
                    <div className="card">
                        <h3 className="text-base font-semibold text-gray-900 mb-4">Información Técnica</h3>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Kilometraje (km)
                                </label>
                                <input
                                    type="number"
                                    value={form.data.mileage}
                                    onChange={(e) => form.setData('mileage', e.target.value)}
                                    className="input-field"
                                    min="0"
                                    placeholder="Ej: 45000"
                                />
                                {form.errors.mileage && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.mileage}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Tipo de Motor
                                </label>
                                <select
                                    value={form.data.engine_type}
                                    onChange={(e) => form.setData('engine_type', e.target.value)}
                                    className="input-field"
                                >
                                    <option value="">Seleccionar...</option>
                                    <option value="gasoline">Gasolina</option>
                                    <option value="diesel">Diésel</option>
                                    <option value="electric">Eléctrico</option>
                                    <option value="hybrid">Híbrido</option>
                                </select>
                                {form.errors.engine_type && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.engine_type}</p>
                                )}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Transmisión
                                </label>
                                <select
                                    value={form.data.transmission}
                                    onChange={(e) => form.setData('transmission', e.target.value)}
                                    className="input-field"
                                >
                                    <option value="">Seleccionar...</option>
                                    <option value="automatic">Automática</option>
                                    <option value="manual">Manual</option>
                                    <option value="cvt">CVT</option>
                                </select>
                                {form.errors.transmission && (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.transmission}</p>
                                )}
                            </div>
                        </div>

                        {/* Notes */}
                        <div className="mt-4">
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Notas adicionales
                            </label>
                            <textarea
                                value={form.data.notes}
                                onChange={(e) => form.setData('notes', e.target.value)}
                                rows={3}
                                className="input-field"
                                placeholder="Observaciones, condiciones especiales, accesorios..."
                            />
                            {form.errors.notes && (
                                <p className="mt-1 text-sm text-red-600">{form.errors.notes}</p>
                            )}
                        </div>
                    </div>

                    {/* Submit / Cancel */}
                    <div className="flex items-center justify-end gap-3">
                        <Link
                            href="/mi-cuenta/vehiculos"
                            className="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            disabled={form.processing}
                            className="btn-primary flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed px-6 py-2.5"
                        >
                            {form.processing && (
                                <div className="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                            )}
                            <TruckIcon className="h-4 w-4" />
                            Registrar Vehículo
                        </button>
                    </div>
                </form>
            </div>
        </AuthenticatedLayout>
    );
}
