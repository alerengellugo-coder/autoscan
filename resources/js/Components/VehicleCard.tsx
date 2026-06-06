import React from 'react';
import { Link } from '@inertiajs/react';
import StatusBadge from './StatusBadge';
import { Vehicle } from '../types';

interface VehicleCardProps {
    vehicle: Vehicle;
    showClient?: boolean;
    showActions?: boolean;
    actions?: React.ReactNode;
}

export default function VehicleCard({
    vehicle,
    showClient = false,
    showActions = true,
    actions,
}: VehicleCardProps) {
    return (
        <div className="card hover:shadow-card-hover transition-shadow duration-200">
            <div className="flex items-start justify-between">
                <div className="flex items-center gap-4">
                    {/* Vehicle icon */}
                    <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-primary-100">
                        <svg
                            className="h-7 w-7 text-primary-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={1.5}
                                d="M8 17h8M8 17v-4m8 4v-4m-8 0h8m-8 0V9a4 4 0 014-4v0a4 4 0 014 4v4"
                            />
                            <circle cx="9" cy="19" r="1" fill="currentColor" />
                            <circle cx="15" cy="19" r="1" fill="currentColor" />
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={1.5}
                                d="M5 17h14a1 1 0 001-1v-4a1 1 0 00-.293-.707l-2.414-2.414A1 1 0 0016.586 8H7.414a1 1 0 00-.707.293L4.293 10.707A1 1 0 004 11.414v4.586a1 1 0 001 1z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h3 className="font-semibold text-gray-900">
                            {vehicle.full_name || `${vehicle.brand} ${vehicle.model} ${vehicle.year}`}
                        </h3>
                        <p className="text-sm text-gray-500">
                            Placa: {vehicle.plate_formatted || vehicle.plate.toUpperCase()}
                        </p>
                    </div>
                </div>
                <StatusBadge status={vehicle.status} label={vehicle.status_label} />
            </div>

            <div className="mt-4 grid grid-cols-2 gap-3 text-sm">
                {vehicle.color && (
                    <div>
                        <span className="text-gray-500">Color: </span>
                        <span className="font-medium text-gray-900">
                            {vehicle.color}
                        </span>
                    </div>
                )}
                {vehicle.mileage !== null && vehicle.mileage !== undefined && (
                    <div>
                        <span className="text-gray-500">Kilometraje: </span>
                        <span className="font-medium text-gray-900">
                            {vehicle.mileage_formatted ||
                                `${vehicle.mileage.toLocaleString()} km`}
                        </span>
                    </div>
                )}
                {vehicle.engine_type && (
                    <div>
                        <span className="text-gray-500">Motor: </span>
                        <span className="font-medium text-gray-900">
                            {vehicle.engine_type_label || vehicle.engine_type}
                        </span>
                    </div>
                )}
                {vehicle.transmission && (
                    <div>
                        <span className="text-gray-500">Transmisión: </span>
                        <span className="font-medium text-gray-900">
                            {vehicle.transmission_label || vehicle.transmission}
                        </span>
                    </div>
                )}
            </div>

            {showClient && vehicle.client && (
                <div className="mt-3 pt-3 border-t border-gray-100 text-sm">
                    <span className="text-gray-500">Propietario: </span>
                    <span className="font-medium text-gray-900">
                        {vehicle.client.name}
                    </span>
                </div>
            )}

            {showActions && (
                <div className="mt-4 pt-3 border-t border-gray-100 flex items-center gap-2">
                    {actions || (
                        <Link
                            href={`/vehicles/${vehicle.id}`}
                            className="text-sm font-medium text-primary-600 hover:text-primary-700"
                        >
                            Ver detalles →
                        </Link>
                    )}
                </div>
            )}
        </div>
    );
}
