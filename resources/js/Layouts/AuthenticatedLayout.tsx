import React, { useState, Fragment } from 'react';
import { usePage, Link } from '@inertiajs/react';
import { Dialog, Transition, Menu } from '@headlessui/react';
import {
    Bars3Icon,
    XMarkIcon,
    HomeIcon,
    WrenchScrewdriverIcon,
    TruckIcon,
    ClipboardDocumentListIcon,
    ShoppingBagIcon,
    DocumentTextIcon,
    CubeIcon,
    BellIcon,
    UserCircleIcon,
    ArrowRightOnRectangleIcon,
    Cog6ToothIcon,
    BuildingOfficeIcon,
    ChevronDownIcon,
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
    {
        label: 'Panel',
        href: '/dashboard',
        icon: HomeIcon,
        roles: ['admin', 'technician', 'client'],
    },
    {
        label: 'Vehículos',
        href: '/admin/vehiculos',
        icon: TruckIcon,
        roles: ['admin'],
    },
    {
        label: 'Mis Vehículos',
        href: '/mi-cuenta/vehiculos',
        icon: TruckIcon,
        roles: ['client'],
    },
    {
        label: 'Órdenes',
        href: '/admin/ordenes',
        icon: ClipboardDocumentListIcon,
        roles: ['admin'],
    },
    {
        label: 'Mis Órdenes',
        href: '/tecnico/ordenes',
        icon: ClipboardDocumentListIcon,
        roles: ['technician'],
    },
    {
        label: 'Mis Órdenes',
        href: '/mi-cuenta/ordenes',
        icon: ClipboardDocumentListIcon,
        roles: ['client'],
    },
    {
        label: 'Productos',
        href: '/admin/productos',
        icon: CubeIcon,
        roles: ['admin'],
    },
    {
        label: 'Catálogo',
        href: '/tecnico/catalogo',
        icon: CubeIcon,
        roles: ['technician'],
    },
    {
        label: 'Cotizaciones',
        href: '/admin/cotizaciones',
        icon: DocumentTextIcon,
        roles: ['admin'],
    },
    {
        label: 'Mis Cotizaciones',
        href: '/mi-cuenta/cotizaciones',
        icon: DocumentTextIcon,
        roles: ['client'],
    },
    {
        label: 'Ventas',
        href: '/admin/ventas',
        icon: ShoppingBagIcon,
        roles: ['admin'],
    },
    {
        label: 'Mis Compras',
        href: '/mi-cuenta/ventas',
        icon: ShoppingBagIcon,
        roles: ['client'],
    },
    {
        label: 'Reportes',
        href: '/admin/reportes-servicio',
        icon: WrenchScrewdriverIcon,
        roles: ['admin'],
    },
    {
        label: 'Reportes',
        href: '/tecnico/reportes',
        icon: WrenchScrewdriverIcon,
        roles: ['technician'],
    },
    {
        label: 'Usuarios',
        href: '/admin/usuarios',
        icon: UserCircleIcon,
        roles: ['admin'],
    },
    {
        label: 'Notificaciones',
        href: '/notificaciones',
        icon: BellIcon,
        roles: ['admin', 'technician', 'client'],
    },
];

export default function AuthenticatedLayout({
    children,
    header,
}: AuthenticatedLayoutProps) {
    const { auth, flash } = usePage<PageProps>().props;
    const user = auth.user as User;
    const [sidebarOpen, setSidebarOpen] = useState(false);

    const filteredNav = navigation.filter((item) =>
        item.roles.includes(user.role)
    );

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        window.location.href = '/logout';
    };

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Flash Messages */}
            <FlashMessage flash={flash} />

            {/* Mobile sidebar */}
            <Transition.Root show={sidebarOpen} as={Fragment}>
                <Dialog
                    as="div"
                    className="relative z-50 lg:hidden"
                    onClose={setSidebarOpen}
                >
                    <Transition.Child
                        as={Fragment}
                        enter="transition-opacity ease-linear duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="transition-opacity ease-linear duration-300"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <div className="fixed inset-0 bg-gray-900/80" />
                    </Transition.Child>

                    <div className="fixed inset-0 flex">
                        <Transition.Child
                            as={Fragment}
                            enter="transition ease-in-out duration-300 transform"
                            enterFrom="-translate-x-full"
                            enterTo="translate-x-0"
                            leave="transition ease-in-out duration-300 transform"
                            leaveFrom="translate-x-0"
                            leaveTo="-translate-x-full"
                        >
                            <Dialog.Panel className="relative mr-16 flex w-full max-w-xs flex-1">
                                <div className="absolute left-full top-0 flex w-16 justify-center pt-5">
                                    <button
                                        type="button"
                                        className="-m-2.5 p-2.5"
                                        onClick={() => setSidebarOpen(false)}
                                    >
                                        <XMarkIcon
                                            className="h-6 w-6 text-white"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>

                                <div className="flex grow flex-col gap-y-5 overflow-y-auto bg-dark-900 px-6 pb-4">
                                    {/* Logo */}
                                    <div className="flex h-16 shrink-0 items-center">
                                        <Link
                                            href="/dashboard"
                                            className="flex items-center gap-2"
                                        >
                                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-500">
                                                <WrenchScrewdriverIcon className="h-5 w-5 text-white" />
                                            </div>
                                            <span className="text-lg font-bold text-white">
                                                AutoScan
                                            </span>
                                        </Link>
                                    </div>

                                    {/* Navigation */}
                                    <nav className="flex flex-1 flex-col">
                                        <ul className="flex flex-1 flex-col gap-y-1">
                                            {filteredNav.map((item) => (
                                                <li key={item.label}>
                                                    <Link
                                                        href={item.href}
                                                        className="group flex gap-x-3 rounded-lg p-2 text-sm font-semibold leading-6 text-dark-300 hover:bg-dark-800 hover:text-white"
                                                    >
                                                        <item.icon
                                                            className="h-5 w-5 shrink-0 text-dark-400 group-hover:text-primary-400"
                                                            aria-hidden="true"
                                                        />
                                                        {item.label}
                                                    </Link>
                                                </li>
                                            ))}
                                        </ul>
                                    </nav>
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </Dialog>
            </Transition.Root>

            {/* Desktop sidebar */}
            <div className="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
                <div className="flex grow flex-col gap-y-5 overflow-y-auto bg-dark-900 px-6 pb-4">
                    {/* Logo */}
                    <div className="flex h-16 shrink-0 items-center">
                        <Link
                            href="/dashboard"
                            className="flex items-center gap-2"
                        >
                            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-500">
                                <WrenchScrewdriverIcon className="h-5 w-5 text-white" />
                            </div>
                            <span className="text-lg font-bold text-white">
                                AutoScan
                            </span>
                        </Link>
                    </div>

                    {/* Navigation */}
                    <nav className="flex flex-1 flex-col">
                        <ul className="flex flex-1 flex-col gap-y-1">
                            {filteredNav.map((item) => {
                                const isActive =
                                    typeof window !== 'undefined' &&
                                    window.location.pathname === item.href;
                                return (
                                    <li key={item.label}>
                                        <Link
                                            href={item.href}
                                            className={`group flex gap-x-3 rounded-lg p-2 text-sm font-semibold leading-6 transition-colors ${
                                                isActive
                                                    ? 'bg-dark-800 text-white'
                                                    : 'text-dark-300 hover:bg-dark-800 hover:text-white'
                                            }`}
                                        >
                                            <item.icon
                                                className={`h-5 w-5 shrink-0 ${
                                                    isActive
                                                        ? 'text-primary-400'
                                                        : 'text-dark-400 group-hover:text-primary-400'
                                                }`}
                                                aria-hidden="true"
                                            />
                                            {item.label}
                                        </Link>
                                    </li>
                                );
                            })}
                        </ul>

                        {/* User info at bottom */}
                        <div className="border-t border-dark-800 pt-4">
                            <div className="flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                                    {user.initials || user.name.charAt(0).toUpperCase()}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="truncate text-sm font-medium text-white">
                                        {user.name}
                                    </p>
                                    <p className="truncate text-xs text-dark-400">
                                        {user.role_label || user.role}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            {/* Main content area */}
            <div className="lg:pl-64">
                {/* Top navbar */}
                <div className="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                    {/* Mobile menu button */}
                    <button
                        type="button"
                        className="-m-2.5 p-2.5 text-gray-700 lg:hidden"
                        onClick={() => setSidebarOpen(true)}
                    >
                        <span className="sr-only">
                            Abrir menú lateral
                        </span>
                        <Bars3Icon
                            className="h-6 w-6"
                            aria-hidden="true"
                        />
                    </button>

                    {/* Separator for mobile */}
                    <div
                        className="h-6 w-px bg-gray-200 lg:hidden"
                        aria-hidden="true"
                    />

                    {/* Header section */}
                    <div className="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        {header && (
                            <div className="flex items-center">
                                {header}
                            </div>
                        )}

                        <div className="ml-auto flex items-center gap-x-4">
                            {/* Notification bell */}
                            <NotificationBell />

                            {/* Profile dropdown */}
                            <Menu
                                as="div"
                                className="relative"
                            >
                                <Menu.Button className="flex items-center gap-2 rounded-full bg-white p-1 text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    <span className="sr-only">
                                        Abrir menú de usuario
                                    </span>
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white">
                                        {user.initials || user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <ChevronDownIcon className="h-4 w-4" />
                                </Menu.Button>

                                <Transition
                                    as={Fragment}
                                    enter="transition ease-out duration-100"
                                    enterFrom="transform opacity-0 scale-95"
                                    enterTo="transform opacity-100 scale-100"
                                    leave="transition ease-in duration-75"
                                    leaveFrom="transform opacity-100 scale-100"
                                    leaveTo="transform opacity-0 scale-95"
                                >
                                    <Menu.Items className="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none">
                                        <div className="px-4 py-3 border-b border-gray-100">
                                            <p className="text-sm font-medium text-gray-900">
                                                {user.name}
                                            </p>
                                            <p className="text-xs text-gray-500">
                                                {user.email}
                                            </p>
                                        </div>
                                        <Menu.Item>
                                            {({ active }) => (
                                                <Link
                                                    href={route('profile.edit')}
                                                    className={`block px-4 py-2 text-sm ${
                                                        active
                                                            ? 'bg-gray-50 text-gray-900'
                                                            : 'text-gray-700'
                                                    }`}
                                                >
                                                    <span className="flex items-center gap-2">
                                                        <UserCircleIcon className="h-4 w-4" />
                                                        Mi Perfil
                                                    </span>
                                                </Link>
                                            )}
                                        </Menu.Item>
                                        {user.role === 'admin' && (
                                            <Menu.Item>
                                                {({ active }) => (
                                                    <Link
                                                        href="/notificaciones"
                                                        className={`block px-4 py-2 text-sm ${
                                                            active
                                                                ? 'bg-gray-50 text-gray-900'
                                                                : 'text-gray-700'
                                                        }`}
                                                    >
                                                        <span className="flex items-center gap-2">
                                                            <Cog6ToothIcon className="h-4 w-4" />
                                                            Configuración
                                                        </span>
                                                    </Link>
                                                )}
                                            </Menu.Item>
                                        )}
                                        <Menu.Item>
                                            {({ active }) => (
                                                <form
                                                    onSubmit={handleLogout}
                                                >
                                                    <button
                                                        type="submit"
                                                        className={`w-full text-left px-4 py-2 text-sm ${
                                                            active
                                                                ? 'bg-gray-50 text-gray-900'
                                                                : 'text-gray-700'
                                                        }`}
                                                    >
                                                        <span className="flex items-center gap-2">
                                                            <ArrowRightOnRectangleIcon className="h-4 w-4" />
                                                            Cerrar Sesión
                                                        </span>
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

                {/* Page content */}
                <main className="py-6 px-4 sm:px-6 lg:px-8">
                    <div className="animate-fade-in">{children}</div>
                </main>
            </div>
        </div>
    );
}

// Helper for route function (Ziggy)
function route(name: string, params?: Record<string, unknown>): string {
    const ziggy = (window as unknown as { ziggy: { location: string } }).ziggy;
    if (name === 'profile.edit') return '/profile';
    if (name === 'login') return '/login';
    if (name === 'register') return '/register';
    return `#${name}`;
}
