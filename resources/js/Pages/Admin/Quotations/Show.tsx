import React from 'react';
import { usePage, Link, useForm, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatusBadge from '@/Components/StatusBadge';
import { DocumentTextIcon, CheckIcon, XMarkIcon, ArrowPathIcon, TrashIcon } from '@heroicons/react/24/outline';

const quotationStatusColors: Record<string, string> = {
  draft: 'bg-gray-100 text-gray-800',
  pending_client: 'bg-yellow-100 text-yellow-800',
  approved: 'bg-green-100 text-green-800',
  rejected: 'bg-red-100 text-red-800',
  expired: 'bg-gray-100 text-gray-500',
};

const quotationStatusLabels: Record<string, string> = {
  draft: 'Borrador',
  pending_client: 'Pendiente de Cliente',
  approved: 'Aprobada',
  rejected: 'Rechazada',
  expired: 'Expirada',
};

interface QuotationItem {
  id: number;
  item_type: string;
  name: string;
  description?: string;
  quantity: number;
  unit_price: number;
  discount: number;
  total: number;
  product?: any;
}

interface Quotation {
  id: number;
  quotation_number: string;
  status: string;
  subtotal: number;
  tax: number;
  tax_percentage: number;
  discount: number;
  total: number;
  valid_until: string | null;
  notes: string | null;
  approved_at: string | null;
  created_at: string;
  client: { id: number; name: string; email: string };
  vehicle: { id: number; full_name: string; plate: string } | null;
  technician: { id: number; name: string } | null;
  service_order: { id: number; order_number: string } | null;
  items: QuotationItem[];
}

export default function Show() {
  const { quotation } = usePage().props as any;
  const q = quotation as Quotation;

  const handleUpdateStatus = (status: string) => {
    router.patch(route('admin.cotizaciones.update-status', q.id), { status }, {
      onSuccess: () => {},
    });
  };

  const handleConvertToSale = () => {
    router.post(route('admin.cotizaciones.convert-to-sale', q.id));
  };

  const handleGeneratePdf = () => {
    window.open(route('admin.cotizaciones.pdf', q.id), '_blank');
  };

  return (
    <AuthenticatedLayout>
      <div className="py-6">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Header */}
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
              <h1 className="text-2xl font-bold text-gray-900">Cotización {q.quotation_number}</h1>
              <p className="mt-1 text-sm text-gray-500">Creada el {new Date(q.created_at).toLocaleDateString('es-VE')}</p>
            </div>
            <div className="mt-4 sm:mt-0 flex flex-wrap gap-2">
              {q.status === 'draft' && (
                <>
                  <button onClick={() => handleUpdateStatus('pending_client')} className="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    <ArrowPathIcon className="w-4 h-4 mr-2" /> Enviar al Cliente
                  </button>
                  <Link href={route('admin.cotizaciones.edit', q.id)} className="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    Editar
                  </Link>
                </>
              )}
              {q.status === 'pending_client' && (
                <>
                  <button onClick={() => handleUpdateStatus('approved')} className="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <CheckIcon className="w-4 h-4 mr-2" /> Aprobar
                  </button>
                  <button onClick={() => handleUpdateStatus('rejected')} className="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <XMarkIcon className="w-4 h-4 mr-2" /> Rechazar
                  </button>
                </>
              )}
              {q.status === 'approved' && (
                <>
                  <button onClick={handleConvertToSale} className="inline-flex items-center px-4 py-2 bg-secondary-600 text-white rounded-lg hover:bg-secondary-700">
                    Convertir a Venta
                  </button>
                  <button onClick={handleGeneratePdf} className="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <DocumentTextIcon className="w-4 h-4 mr-2" /> PDF
                  </button>
                </>
              )}
              {q.status === 'draft' && (
                <Link href={route('admin.cotizaciones.edit', q.id)} method="delete" as="button" className="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                  <TrashIcon className="w-4 h-4 mr-2" /> Eliminar
                </Link>
              )}
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Main content */}
            <div className="lg:col-span-2 space-y-6">
              {/* Info cards */}
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="bg-white rounded-lg border p-4">
                  <p className="text-xs font-medium text-gray-500 uppercase">Estado</p>
                  <span className={`inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-medium ${quotationStatusColors[q.status]}`}>
                    {quotationStatusLabels[q.status]}
                  </span>
                </div>
                <div className="bg-white rounded-lg border p-4">
                  <p className="text-xs font-medium text-gray-500 uppercase">Cliente</p>
                  <p className="mt-1 text-sm font-semibold text-gray-900">{q.client?.name}</p>
                  <p className="text-xs text-gray-500">{q.client?.email}</p>
                </div>
                <div className="bg-white rounded-lg border p-4">
                  <p className="text-xs font-medium text-gray-500 uppercase">Vehículo</p>
                  <p className="mt-1 text-sm font-semibold text-gray-900">{q.vehicle?.full_name || 'N/A'}</p>
                  <p className="text-xs text-gray-500">{q.vehicle?.plate || ''}</p>
                </div>
              </div>

              {/* Items table */}
              <div className="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div className="px-6 py-4 border-b">
                  <h2 className="text-lg font-semibold text-gray-900">Detalle de Ítems</h2>
                </div>
                <div className="overflow-x-auto">
                  <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                      <tr>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cant.</th>
                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">P. Unit.</th>
                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Desc.</th>
                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-200">
                      {q.items?.map((item) => (
                        <tr key={item.id} className="hover:bg-gray-50">
                          <td className="px-6 py-4 text-sm">
                            <span className={`px-2 py-0.5 rounded text-xs ${item.item_type === 'product' ? 'bg-blue-100 text-blue-700' : item.item_type === 'service' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700'}`}>
                              {item.item_type === 'product' ? 'Producto' : item.item_type === 'service' ? 'Servicio' : 'Mano de Obra'}
                            </span>
                          </td>
                          <td className="px-6 py-4">
                            <p className="text-sm font-medium text-gray-900">{item.name}</p>
                            {item.description && <p className="text-xs text-gray-500 mt-0.5">{item.description}</p>}
                          </td>
                          <td className="px-6 py-4 text-sm text-right text-gray-900">{item.quantity}</td>
                          <td className="px-6 py-4 text-sm text-right text-gray-900">${Number(item.unit_price).toFixed(2)}</td>
                          <td className="px-6 py-4 text-sm text-right text-gray-500">{item.discount > 0 ? `$${Number(item.discount).toFixed(2)}` : '-'}</td>
                          <td className="px-6 py-4 text-sm text-right font-medium text-gray-900">${Number(item.total).toFixed(2)}</td>
                        </tr>
                      ))}
                      {(!q.items || q.items.length === 0) && (
                        <tr><td colSpan={6} className="px-6 py-8 text-center text-gray-500">No hay ítems en esta cotización</td></tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            {/* Sidebar - Totals */}
            <div className="space-y-6">
              <div className="bg-white rounded-xl shadow-sm border p-6">
                <h2 className="text-lg font-semibold text-gray-900 mb-4">Resumen</h2>
                <div className="space-y-3">
                  <div className="flex justify-between text-sm">
                    <span className="text-gray-500">Subtotal</span>
                    <span className="font-medium">${Number(q.subtotal).toFixed(2)}</span>
                  </div>
                  <div className="flex justify-between text-sm">
                    <span className="text-gray-500">IVA ({q.tax_percentage}%)</span>
                    <span className="font-medium">${Number(q.tax).toFixed(2)}</span>
                  </div>
                  {q.discount > 0 && (
                    <div className="flex justify-between text-sm text-green-600">
                      <span>Descuento</span>
                      <span>-${Number(q.discount).toFixed(2)}</span>
                    </div>
                  )}
                  <div className="border-t pt-3 flex justify-between">
                    <span className="text-lg font-bold text-gray-900">Total</span>
                    <span className="text-lg font-bold text-primary-600">${Number(q.total).toFixed(2)}</span>
                  </div>
                </div>
              </div>

              {q.valid_until && (
                <div className="bg-yellow-50 rounded-xl border border-yellow-200 p-4">
                  <p className="text-sm font-medium text-yellow-800">Válida hasta</p>
                  <p className="text-sm text-yellow-700">{new Date(q.valid_until).toLocaleDateString('es-VE', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                </div>
              )}

              {q.notes && (
                <div className="bg-white rounded-xl shadow-sm border p-6">
                  <h3 className="text-sm font-semibold text-gray-900 mb-2">Notas</h3>
                  <p className="text-sm text-gray-600 whitespace-pre-wrap">{q.notes}</p>
                </div>
              )}

              {q.service_order && (
                <div className="bg-white rounded-xl shadow-sm border p-4">
                  <p className="text-xs font-medium text-gray-500">Orden de Servicio</p>
                  <Link href={route('admin.ordenes.show', q.service_order.id)} className="text-sm font-semibold text-primary-600 hover:text-primary-700">
                    {q.service_order.order_number}
                  </Link>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
