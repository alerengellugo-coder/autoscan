<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\ServiceOrder;
use App\Models\ServiceReport;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Vehicle;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * Root dashboard: redirect by role.
     */
    public function redirectByRole(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        return redirect()->route(match (Auth::user()->role) {
            'admin'      => 'admin.dashboard',
            'technician' => 'technician.dashboard',
            'client'     => 'client.dashboard',
            default      => 'home',
        });
    }

    /**
     * Admin dashboard.
     * Expects: stats, recent_orders, low_stock_products, recent_quotations
     */
    public function adminDashboard(): Response
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();

        $stats = [
            'total_orders'         => ServiceOrder::count(),
            'active_orders'        => ServiceOrder::whereIn('status', ['pending', 'in_progress', 'diagnosing'])->count(),
            'completed_this_month' => ServiceOrder::where('status', 'completed')
                ->where('completed_at', '>=', $startOfMonth)->count(),
            'monthly_revenue'      => (float) Quotation::where('status', 'approved')
                ->whereBetween('approved_at', [$startOfMonth, $now])
                ->sum('total'),
        ];

        $recent_orders = ServiceOrder::with(['vehicle', 'client', 'technician'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($o) => [
                'id'             => $o->id,
                'order_number'   => $o->order_number,
                'status'         => $o->status,
                'status_label'   => $o->status_label ?? $o->status,
                'priority'       => $o->priority,
                'priority_label' => $o->priority_label ?? $o->priority,
                'created_at'     => $o->created_at->toISOString(),
                'vehicle'        => $o->vehicle ? [
                    'plate' => $o->vehicle->plate,
                    'brand' => $o->vehicle->brand,
                    'model' => $o->vehicle->model,
                ] : null,
                'client' => $o->client ? ['name' => $o->client->name] : null,
            ]);

        $low_stock_products = Product::whereRaw('stock_quantity <= min_stock_alert')
            ->orWhere('stock_quantity', 0)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get()
            ->map(fn ($p) => [
                'id'        => $p->id,
                'name'      => $p->name,
                'stock'     => $p->stock,
                'min_stock' => $p->min_stock,
            ]);

        $recent_quotations = Quotation::with(['client'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($q) => [
                'id'               => $q->id,
                'quotation_number' => $q->quotation_number,
                'status'           => $q->status,
                'status_label'     => $q->status_label ?? $q->status,
                'total'            => (float) $q->total,
                'client'           => $q->client ? ['name' => $q->client->name] : null,
            ]);

        return Inertia::render('Dashboard/AdminDashboard', [
            'stats'              => $stats,
            'recent_orders'      => $recent_orders,
            'low_stock_products' => $low_stock_products,
            'recent_quotations'  => $recent_quotations,
        ]);
    }

    /**
     * Technician dashboard.
     * Expects: stats, active_orders, recent_reports
     */
    public function technicianDashboard(): Response
    {
        $user = Auth::user();

        $stats = [
            'assigned_orders'     => ServiceOrder::where('technician_id', $user->id)->count(),
            'active_orders'       => ServiceOrder::where('technician_id', $user->id)
                ->whereIn('status', ['pending', 'in_progress', 'diagnosing'])->count(),
            'completed_today'     => ServiceOrder::where('technician_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('completed_at', today())->count(),
            'pending_diagnostics' => ServiceOrder::where('technician_id', $user->id)
                ->where('status', 'pending')->count(),
        ];

        $active_orders = ServiceOrder::with(['vehicle', 'client'])
            ->where('technician_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'diagnosing'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($o) => [
                'id'             => $o->id,
                'order_number'   => $o->order_number,
                'status'         => $o->status,
                'status_label'   => $o->status_label ?? $o->status,
                'priority'       => $o->priority,
                'priority_label' => $o->priority_label ?? $o->priority,
                'created_at'     => $o->created_at->toISOString(),
                'vehicle'        => $o->vehicle ? [
                    'id'    => $o->vehicle->id,
                    'plate' => $o->vehicle->plate,
                    'brand' => $o->vehicle->brand,
                    'model' => $o->vehicle->model,
                ] : null,
                'client' => $o->client ? ['name' => $o->client->name] : null,
            ]);

        $recent_reports = ServiceReport::with(['serviceOrder.vehicle', 'technician'])
            ->where('technician_id', $user->id)
            ->latest('report_date')
            ->take(5)
            ->get()
            ->map(fn ($r) => [
                'id'               => $r->id,
                'report_date'      => $r->report_date?->toISOString(),
                'description'      => $r->description,
                'work_performed'   => $r->work_performed,
                'labor_hours'      => $r->labor_hours,
                'service_order'    => $r->serviceOrder ? [
                    'id'           => $r->serviceOrder->id,
                    'order_number' => $r->serviceOrder->order_number,
                    'vehicle'      => $r->serviceOrder->vehicle ? [
                        'plate' => $r->serviceOrder->vehicle->plate,
                    ] : null,
                ] : null,
                'technician'       => $r->technician ? ['name' => $r->technician->name] : null,
            ]);

        return Inertia::render('Dashboard/TechnicianDashboard', [
            'stats'          => $stats,
            'active_orders'  => $active_orders,
            'recent_reports' => $recent_reports,
        ]);
    }

    /**
     * Client dashboard.
     * Expects: vehicles, active_orders, notifications
     */
    public function clientDashboard(): Response
    {
        $user = Auth::user();

        $vehicles = Vehicle::where('client_id', $user->id)
            ->latest()
            ->get()
            ->map(fn ($v) => [
                'id'       => $v->id,
                'brand'    => $v->brand,
                'model'    => $v->model,
                'year'     => $v->year,
                'plate'    => $v->plate,
                'color'    => $v->color,
                'status'   => $v->status,
                'status_label' => $v->status_label ?? $v->status,
                'full_name' => $v->brand . ' ' . $v->model . ' ' . $v->year,
            ]);

        $active_orders = ServiceOrder::with(['vehicle', 'technician'])
            ->where('client_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'diagnosing'])
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($o) => [
                'id'             => $o->id,
                'order_number'   => $o->order_number,
                'status'         => $o->status,
                'status_label'   => $o->status_label ?? $o->status,
                'priority'       => $o->priority,
                'priority_label' => $o->priority_label ?? $o->priority,
                'service_type'   => $o->service_type,
                'created_at'     => $o->created_at->toISOString(),
                'vehicle'        => $o->vehicle ? [
                    'id'    => $o->vehicle->id,
                    'plate' => $o->vehicle->plate,
                    'brand' => $o->vehicle->brand,
                    'model' => $o->vehicle->model,
                ] : null,
                'technician'     => $o->technician ? ['name' => $o->technician->name] : null,
            ]);

        $notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'data'       => $n->data,
                'read_at'    => $n->read_at?->toISOString(),
                'created_at' => $n->created_at->toISOString(),
            ]);

        return Inertia::render('Dashboard/ClientDashboard', [
            'vehicles'      => $vehicles,
            'active_orders' => $active_orders,
            'notifications' => $notifications,
        ]);
    }
}
