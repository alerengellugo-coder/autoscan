<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Middleware: CleanDatabaseConnection
 *
 * Disconnects the database connection after each response is sent.
 * This prevents Neon pgBouncer from reusing connections that have
 * stale aborted transaction state (SQLSTATE[25P02]).
 *
 * Must be registered in the global middleware stack.
 */
class CleanDatabaseConnection
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        try {
            DB::disconnect('pgsql');
        } catch (\Throwable $e) {
            // Ignore — connection may already be closed
        }
    }
}
