import React from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import { PageProps } from '../../../types';

interface Props extends PageProps {
    users: any[];
}

export default function AdminUsersIndex({ users }: Props) {
    return (
        <>
            <Head title="Usuarios" />
            <AuthenticatedLayout header={<h1 className="text-xl font-semibold text-gray-900">Usuarios</h1>}>
                <div className="card">
                    {users && users.length === 0 ? (
                        <p className="text-gray-500 text-center py-8">No hay usuarios.</p>
                    ) : (
                        <ul className="space-y-2">
                            {users && users.map((u: any) => (
                                <li key={u.id} className="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                    <div>
                                        <span className="font-medium">{u.name}</span>
                                        <span className="text-gray-500 text-sm ml-2">{u.email}</span>
                                    </div>
                                    <span className="text-xs px-2 py-1 bg-gray-100 rounded">{u.role}</span>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </AuthenticatedLayout>
        </>
    );
}
