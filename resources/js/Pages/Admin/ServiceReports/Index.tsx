import React from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { PageProps } from '../../../types';

interface Props extends PageProps {
    reports: any[];
}

export default function AdminServiceReportsIndex({ reports }: Props) {
    return (
        <>
            <Head title="Reportes de Servicio" />
            <AuthenticatedLayout header={<h1 className="text-xl font-semibold text-gray-900">Reportes de Servicio</h1>}>
                <div className="card">
                    {reports.length === 0 ? (
                        <p className="text-gray-500 text-center py-8">No hay reportes de servicio.</p>
                    ) : (
                        <ul className="space-y-2">
                            {reports.map((r: any) => (
                                <li key={r.id} className="p-3 border rounded-lg hover:bg-gray-50">
                                    <span className="font-medium">Reporte #{r.id}</span>
                                    <span className="text-gray-500 ml-2">{r.report_date}</span>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </AuthenticatedLayout>
        </>
    );
}
