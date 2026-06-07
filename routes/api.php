<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
});

// Temporary diagnostic route - REMOVE AFTER DEBUGGING
Route::get('/diag', function () {
    try {
        $result = [];
        
        // Check view compiled path
        $result['view_config'] = config('view');
        $result['view_compiled'] = config('view.compiled');
        $result['storage_path'] = storage_path('framework/views');
        $result['storage_path_exists'] = is_dir(storage_path('framework/views'));
        $result['storage_path_writable'] = is_writable(storage_path('framework/views'));
        $result['path_storage'] = app('path.storage');
        
        // Check Settings
        try {
            $result['settings'] = [
                'workshop_name' => \App\Models\Setting::get('workshop_name', 'AutoScan'),
                'currency' => \App\Models\Setting::get('currency', 'USD'),
                'tax_percentage' => \App\Models\Setting::get('tax_percentage', 16),
            ];
        } catch (\Throwable $e) {
            $result['settings_error'] = get_class($e) . ': ' . $e->getMessage();
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
        
        return response()->json($result);
    } catch (\Throwable $e) {
        return response()->json([
            'fatal' => get_class($e) . ': ' . $e->getMessage(),
            'file' => $e->getFile() . ':' . $e->getLine(),
        ], 500);
    }
});
