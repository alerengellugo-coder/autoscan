@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Usuarios')

@section('content')
@php if(!isset($errors)) $errors = app(\Illuminate\Contracts\Support\MessageBag::class); @endphp
<div class="space-y-6">

    {{-- Users Table --}}
    <div class="bg-white rounded-xl shadow-sm border @php $_t3 = -- Users Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Lista de Usuarios</h2>
            <button type="button"
                    onclick="document.getElementById('createUserModal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nuevo Usuario
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase text-gray-500 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 font-medium">Nombre</th>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Teléfono</th>
                        <th class="px-5 py-3 font-medium">Rol</th>
                        <th class="px-5 py-3 font-medium">Estado</th>
                        <th class="px-5 py-3 font-medium">Fecha</th>
                        <th class="px-5 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-700">{{ $user->email }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $user->phone ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @switch($user->role)
                                @case('admin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Administrador</span>
                                    @break
                                @case('technician')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Técnico</span>
                                    @break
                                @case('client')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Cliente</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ ucfirst($user->role) }}</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-5 py-3">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500">
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                    Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                {{-- Toggle Active --}}
                                <form method="POST" action="{{ route('admin.usuarios.toggle-active', $user) }}" class="inline-flex" onsubmit="return confirm('¿Cambiar estado del usuario?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium {{ $user->is_activ@php $_t2 = $user->is_active ? 'text-yellow-600 hover:text-white hover:bg-yellow-600' : 'text-green-600 hover:text-white hover:bg-green-600'; @endphp{{ $_t2 }}low-600" : "text-green-600 hover:text-white hover:bg-green-600"; @endphp{{ $_t6 }} viewBox="0 0@php $_t5 = $user->is_activ@php $_t2 = $_t6 }} viewBox="0 0@php $_t5 = $user->is_active ? "Desactivar" : "Activar"; @endphp{{ $_t5 }}width="2" stroke-linecap="round" stroke-linejoin="round">
                                            @if($user->is_active)
                                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/>
                                                <line x1="1" y1="1" x2="23" y2="23"/>
                                            @else
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            @endif
                                        </svg>
                                        @php $_t0 = $user-@php $_t1 = $user->is_active ? 'Desactivar' : 'Activar'; @endphp{ $_t0 }4 = $user->is_active ? "Desactivar" : "Activar"; @endphp{{ $_t4 }}               @php $_t1 = $_t4 }}                    {{-- Edit --}}
                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-colors" title="Editar">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                    Editar
                                </a>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.usuarios.destroy', $user) }}" class="inline-flex" onsubmit="return confirm('¿Eliminar este usuario? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-colors" title="Eliminar">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3,6 5,6 21,6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            <p class="text-sm">No se encontraron usuarios.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-200">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

    {{-- Create User Modal --}}
    <div id="createUserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('createUserModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-xl shadow-xl border border-gray-200 w-full max-w-lg mx-4 z-10">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Crear Nuevo Usuario</h3>
                    <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')" class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.usuarios.store') }}" class="p-6 space-y-4">
                @csrf

                <div>
                    <label for="user_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="user_name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Nombre completo">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="user_email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email"
                               id="user_email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="correo@ejemplo.com">
                        @error('email')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="user_phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel"
                               id="user_phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="+1234567890">
                        @error('phone')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="user_password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                    <input type="password"
                           id="user_password"
                           name="password"
                           required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="user_role" class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                    <select id="user_role"
                            name="role"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="" @php $_t0 = old('role') == '' ? 'selected' : ''; @endphp{{ $_t0 }}
        @php $_t3 = old('role') == '' ? "selected" : ""; @endphp@php $_t0 = $_t3 }}old('role') == 'admin' ? 'selected' : ''; @endphp{{ $_t0 }}>Adm@php $_t2 = old('role') == 'admin' ? "selected" : ""; @endphp{{ $_t2 }}ption value="technician" @php $_t1 = old('role') == 'techni@php $_t1 = old('role') == 'technician' ? 'selected' : ''; @endphp{ $_t1 }                 <option value="client" @php $_t0 = old('role') == 'client' ? "selected" : ""; @endphp{{ $_t0 }}>Cliente</option>
                    </select>
                    @error('role')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('createUserModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
