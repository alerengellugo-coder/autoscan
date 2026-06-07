<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
        
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'technician' => \App\Http\Middleware\EnsureUserIsTechnician::class,
            'client' => \App\Http\Middleware\EnsureUserIsClient::class,
            'vehicle.owner' => \App\Http\Middleware\CheckVehicleOwnership::class,
            'order.owner' => \App\Http\Middleware\CheckOrderOwnership::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // TEMPORARY: Log all exceptions to a file we can read via API
        $exceptions->report(function (\Throwable $e) {
            try {
                $errorLog = storage_path('logs/error-debug.log');
                $data = date('Y-m-d H:i:s') . ' | ' . get_class($e) . ' | ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine() . "\n";
                $data .= $e->getTraceAsString() . "\n\n";
                @file_put_contents($errorLog, $data, FILE_APPEND | LOCK_EX);
            } catch (\Throwable $t) {
                // Can't log
            }
        });
    })->create();
