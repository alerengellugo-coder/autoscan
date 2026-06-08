@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.usuarios.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Usuarios
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Editar Usuario: {{ $user->name }}</h2>
        </div>

        <form method="PUT" action="{{ route('admin.usuarios.update', $user) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                <input type="text" id="edit_name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="edit_email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">Telefono</label>
                    <input type="tel" id="edit_phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <div>
                <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                <select id="edit_role" name="role" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="technician" {{ $user->role == 'technician' ? 'selected' : '' }}>Tecnico</option>
                    <option value="client" {{ $user->role == 'client' ? 'selected' : '' }}>Cliente</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.usuarios.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
