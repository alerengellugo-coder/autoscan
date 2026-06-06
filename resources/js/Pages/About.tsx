import React from 'react';
import { Head } from '@inertiajs/react';
import PublicLayout from '../Layouts/PublicLayout';
import {
    ShieldCheckIcon,
    UsersIcon,
    WrenchScrewdriverIcon,
    AcademicCapIcon,
    HeartIcon,
    TrophyIcon,
} from '@heroicons/react/24/outline';

const values = [
    {
        icon: ShieldCheckIcon,
        title: 'Confianza',
        description: 'Transparencia total en cada diagnóstico y servicio que realizamos.',
    },
    {
        icon: AcademicCapIcon,
        title: 'Profesionalismo',
        description: 'Técnicos certificados con actualización constante en nuevas tecnologías.',
    },
    {
        icon: WrenchScrewdriverIcon,
        title: 'Excelencia',
        description: 'Comprometidos con los más altos estándares de calidad en cada trabajo.',
    },
    {
        icon: HeartIcon,
        title: 'Compromiso',
        description: 'Nos apasiona mantener tu vehículo en óptimas condiciones.',
    },
    {
        icon: UsersIcon,
        title: 'Trabajo en Equipo',
        description: 'Colaboración estrecha para resolver los problemas más complejos.',
    },
    {
        icon: TrophyIcon,
        title: 'Resultados',
        description: 'Nuestro éxito se mide por la satisfacción de nuestros clientes.',
    },
];

const team = [
    { name: 'Ing. Juan Martínez', role: 'Director General', initials: 'JM' },
    { name: 'Ing. Ana López', role: 'Jefa de Diagnóstico', initials: 'AL' },
    { name: 'Téc. Pedro Ramírez', role: 'Especialista Eléctrico', initials: 'PR' },
    { name: 'Téc. Laura Sánchez', role: 'Mecánica General', initials: 'LS' },
];

export default function About() {
    return (
        <>
            <Head title="Nosotros - AutoScan" />
            <PublicLayout>
                {/* Hero */}
                <section className="bg-gradient-to-br from-dark-900 to-primary-900 py-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <h1 className="text-4xl sm:text-5xl font-extrabold text-white">
                            Nosotros
                        </h1>
                        <p className="mt-4 text-lg text-gray-300 max-w-2xl mx-auto">
                            Conoce al equipo de profesionales detrás de AutoScan,
                            líderes en diagnóstico automotriz.
                        </p>
                    </div>
                </section>

                {/* Mission */}
                <section className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid lg:grid-cols-2 gap-12 items-center">
                            <div>
                                <h2 className="text-3xl font-bold text-gray-900">
                                    Nuestra Misión
                                </h2>
                                <p className="mt-4 text-gray-600 leading-relaxed">
                                    Brindar servicios de diagnóstico y reparación
                                    automotriz de la más alta calidad, utilizando
                                    tecnología de vanguardia y un equipo de
                                    profesionales altamente capacitados, para
                                    garantizar la seguridad y satisfacción de
                                    nuestros clientes.
                                </p>
                                <p className="mt-4 text-gray-600 leading-relaxed">
                                    Desde nuestros inicios, nos hemos comprometido
                                    a ser líderes en innovación dentro del sector
                                    automotriz, desarrollando herramientas y
                                    procesos que agilizan el diagnóstico y
                                    mejoran la experiencia del cliente.
                                </p>
                            </div>
                            <div className="bg-gradient-to-br from-primary-50 to-secondary-50 rounded-2xl p-12 flex items-center justify-center">
                                <div className="grid grid-cols-2 gap-8 text-center">
                                    <div>
                                        <div className="text-3xl font-extrabold text-primary-600">15+</div>
                                        <div className="text-sm text-gray-600 mt-1">Años de experiencia</div>
                                    </div>
                                    <div>
                                        <div className="text-3xl font-extrabold text-secondary-600">50+</div>
                                        <div className="text-sm text-gray-600 mt-1">Profesionales</div>
                                    </div>
                                    <div>
                                        <div className="text-3xl font-extrabold text-emerald-600">10K+</div>
                                        <div className="text-sm text-gray-600 mt-1">Servicios realizados</div>
                                    </div>
                                    <div>
                                        <div className="text-3xl font-extrabold text-purple-600">98%</div>
                                        <div className="text-sm text-gray-600 mt-1">Satisfacción</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Values */}
                <section className="py-20 bg-gray-50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900">Nuestros Valores</h2>
                            <p className="mt-4 text-gray-600 max-w-2xl mx-auto">
                                Los principios que guían cada acción y decisión de nuestro equipo.
                            </p>
                        </div>
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {values.map((v) => (
                                <div
                                    key={v.title}
                                    className="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-card-hover transition-shadow"
                                >
                                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 mb-4">
                                        <v.icon className="h-6 w-6 text-primary-600" />
                                    </div>
                                    <h3 className="text-lg font-bold text-gray-900 mb-2">{v.title}</h3>
                                    <p className="text-sm text-gray-600">{v.description}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Team */}
                <section className="py-20 bg-white">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-16">
                            <h2 className="text-3xl font-bold text-gray-900">Nuestro Equipo</h2>
                            <p className="mt-4 text-gray-600 max-w-2xl mx-auto">
                                Profesionales apasionados por el mundo automotriz.
                            </p>
                        </div>
                        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                            {team.map((member) => (
                                <div key={member.name} className="text-center group">
                                    <div className="mx-auto h-24 w-24 rounded-full bg-gradient-to-br from-primary-500 to-secondary-500 flex items-center justify-center text-2xl font-bold text-white shadow-lg group-hover:shadow-xl transition-shadow">
                                        {member.initials}
                                    </div>
                                    <h3 className="mt-4 font-semibold text-gray-900">{member.name}</h3>
                                    <p className="text-sm text-gray-500">{member.role}</p>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            </PublicLayout>
        </>
    );
}
