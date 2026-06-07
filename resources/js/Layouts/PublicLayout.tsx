import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
    Bars3Icon,
    XMarkIcon,
    CpuChipIcon,
} from '@heroicons/react/24/outline';
import { PageProps } from '../types';

interface PublicLayoutProps {
    children: React.ReactNode;
}

export default function PublicLayout({ children }: PublicLayoutProps) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const url = usePage<PageProps>().props.ziggy?.location || '';

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 20);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const navLinks = [
        { label: 'Inicio', href: '/' },
        { label: 'Servicios', href: '/servicios' },
        { label: 'Nosotros', href: '/nosotros' },
        { label: 'Contacto', href: '/contacto' },
    ];

    const isActive = (href: string) => {
        if (href === '/') return url === '/';
        return url.startsWith(href);
    };

    return (
        <div className="min-h-screen bg-dark-50">
            {/* Header */}
            <header className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${scrolled ? 'nav-glass shadow-sm' : 'bg-transparent'}`}>
                <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-18">
                        {/* Logo */}
                        <Link href="/" className="flex items-center gap-3 group">
                            <div className={`flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-glow transition-all duration-300 group-hover:shadow-lg group-hover:scale-105 ${scrolled ? '' : 'ring-1 ring-white/20'}`}>
                                <CpuChipIcon className="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <span className={`text-xl font-extrabold tracking-tight ${scrolled ? 'text-dark-900' : 'text-white'} transition-colors duration-300`}>
                                    Auto<span className={scrolled ? 'text-primary-600' : 'text-primary-400'}>Scan</span>
                                </span>
                                <p className={`text-[10px] font-semibold uppercase tracking-widest -mt-0.5 ${scrolled ? 'text-dark-500' : 'text-dark-300'} transition-colors`}>Diagnostico Profesional</p>
                            </div>
                        </Link>

                        {/* Desktop nav */}
                        <div className="hidden md:flex items-center gap-1">
                            {navLinks.map((link) => (
                                <Link
                                    key={link.label}
                                    href={link.href}
                                    className={`px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 ${
                                        isActive(link.href)
                                            ? scrolled
                                                ? 'text-primary-700 bg-primary-50'
                                                : 'text-white bg-white/15 backdrop-blur-sm'
                                            : scrolled
                                                ? 'text-dark-600 hover:text-primary-600 hover:bg-dark-100'
                                                : 'text-dark-200 hover:text-white hover:bg-white/10'
                                    }`}
                                >
                                    {link.label}
                                </Link>
                            ))}
                        </div>

                        {/* CTA buttons */}
                        <div className="hidden md:flex items-center gap-3">
                            <Link
                                href="/login"
                                className={`text-sm font-medium px-4 py-2 rounded-lg transition-all duration-200 ${
                                    scrolled ? 'text-dark-600 hover:text-primary-600' : 'text-dark-200 hover:text-white'
                                }`}
                            >
                                Iniciar Sesion
                            </Link>
                            <Link
                                href="/register"
                                className="btn-primary btn-sm"
                            >
                                Registrarse
                            </Link>
                        </div>

                        {/* Mobile menu button */}
                        <button
                            type="button"
                            className={`md:hidden p-2 rounded-lg transition-colors ${scrolled ? 'text-dark-700 hover:bg-dark-100' : 'text-white hover:bg-white/10'}`}
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                        >
                            {mobileMenuOpen ? <XMarkIcon className="h-6 w-6" /> : <Bars3Icon className="h-6 w-6" />}
                        </button>
                    </div>

                    {/* Mobile nav */}
                    {mobileMenuOpen && (
                        <div className="md:hidden border-t border-dark-200 py-4 animate-slide-down bg-white rounded-b-2xl shadow-card-hover mx-2 mb-2">
                            <div className="flex flex-col gap-1">
                                {navLinks.map((link) => (
                                    <Link
                                        key={link.label}
                                        href={link.href}
                                        className={`block px-4 py-2.5 text-sm font-medium rounded-lg transition-colors ${
                                            isActive(link.href)
                                                ? 'text-primary-700 bg-primary-50'
                                                : 'text-dark-700 hover:bg-dark-50'
                                        }`}
                                        onClick={() => setMobileMenuOpen(false)}
                                    >
                                        {link.label}
                                    </Link>
                                ))}
                                <hr className="my-2 border-dark-100" />
                                <Link
                                    href="/login"
                                    className="block px-4 py-2.5 text-sm font-medium text-dark-700 hover:bg-dark-50 rounded-lg"
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    Iniciar Sesion
                                </Link>
                                <Link
                                    href="/register"
                                    className="block mx-4 btn-primary text-sm text-center mt-1"
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    Registrarse
                                </Link>
                            </div>
                        </div>
                    )}
                </nav>
            </header>

            {/* Spacer for fixed header */}
            <div className="h-[72px]" />

            {/* Page content */}
            <main>{children}</main>

            {/* Footer */}
            <footer className="bg-dark-900 text-dark-300 relative overflow-hidden">
                {/* Top gradient line */}
                <div className="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-primary-500 to-transparent" />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-10">
                        {/* Brand */}
                        <div className="col-span-1 md:col-span-2">
                            <div className="flex items-center gap-3 mb-5">
                                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-700">
                                    <CpuChipIcon className="h-6 w-6 text-white" />
                                </div>
                                <div>
                                    <span className="text-xl font-extrabold text-white">Auto<span className="text-primary-400">Scan</span></span>
                                    <p className="text-[10px] font-semibold uppercase tracking-widest text-dark-500 -mt-0.5">Diagnostico Profesional</p>
                                </div>
                            </div>
                            <p className="text-sm text-dark-400 max-w-md leading-relaxed">
                                Especialistas en diagnostico electronico automotriz, escaneo de computadoras de vehiculos,
                                reparacion de modulos electricos y mantenimiento integral con tecnologia de vanguardia.
                            </p>
                            {/* Social */}
                            <div className="flex gap-3 mt-6">
                                {[
                                    { label: 'Facebook', path: 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z' },
                                    { label: 'Twitter', path: 'M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z' },
                                    { label: 'Instagram', path: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z' },
                                ].map((social) => (
                                    <a key={social.label} href="#" className="w-10 h-10 rounded-xl bg-dark-800 flex items-center justify-center hover:bg-primary-600 transition-all duration-200 hover:scale-105 hover:shadow-glow">
                                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d={social.path} /></svg>
                                    </a>
                                ))}
                            </div>
                        </div>

                        {/* Quick Links */}
                        <div>
                            <h3 className="text-sm font-bold text-white uppercase tracking-widest mb-5">Enlaces</h3>
                            <ul className="space-y-3">
                                {navLinks.map((link) => (
                                    <li key={link.label}>
                                        <Link href={link.href} className="text-sm text-dark-400 hover:text-primary-400 transition-colors duration-200 flex items-center gap-2">
                                            <span className="w-1 h-1 rounded-full bg-primary-600" />
                                            {link.label}
                                        </Link>
                                    </li>
                                ))}
                                <li>
                                    <Link href="/login" className="text-sm text-dark-400 hover:text-primary-400 transition-colors duration-200 flex items-center gap-2">
                                        <span className="w-1 h-1 rounded-full bg-primary-600" />
                                        Mi Cuenta
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        {/* Contact */}
                        <div>
                            <h3 className="text-sm font-bold text-white uppercase tracking-widest mb-5">Contacto</h3>
                            <ul className="space-y-4">
                                <li className="flex items-start gap-3">
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-600/15">
                                        <svg className="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <span className="text-sm text-dark-400">Av. Principal #123, Centro, Ciudad</span>
                                </li>
                                <li className="flex items-center gap-3">
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-600/15">
                                        <svg className="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    </div>
                                    <span className="text-sm text-dark-400">+52 (555) 123-4567</span>
                                </li>
                                <li className="flex items-center gap-3">
                                    <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary-600/15">
                                        <svg className="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <span className="text-sm text-dark-400">info@autoscan.com</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* Bottom bar */}
                    <div className="border-t border-dark-800 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p className="text-sm text-dark-500">
                            &copy; {new Date().getFullYear()} AutoScan. Todos los derechos reservados.
                        </p>
                        <div className="flex gap-6">
                            <a href="#" className="text-sm text-dark-500 hover:text-dark-300 transition-colors">Privacidad</a>
                            <a href="#" className="text-sm text-dark-500 hover:text-dark-300 transition-colors">Terminos</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
