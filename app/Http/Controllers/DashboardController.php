<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller: DashboardController
 *
 * The root /dashboard route redirects to the role-specific dashboard.
 * The role-specific routes render the actual Inertia page.
 */
class DashboardController extends Controller
{
    /**
     * Root dashboard entry: redirect to role-specific dashboard.
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
     * Admin dashboard page.
     */
    public function adminDashboard(): Response
    {
        return Inertia::render('Dashboard/AdminDashboard');
    }

    /**
     * Technician dashboard page.
     */
    public function technicianDashboard(): Response
    {
        return Inertia::render('Dashboard/TechnicianDashboard');
    }

    /**
     * Client dashboard page.
     */
    public function clientDashboard(): Response
    {
        return Inertia::render('Dashboard/ClientDashboard');
    }
}
