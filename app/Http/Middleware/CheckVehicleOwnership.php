<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Vehicle;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: CheckVehicleOwnership
 *
 * Ensures that a client user owns the vehicle being accessed.
 * Admin and technician roles bypass this check.
 * The vehicle ID must be provided via the 'vehicle' route parameter.
 */
class CheckVehicleOwnership
{
    /**
     * Handle an incoming request.
     *
     * Admins and technicians bypass the ownership check entirely.
     * Client users must own the vehicle identified by the route parameter.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admins and technicians can access any vehicle
        if ($user->isAdmin() || $user->isTechnician()) {
            return $next($request);
        }

        // Clients must own the vehicle
        $vehicleId = $request->route('vehicle');

        if (! $vehicleId) {
            abort(404, 'Vehículo no encontrado en la ruta.');
        }

        $vehicle = Vehicle::find($vehicleId);

        if (! $vehicle || $vehicle->client_id !== $user->id) {
            abort(403, 'No tiene permiso para acceder a este vehículo.');
        }

        // Share the vehicle instance on the request for optional reuse
        $request->merge(['vehicle_instance' => $vehicle]);

        return $next($request);
    }
}
