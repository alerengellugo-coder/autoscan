import React, { useState, Fragment } from 'react';
import { usePage, Link } from '@inertiajs/react';
import { Dialog, Transition, Menu } from '@headlessui/react';
import {
    Bars3Icon,
    XMarkIcon,
    HomeIcon,
    CpuChipIcon,
    TruckIcon,
    ClipboardDocumentListIcon,
    ShoppingBagIcon,
    DocumentTextIcon,
    CubeIcon,
    BellIcon,
    UserCircleIcon,
    ArrowRightOnRectangleIcon,
    Cog6ToothIcon,
    ChevronDownIcon,
    WrenchScrewdriverIcon,
    SignalIcon,
} from '@heroicons/react/24/outline';
import NotificationBell from '../Components/NotificationBell';
import FlashMessage from '../Components/FlashMessage';
import { PageProps, User } from '../types';

interface AuthenticatedLayoutProps {
    children: React.ReactNode;
    header?: React.ReactNode;
}

interface NavItem {
    label: string;
    href: string;
    icon: React.ElementType;
    roles: ('admin' | 'technician' | 'client')[];
}

const navigation: NavItem[] = [
    { label: 'Panel', href: '/dashboard', icon: HomeIcon, roles: ['admin', 'technician', 'client'] },
    { label: 'Vehiculos', href: '/admin/vehiculos', icon: TruckIcon, roles: ['admin'] },
    { label: 'Mis Vehiculos', href: '/mi-cuenta/vehiculos', icon: TruckIcon, roles: ['client'] },
    { label: 'Ordenes', href: '/admin/ordenes', icon: ClipboardDocumentListIcon, roles: ['admin'] },
    { label: 'Mis Ordenes', href: '/tecnico/ordenes', icon: ClipboardDocumentListIcon, roles: ['technician'] },
    { label: 'Mis Ordenes', href: '/mi-cuenta/ordenes', icon: ClipboardDocumentListIcon, roles: ['client'] },
    { label: 'Productos', href: '/admin/productos', icon: CubeIcon, roles: ['admin'] },
    { label: 'Catalogo', href: '/tecnico/catalogo', icon: CubeIcon, roles: ['technician'] },
    { label: 'Cotizaciones', href: '/admin/cotizaciones', icon: DocumentTextIcon, roles: ['admin'] },
    { label: 'Mis Cotizaciones', href: '/mi-cuenta/cotizaciones', icon: DocumentTextIcon, roles: ['client'] },
    { label: 'Ventas', href: '/admin/ventas', icon: ShoppingBagIcon, roles: ['admin'] },
    { label: 'Mis Compras', href: '/mi-cuenta/ventas', icon: ShoppingBagIcon, roles: ['client'] },
    { label: 'Reportes', href: '/admin/reportes-servicio', icon: WrenchScrewdriverIcon, roles: ['admin'] },
    { label: 'Reportes', href: '/tecnico/reportes', icon: WrenchScrewdriverIcon, roles: ['technician'] },
    { label: 'Usuarios', href: '/admin/usuarios', icon: UserCircleIcon, roles: ['admin'] },
    { label: 'Notificaciones', href: '/notificaciones', icon: BellIcon, roles: ['admin', 'technician', 'client'] },
];

export default function AuthenticatedLayout({ children, header }: AuthenticatedLayoutProps) {
    const { auth, flash } = usePage<PageProps>().props;
    const user = auth.user as User;
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const filteredNav = navigation.filter((item) => item.roles.includes(user.role));

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        window.location.href = '/logout';
    };

    const currentPath = typeof window !== 'undefined' ? window.location.pathname : '';

    return (
        <div className="min-h-screen bg-dark-50">
            <FlashMessage flash={flash} />

            {/* ── Mobile sidebar ── */}
            <Transition.Root show={sidebarOpen} as={Fragment}>
                <Dialog as="div" className="relative z-50 lg:hidden" onClose={setSidebarOpen}>
                    <Transition.Child as={Fragment} enter="transition-opacity ease-linear duration-300" enterFrom="opacity-0" enterTo="opacity-100" leave="transition-opacity ease-linear duration-300" leaveFrom="opacity-100" leaveTo="opacity-0">
                        <div className="fixed inset-0 bg-dark-950/80 backdrop-blur-sm" />
                    </Transition.Child>
                    <div className="fixed inset-0 flex">
                        <Transition.Child as={Fragment} enter="transition ease-in-out duration-300 transform" enterFrom="-translate-x-full" enterTo="translate-x-0" leave="transition ease-in-out duration-300 transform" leaveFrom="translate-x-0" leaveTo="-translate-x-full">
                            <Dialog.Panel className="relative mr-16 flex w-full max-w-xs flex-1">
                                <div className="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button type="button" className="-m-2.5 p-2.5" onClick={() => setSidebarOpen(false)}>
                                        <XMarkIcon className="h-6 w-6 text-white" />
                                    </button>
                                </div>
                                <div className="flex grow flex-col gap-y-5 overflow-y-auto bg-dark-900 px-6 pb-4 pt-2">
                                    <div className="flex h-16 shrink-0 items-center gap-3">
                                        <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-glow">
                                            <CpuChipIcon className="h-5 w-5 text-white" />
                                        </div>
                                        <div>
                                            <span className="text-lg font-extrabold text-white">Auto<span className="text-primary-400">Scan</span></span>
                                        </div>
                                    </div>
                                    <nav className="flex flex-1 flex-col mt-2">
                                        <ul className="flex flex-1 flex-col gap-y-1">
                                            {filteredNav.map((item) => {
                                                const isActive = currentPath === item.href;
                                                return (
                                                    <li key={item.label}>
                                                        <Link href={item.href} className={(isActive ? 'sidebar-link-active' : 'sidebar-link') + ' group'} onClick={() => setSidebarOpen(false)}>
                                                            <item.icon className={isActive ? 'sidebar-icon text-primary-400' : 'sidebar-icon group-hover:text-primary-400'} />
                                                            {item.label}
                                                        </Link>
                                                    </li>
                                                );
                                            })}
                                        </ul>
                                    </nav>
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </Dialog>
            </Transition.Root>

            {/* ── Desktop sidebar ── */}
            <div className="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-[260px] lg:flex-col shadow-sidebar">
                <div className="flex grow flex-col gap-y-5 overflow-y-auto bg-dark-900 px-5 pb-4 pt-2">
                    {/* Logo */}
                    <div className="flex h-16 shrink-0 items-center gap-3 px-1">
                        <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-glow">
                            <CpuChipIcon className="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <span className="text-lg font-extrabold text-white">Auto<span className="text-primary-400">Scan</span></span>
                            <p className="text-[9px] font-semibold uppercase tracking-[0.15em] text-dark-500 -mt-0.5">Sistema de Gestion</p>
                        </div>
                    </div>

                    {/* Nav */}
                    <nav className="flex flex-1 flex-col mt-2">
                        <ul className="flex flex-1 flex-col gap-y-0.5">
                            {filteredNav.map((item) => {
                                const isActive = currentPath === item.href;
                                return (
                                    <li key={item.label}>
                                        <Link href={item.href} className={(isActive ? 'sidebar-link-active' : 'sidebar-link') + ' group'}>
                                            <item.icon className={isActive ? 'sidebar-icon text-primary-400' : 'sidebar-icon group-hover:text-primary-400'} />
                                            {item.label}
                                        </Link>
                                    </li>
                                );
                            })}
                        </ul>

                        {/* User at bottom */}
                        <div className="border-t border-dark-800 pt-4 mt-4">
                            <div className="flex items-center gap-3 px-1">
                                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 text-sm font-bold text-white shadow-sm">
                                    {user.initials || user.name.charAt(0).toUpperCase()}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="truncate text-sm font-semibold text-white">{user.name}</p>
                                    <p className="truncate text-xs text-dark-500">{user.role_label || user.role}</p>
                                </div>
                                <SignalIcon className="h-4 w-4 text-success-500" />
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            {/* ── Main content ── */}
            <div className="lg:pl-[260px]">
                {/* Top bar */}
                <div className="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-dark-100 bg-white/80 backdrop-blur-xl px-4 sm:gap-x-6 sm:px-6 lg:px-8">
                    <button type="button" className="-m-2.5 p-2.5 text-dark-600 lg:hidden rounded-lg hover:bg-dark-100" onClick={() => setSidebarOpen(true)}>
                        <Bars3Icon className="h-6 w-6" />
                    </button>

                    <div className="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        {header && <div className="flex items-center">{header}</div>}
                        <div className="ml-auto flex items-center gap-x-3">
                            <NotificationBell />
                            <Menu as="div" className="relative">
                                <Menu.Button className="flex items-center gap-2 rounded-xl bg-dark-50 p-1.5 hover:bg-dark-100 transition-colors border border-dark-100">
                                    <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-accent-500 text-xs font-bold text-white">
                                        {user.initials || user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <span className="hidden sm:block text-sm font-medium text-dark-700 pr-1">{user.name}</span>
                                    <ChevronDownIcon className="h-4 w-4 text-dark-400" />
                                </Menu.Button>
                                <Transition as={Fragment} enter="transition ease-out duration-100" enterFrom="transform opacity-0 scale-95" enterTo="transform opacity-100 scale-100" leave="transition ease-in duration-75" leaveFrom="transform opacity-100 scale-100" leaveTo="transform opacity-0 scale-95">
                                    <Menu.Items className="absolute right-0 z-10 mt-2 w-60 origin-top-right rounded-2xl bg-white py-2 shadow-card-hover ring-1 ring-dark-100 focus:outline-none border border-dark-100">
                                        <div className="px-4 py-3 border-b border-dark-100">
                                            <p className="text-sm font-bold text-dark-900">{user.name}</p>
                                            <p className="text-xs text-dark-500">{user.email}</p>
                                        </div>
                                        <Menu.Item>
                                            {({ active }) => (
                                                <Link href="/profile" className={`flex items-center gap-2.5 px-4 py-2.5 text-sm ${active ? 'bg-dark-50 text-dark-900' : 'text-dark-600'}`}>
                                                    <UserCircleIcon className="h-4 w-4" /> Mi Perfil
                                                </Link>
                                            )}
                                        </Menu.Item>
                                        {user.role === 'admin' && (
                                            <Menu.Item>
                                                {({ active }) => (
                                                    <Link href="/notificaciones" className={`flex items-center gap-2.5 px-4 py-2.5 text-sm ${active ? 'bg-dark-50 text-dark-900' : 'text-dark-600'}`}>
                                                        <Cog6ToothIcon className="h-4 w-4" /> Configuracion
                                                    </Link>
                                                )}
                                            </Menu.Item>
                                        )}
                                        <Menu.Item>
                                            {({ active }) => (
                                                <form onSubmit={handleLogout}>
                                                    <button type="submit" className={`w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-sm text-danger-600 ${active ? 'bg-danger-50' : ''}`}>
                                                        <ArrowRightOnRectangleIcon className="h-4 w-4" /> Cerrar Sesion
                                                    </button>
                                                </form>
                                            )}
                                        </Menu.Item>
                                    </Menu.Items>
                                </Transition>
                            </Menu>
                        </div>
                    </div>
                </div>

                <main className="py-6 px-4 sm:px-6 lg:px-8">
                    <div className="animate-fade-in">{children}</div>
                </main>
            </div>
        </div>
    );
}
