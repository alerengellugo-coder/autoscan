<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller: DashboardController
 *
 * Handles the root dashboard redirect.
 * Inspects the authenticated user's role and redirects them
 * to their role-specific dashboard route.
 */
class DashboardController extends Controller
{
    /**
     * Redirect the authenticated user to their role-specific dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        return redirect()->route(match ($user->role) {
            'admin'      => 'admin.dashboard',
            'technician' => 'technician.dashboard',
            'client'     => 'client.dashboard',
            default      => 'home',
        });
    }
}
