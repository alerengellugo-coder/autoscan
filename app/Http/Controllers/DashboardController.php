<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceOrder;
use App\Models\ServiceReport;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\Vehicle;

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
    public function adminDashboard()
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
            ->get();

        $low_stock_products = Product::whereRaw('stock_quantity <= min_stock_alert')
            ->orWhere('stock_quantity', 0)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        $recent_quotations = Quotation::with(['client'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', [
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
    public function technicianDashboard()
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
            ->get();

        $recent_reports = ServiceReport::with(['serviceOrder.vehicle', 'technician'])
            ->where('technician_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.technician', [
            'stats'          => $stats,
            'active_orders'  => $active_orders,
            'recent_reports' => $recent_reports,
        ]);
    }

    /**
     * Client dashboard.
     * Expects: vehicles, active_orders, notifications
     */
    public function clientDashboard()
    {
        $user = Auth::user();

        $vehicles = Vehicle::where('client_id', $user->id)
            ->latest()
            ->get();

        $active_orders = ServiceOrder::with(['vehicle', 'technician'])
            ->where('client_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'diagnosing'])
            ->latest()
            ->take(10)
            ->get();

        $notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.client', [
            'vehicles'      => $vehicles,
            'active_orders' => $active_orders,
            'notifications' => $notifications,
        ]);
    }
}
