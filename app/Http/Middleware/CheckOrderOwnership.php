<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ServiceOrder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: CheckOrderOwnership
 *
 * Ensures that a client user owns the service order being accessed.
 * Admin and technician roles bypass this check.
 * The service order ID must be provided via the 'service_order' route parameter.
 */
class CheckOrderOwnership
{
    /**
     * Handle an incoming request.
     *
     * Admins and technicians can access any service order.
     * Client users must own the service order identified by the route parameter.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admins and technicians can access any service order
        if ($user->isAdmin() || $user->isTechnician()) {
            return $next($request);
        }

        // Clients must own the service order
        $orderId = $request->route('service_order');

        if (! $orderId) {
            abort(404, 'Orden de servicio no encontrada en la ruta.');
        }

        $order = ServiceOrder::find($orderId);

        if (! $order || $order->client_id !== $user->id) {
            abort(403, 'No tiene permiso para acceder a esta orden de servicio.');
        }

        // Share the order instance on the request for optional reuse
        $request->merge(['order_instance' => $order]);

        return $next($request);
    }
}
