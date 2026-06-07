import React from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { PageProps } from '../../../types';

interface Props extends PageProps {
    report: any;
}

export default function AdminServiceReportsShow({ report }: Props) {
    return (
        <>
            <Head title={`Reporte #${report.id}`} />
            <AuthenticatedLayout header={<h1 className="text-xl font-semibold text-gray-900">Reporte #{report.id}</h1>}>
                <div className="card">
                    <p className="text-sm text-gray-500">Fecha: {report.report_date}</p>
                    {report.description && <p className="mt-4">{report.description}</p>}
                    {report.work_performed && (
                        <div className="mt-4">
                            <h3 className="font-semibold">Trabajo Realizado</h3>
                            <p>{report.work_performed}</p>
                        </div>
                    )}
                </div>
            </AuthenticatedLayout>
        </>
    );
}
