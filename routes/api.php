<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
});

Route::get('/diag', function () {
    try {
        $result = [];
        
        // Read custom error debug log
        try {
            $debugLog = storage_path('logs/error-debug.log');
            if (file_exists($debugLog)) {
                $content = file_get_contents($debugLog);
                $result['error_debug_log'] = $content;
            } else {
                $result['error_debug_log'] = 'FILE NOT FOUND';
            }
        } catch (\Throwable $e) {
            $result['error_debug_log_error'] = $e->getMessage();
        }
        
        // Also read main log
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $content = file_get_contents($logPath);
                // Find last error
                $lines = explode("\n", $content);
                $lastError = -1;
                for ($i = count($lines) - 1; $i >= 0; $i--) {
                    if (strpos($lines[$i], '.ERROR') !== false) {
                        $lastError = $i;
                        break;
                    }
                }
                if ($lastError >= 0) {
                    $result['laravel_log_error'] = implode("\n", array_slice($lines, $lastError, 20));
                } else {
                    $result['laravel_log_note'] = 'No ERROR entries';
                    $result['laravel_log_size'] = strlen($content);
                }
            }
        } catch (\Throwable $e) {
            $result['laravel_log_error'] = $e->getMessage();
        }
        
        return response()->json($result);
    } catch (\Throwable $e) {
        return response()->json(['fatal' => $e->getMessage()], 500);
    }
});
