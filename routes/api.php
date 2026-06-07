<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
});

// Temporary diagnostic route - REMOVE AFTER DEBUGGING
Route::get('/diag', function () {
    try {
        $result = [];
        
        // Check Settings
        try {
            $result['settings'] = [
                'workshop_name' => \App\Models\Setting::get('workshop_name', 'AutoScan'),
                'currency' => \App\Models\Setting::get('currency', 'USD'),
                'tax_percentage' => \App\Models\Setting::get('tax_percentage', 16),
            ];
        } catch (\Throwable $e) {
            $result['settings_error'] = get_class($e) . ': ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
        }
        
        // Check Ziggy
        try {
            $ziggy = \Tightenco\Ziggy\Ziggy::json()->toArray();
            $result['ziggy_ok'] = true;
            $result['ziggy_keys'] = array_keys($ziggy);
        } catch (\Throwable $e) {
            $result['ziggy_error'] = get_class($e) . ': ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
        }
        
        // Check Inertia
        try {
            $page = \Inertia\Inertia::render('Home')->toResponse(request());
            $result['inertia_ok'] = true;
        } catch (\Throwable $e) {
            $result['inertia_error'] = get_class($e) . ': ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
        }
        
        // Check env
        $result['app_key'] = config('app.key') ? 'SET' : 'MISSING';
        $result['app_env'] = config('app.env');
        $result['app_debug'] = config('app.debug');
        $result['cache_driver'] = config('cache.default');
        $result['session_driver'] = config('session.driver');
        $result['log_channel'] = config('logging.default');
        
        // Check last log entries
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $lines = explode("\n", $logContent);
                $result['last_log_lines'] = array_slice($lines, -30);
            } else {
                $result['log_file'] = 'NOT FOUND at ' . $logPath;
                // Try to list log directory
                $logDir = storage_path('logs');
                $result['log_dir'] = is_dir($logDir) ? scandir($logDir) : 'NOT A DIR';
            }
        } catch (\Throwable $e) {
            $result['log_read_error'] = $e->getMessage();
        }
        
        return response()->json($result);
    } catch (\Throwable $e) {
        return response()->json([
            'fatal' => get_class($e) . ': ' . $e->getMessage(),
            'file' => $e->getFile() . ':' . $e->getLine(),
            'trace' => collect($e->getTrace())->take(5)->map(fn ($t) => ($t['class'] ?? '') . ($t['type'] ?? '') . $t['function'] . ' at ' . ($t['file'] ?? 'n/a') . ':' . ($t['line'] ?? 0)),
        ], 500);
    }
});
