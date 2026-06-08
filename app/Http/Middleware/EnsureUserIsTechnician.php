<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: EnsureUserIsTechnician
 *
 * Restricts access to authenticated users with the 'technician' role.
 * Non-technician users are redirected to their respective dashboard.
 */
class EnsureUserIsTechnician
{
    /**
     * Handle an incoming request.
     *
     * Checks if the authenticated user has the 'technician' role.
     * If not, redirects to the dashboard with an error flash message.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! Auth::user()->isTechnician()) {
            // Determine the correct dashboard redirect based on the user's role
            $redirectRoute = match (Auth::user()->role) {
                'admin' => 'admin.dashboard',
                'client' => 'client.dashboard',
                default => 'dashboard',
            };

            return redirect()
                ->route($redirectRoute)
                ->with('error', 'No tiene permisos de técnico para acceder a esta sección.');
        }

        return $next($request);
    }
}
