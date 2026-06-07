import React, { useState, useMemo } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { ArrowLeftIcon, TruckIcon } from '@heroicons/react/24/outline';
import { PageProps, Vehicle, SelectOption } from '../../../types';

interface OrdersCreateProps extends PageProps {
    vehicles: Vehicle[];
    technicians: SelectOption[];
    service_types: SelectOption[];
    priorities: SelectOption[];
}

export default function OrdersCreate({
    vehicles,
    technicians,
    service_types,
    priorities,
}: OrdersCreateProps) {
    const { data, setData, post, processing, errors } = useForm({
        vehicle_id: '',
        technician_id: '',
        service_type: '',
        priority: 'medium',
        description: '',
        estimated_cost: '',
        estimated_completion_date: '',
    });

    const [selectedVehicle, setSelectedVehicle] = useState<Vehicle | null>(null);

    const handleVehicleChange = (vehicleId: string) => {
        setData('vehicle_id', vehicleId);
        const vehicle = vehicles.find((v) => v.id === parseInt(vehicleId));
        setSelectedVehicle(vehicle || null);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/ordenes');
    };

    const inputClass = (field: string) =>
        `input-field px-4 py-2.5 ${errors[field as keyof typeof errors] ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`;

    return (
        <>
            <Head title="Nueva Orden de Servicio" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/ordenes"
                            className="text-gray-500 hover:text-gray-700 transition-colors"
                        >
                            <ArrowLeftIcon className="h-5 w-5" />
                        </Link>
                        <h1 className="text-xl font-semibold text-gray-900">
                            Nueva Orden de Servicio
                        </h1>
                    </div>
                }
            >
                <div className="max-w-3xl mx-auto">
                    <form onSubmit={handleSubmit} className="card space-y-6">
                        {/* Vehicle Select */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Vehículo *
                            </label>
                            <select
                                value={data.vehicle_id}
                                onChange={(e) => handleVehicleChange(e.target.value)}
                                className={inputClass('vehicle_id')}
                            >
                                <option value="">Seleccionar vehículo...</option>
                                {vehicles.map((vehicle) => (
                                    <option key={vehicle.id} value={vehicle.id}>
                                        {vehicle.brand} {vehicle.model} ({vehicle.plate}) - {vehicle.client?.name || 'Sin cliente'}
                                    </option>
                                ))}
                            </select>
                            {errors.vehicle_id && (
                                <p className="mt-1 text-sm text-red-600">{errors.vehicle_id}</p>
                            )}
                        </div>

                        {/* Vehicle Details */}
                        {selectedVehicle && (
                            <div className="p-4 rounded-xl bg-primary-50 border border-primary-100">
                                <div className="flex items-center gap-2 mb-3">
                                    <TruckIcon className="h-5 w-5 text-primary-600" />
                                    <h3 className="text-sm font-semibold text-primary-900">
                                        Información del Vehículo
                                    </h3>
                                </div>
                                <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <p className="text-xs text-primary-600">Marca / Modelo</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.brand} {selectedVehicle.model}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Año</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.year}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Placa</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.plate}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Kilometraje</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.mileage ? `${selectedVehicle.mileage.toLocaleString()} km` : '—'}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Motor</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.engine_type_label || selectedVehicle.engine_type || '—'}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Transmisión</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.transmission_label || selectedVehicle.transmission || '—'}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Color</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.color || '—'}</p>
                                    </div>
                                    <div>
                                        <p className="text-xs text-primary-600">Propietario</p>
                                        <p className="font-medium text-primary-900">{selectedVehicle.client?.name || '—'}</p>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Technician and Service Type */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Técnico
                                </label>
                                <select
                                    value={data.technician_id}
                                    onChange={(e) => setData('technician_id', e.target.value)}
                                    className={inputClass('technician_id')}
                                >
                                    <option value="">Seleccionar técnico...</option>
                                    {technicians.map((tech) => (
                                        <option key={tech.value} value={tech.value}>
                                            {tech.label}
                                        </option>
                                    ))}
                                </select>
                                {errors.technician_id && (
                                    <p className="mt-1 text-sm text-red-600">{errors.technician_id}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tipo de Servicio *
                                </label>
                                <select
                                    value={data.service_type}
                                    onChange={(e) => setData('service_type', e.target.value)}
                                    className={inputClass('service_type')}
                                >
                                    <option value="">Seleccionar tipo...</option>
                                    {service_types.map((type) => (
                                        <option key={type.value} value={type.value}>
                                            {type.label}
                                        </option>
                                    ))}
                                </select>
                                {errors.service_type && (
                                    <p className="mt-1 text-sm text-red-600">{errors.service_type}</p>
                                )}
                            </div>
                        </div>

                        {/* Priority */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Prioridad
                            </label>
                            <select
                                value={data.priority}
                                onChange={(e) => setData('priority', e.target.value)}
                                className={inputClass('priority')}
                            >
                                {priorities.map((p) => (
                                    <option key={p.value} value={p.value}>
                                        {p.label}
                                    </option>
                                ))}
                            </select>
                            {errors.priority && (
                                <p className="mt-1 text-sm text-red-600">{errors.priority}</p>
                            )}
                        </div>

                        {/* Description */}
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Descripción del Problema / Servicio
                            </label>
                            <textarea
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                rows={4}
                                className={inputClass('description')}
                                placeholder="Describa el problema o el servicio que se necesita realizar..."
                            />
                            {errors.description && (
                                <p className="mt-1 text-sm text-red-600">{errors.description}</p>
                            )}
                        </div>

                        {/* Estimated Cost and Completion Date */}
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Costo Estimado
                                </label>
                                <div className="relative">
                                    <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">$</span>
                                    <input
                                        type="number"
                                        value={data.estimated_cost}
                                        onChange={(e) => setData('estimated_cost', e.target.value)}
                                        className={`input-field pl-7 py-2.5 ${errors.estimated_cost ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : ''}`}
                                        placeholder="0.00"
                                        step="0.01"
                                        min="0"
                                    />
                                </div>
                                {errors.estimated_cost && (
                                    <p className="mt-1 text-sm text-red-600">{errors.estimated_cost}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Fecha Estimada de Finalización
                                </label>
                                <input
                                    type="date"
                                    value={data.estimated_completion_date}
                                    onChange={(e) => setData('estimated_completion_date', e.target.value)}
                                    className={inputClass('estimated_completion_date')}
                                />
                                {errors.estimated_completion_date && (
                                    <p className="mt-1 text-sm text-red-600">{errors.estimated_completion_date}</p>
                                )}
                            </div>
                        </div>

                        {/* Actions */}
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <Link
                                href="/admin/ordenes"
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                disabled={processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Creando...' : 'Crear Orden'}
                            </button>
                        </div>
                    </form>
                </div>
            </AuthenticatedLayout>
        </>
    );
}
