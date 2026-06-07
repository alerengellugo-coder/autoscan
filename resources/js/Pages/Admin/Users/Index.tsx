import React, { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../../Layouts/AuthenticatedLayout';
import Modal from '../../../Components/Modal';
import {
    PlusCircleIcon,
    MagnifyingGlassIcon,
    PencilSquareIcon,
    TrashIcon,
    UserCircleIcon,
    FunnelIcon,
    CheckCircleIcon,
    XMarkIcon,
} from '@heroicons/react/24/outline';
import { PageProps, User, PaginationData } from '../../../types';

const roleLabels: Record<string, string> = {
    admin: 'Administrador',
    technician: 'Técnico',
    client: 'Cliente',
};

const roleColors: Record<string, string> = {
    admin: 'bg-red-100 text-red-700',
    technician: 'bg-blue-100 text-blue-700',
    client: 'bg-green-100 text-green-700',
};

interface UsersIndexProps extends PageProps {
    users: PaginationData<User>;
    filters: {
        search?: string;
        role?: string;
    };
}

export default function AdminUsersIndex({ users, filters }: UsersIndexProps) {
    const [search, setSearch] = useState(filters.search || '');
    const [roleFilter, setRoleFilter] = useState(filters.role || '');
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);
    const [editingUser, setEditingUser] = useState<User | null>(null);

    const createForm = useForm({
        name: '',
        email: '',
        password: '',
        role: 'client',
        phone: '',
    });

    const editForm = useForm({
        name: '',
        email: '',
        role: '',
        phone: '',
        is_active: true,
    });

    const handleFilter = (e: React.FormEvent) => {
        e.preventDefault();
        router.get('/admin/usuarios', { search, role: roleFilter }, { preserveState: true });
    };

    const handleCreateSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        createForm.post('/admin/usuarios', {
            onSuccess: () => {
                setShowCreateModal(false);
                createForm.reset();
            },
        });
    };

    const handleEditOpen = (user: User) => {
        setEditingUser(user);
        editForm.setData({
            name: user.name,
            email: user.email,
            role: user.role,
            phone: user.phone || '',
            is_active: user.is_active,
        });
        setShowEditModal(true);
    };

    const handleEditSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingUser) return;
        editForm.put(`/admin/usuarios/${editingUser.id}`, {
            onSuccess: () => {
                setShowEditModal(false);
                setEditingUser(null);
                editForm.reset();
            },
        });
    };

    const handleDelete = (user: User) => {
        if (confirm(`¿Estás seguro de eliminar al usuario "${user.name}"?`)) {
            router.delete(`/admin/usuarios/${user.id}`);
        }
    };

    const formatDate = (date: string) => {
        return new Date(date).toLocaleDateString('es-MX', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });
    };

    return (
        <>
            <Head title="Usuarios" />
            <AuthenticatedLayout
                header={
                    <div className="flex items-center justify-between">
                        <h1 className="text-xl font-semibold text-gray-900">Usuarios</h1>
                        <button
                            onClick={() => {
                                createForm.reset();
                                setShowCreateModal(true);
                            }}
                            className="inline-flex items-center gap-2 btn-primary"
                        >
                            <PlusCircleIcon className="h-5 w-5" />
                            Crear Usuario
                        </button>
                    </div>
                }
            >
                {/* Filter Bar */}
                <div className="card mb-6">
                    <form onSubmit={handleFilter} className="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div className="relative flex-1 w-full">
                            <MagnifyingGlassIcon className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" />
                            <input
                                type="text"
                                value={search}
                                onChange={(e) => setSearch(e.target.value)}
                                placeholder="Buscar por nombre o email..."
                                className="input-field pl-10 pr-4 py-2 text-sm"
                            />
                        </div>
                        <div className="flex items-center gap-3">
                            <div className="flex items-center gap-2">
                                <FunnelIcon className="h-4 w-4 text-gray-400" />
                                <select
                                    value={roleFilter}
                                    onChange={(e) => setRoleFilter(e.target.value)}
                                    className="input-field px-4 py-2 text-sm w-44"
                                >
                                    <option value="">Todos los roles</option>
                                    <option value="admin">Administrador</option>
                                    <option value="technician">Técnico</option>
                                    <option value="client">Cliente</option>
                                </select>
                            </div>
                            <button type="submit" className="btn-primary py-2 text-sm">
                                Filtrar
                            </button>
                            {(search || roleFilter) && (
                                <Link
                                    href="/admin/usuarios"
                                    className="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors"
                                >
                                    Limpiar
                                </Link>
                            )}
                        </div>
                    </form>
                </div>

                {/* Table */}
                <div className="card p-0 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th className="table-header px-6 py-3">Usuario</th>
                                    <th className="table-header px-6 py-3">Rol</th>
                                    <th className="table-header px-6 py-3">Estado</th>
                                    <th className="table-header px-6 py-3">Registrado</th>
                                    <th className="table-header px-6 py-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {users.data.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-6 py-12 text-center text-gray-500">
                                            No se encontraron usuarios.
                                        </td>
                                    </tr>
                                ) : (
                                    users.data.map((user) => (
                                        <tr key={user.id} className="hover:bg-gray-50 transition-colors">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="flex items-center gap-3">
                                                    <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                                                        {user.initials || user.name.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2)}
                                                    </div>
                                                    <div>
                                                        <div className="text-sm font-medium text-gray-900">{user.name}</div>
                                                        <div className="text-sm text-gray-500">{user.email}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <span className={`inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${roleColors[user.role] || 'bg-gray-100 text-gray-700'}`}>
                                                    {roleLabels[user.role] || user.role}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {user.is_active ? (
                                                    <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                        <CheckCircleIcon className="h-3 w-3" />
                                                        Activo
                                                    </span>
                                                ) : (
                                                    <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                        <XMarkIcon className="h-3 w-3" />
                                                        Inactivo
                                                    </span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {formatDate(user.created_at)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right">
                                                <div className="flex items-center justify-end gap-1">
                                                    <button
                                                        onClick={() => handleEditOpen(user)}
                                                        className="text-primary-600 hover:text-primary-700 p-1.5 rounded-lg hover:bg-primary-50 transition-colors"
                                                        title="Editar"
                                                    >
                                                        <PencilSquareIcon className="h-4 w-4" />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(user)}
                                                        className="text-red-600 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors"
                                                        title="Eliminar"
                                                    >
                                                        <TrashIcon className="h-4 w-4" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Pagination */}
                    {users.last_page > 1 && (
                        <div className="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                            <div className="text-sm text-gray-500">
                                Mostrando {users.from} a {users.to} de {users.total} usuarios
                            </div>
                            <div className="flex items-center gap-2">
                                <Link
                                    href={users.prev_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        users.prev_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Anterior
                                </Link>
                                <span className="text-sm text-gray-700">
                                    Página {users.current_page} de {users.last_page}
                                </span>
                                <Link
                                    href={users.next_page_url || '#'}
                                    className={`inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 ${
                                        users.next_page_url ? 'text-gray-700 hover:bg-gray-50' : 'text-gray-300 cursor-not-allowed pointer-events-none'
                                    }`}
                                    preserveState
                                >
                                    Siguiente
                                </Link>
                            </div>
                        </div>
                    )}
                </div>

                {/* Create User Modal */}
                <Modal
                    isOpen={showCreateModal}
                    onClose={() => {
                        setShowCreateModal(false);
                        createForm.reset();
                    }}
                    title="Crear Usuario"
                    size="lg"
                >
                    <form onSubmit={handleCreateSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Nombre *</label>
                            <input
                                type="text"
                                value={createForm.data.name}
                                onChange={(e) => createForm.setData('name', e.target.value)}
                                className={`input-field px-4 py-2.5 ${createForm.errors.name ? 'border-red-500' : ''}`}
                                required
                            />
                            {createForm.errors.name && <p className="mt-1 text-sm text-red-600">{createForm.errors.name}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                            <input
                                type="email"
                                value={createForm.data.email}
                                onChange={(e) => createForm.setData('email', e.target.value)}
                                className={`input-field px-4 py-2.5 ${createForm.errors.email ? 'border-red-500' : ''}`}
                                required
                            />
                            {createForm.errors.email && <p className="mt-1 text-sm text-red-600">{createForm.errors.email}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Contraseña *</label>
                            <input
                                type="password"
                                value={createForm.data.password}
                                onChange={(e) => createForm.setData('password', e.target.value)}
                                className={`input-field px-4 py-2.5 ${createForm.errors.password ? 'border-red-500' : ''}`}
                                required
                                minLength={8}
                            />
                            {createForm.errors.password && <p className="mt-1 text-sm text-red-600">{createForm.errors.password}</p>}
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Rol *</label>
                                <select
                                    value={createForm.data.role}
                                    onChange={(e) => createForm.setData('role', e.target.value)}
                                    className="input-field px-4 py-2.5"
                                    required
                                >
                                    <option value="client">Cliente</option>
                                    <option value="technician">Técnico</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                                <input
                                    type="text"
                                    value={createForm.data.phone}
                                    onChange={(e) => createForm.setData('phone', e.target.value)}
                                    className="input-field px-4 py-2.5"
                                />
                            </div>
                        </div>
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => {
                                    setShowCreateModal(false);
                                    createForm.reset();
                                }}
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                disabled={createForm.processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {createForm.processing ? 'Creando...' : 'Crear Usuario'}
                            </button>
                        </div>
                    </form>
                </Modal>

                {/* Edit User Modal */}
                <Modal
                    isOpen={showEditModal}
                    onClose={() => {
                        setShowEditModal(false);
                        setEditingUser(null);
                        editForm.reset();
                    }}
                    title="Editar Usuario"
                    size="lg"
                >
                    <form onSubmit={handleEditSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Nombre *</label>
                            <input
                                type="text"
                                value={editForm.data.name}
                                onChange={(e) => editForm.setData('name', e.target.value)}
                                className={`input-field px-4 py-2.5 ${editForm.errors.name ? 'border-red-500' : ''}`}
                                required
                            />
                            {editForm.errors.name && <p className="mt-1 text-sm text-red-600">{editForm.errors.name}</p>}
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                            <input
                                type="email"
                                value={editForm.data.email}
                                onChange={(e) => editForm.setData('email', e.target.value)}
                                className={`input-field px-4 py-2.5 ${editForm.errors.email ? 'border-red-500' : ''}`}
                                required
                            />
                            {editForm.errors.email && <p className="mt-1 text-sm text-red-600">{editForm.errors.email}</p>}
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Rol *</label>
                                <select
                                    value={editForm.data.role}
                                    onChange={(e) => editForm.setData('role', e.target.value)}
                                    className="input-field px-4 py-2.5"
                                    required
                                >
                                    <option value="client">Cliente</option>
                                    <option value="technician">Técnico</option>
                                    <option value="admin">Administrador</option>
                                </select>
                                {editForm.errors.role && <p className="mt-1 text-sm text-red-600">{editForm.errors.role}</p>}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">Teléfono</label>
                                <input
                                    type="text"
                                    value={editForm.data.phone}
                                    onChange={(e) => editForm.setData('phone', e.target.value)}
                                    className="input-field px-4 py-2.5"
                                />
                            </div>
                        </div>
                        <div>
                            <label className="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={editForm.data.is_active}
                                    onChange={(e) => editForm.setData('is_active', e.target.checked)}
                                    className="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                />
                                <span className="text-sm font-medium text-gray-700">Usuario activo</span>
                            </label>
                        </div>
                        <div className="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                onClick={() => {
                                    setShowEditModal(false);
                                    setEditingUser(null);
                                    editForm.reset();
                                }}
                                className="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                disabled={editForm.processing}
                                className="btn-primary py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {editForm.processing ? 'Guardando...' : 'Guardar Cambios'}
                            </button>
                        </div>
                    </form>
                </Modal>
            </AuthenticatedLayout>
        </>
    );
}
