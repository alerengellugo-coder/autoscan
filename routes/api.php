<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
});

Route::get('/diag', function () {
    try {
        $result = [];
        
        // Read log file - look for errors
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $lines = explode("\n", $logContent);
                
                // Find the LAST error entry
                $errorStart = -1;
                for ($i = count($lines) - 1; $i >= 0; $i--) {
                    if (strpos($lines[$i], '[') === 0 && strpos($lines[$i], 'local.ERROR') !== false) {
                        $errorStart = $i;
                        break;
                    }
                }
                
                if ($errorStart >= 0) {
                    // Get 30 lines from the error
                    $errorLines = array_slice($lines, $errorStart, min(40, count($lines) - $errorStart));
                    $result['error_log'] = $errorLines;
                } else {
                    $result['log_note'] = 'No error entries found in log';
                    $result['last_lines'] = array_slice($lines, -20);
                }
            } else {
                $result['log_path'] = $logPath;
                $result['log_exists'] = false;
                $result['log_dir'] = is_dir(dirname($logPath)) ? scandir(dirname($logPath)) : [];
            }
        } catch (\Throwable $e) {
            $result['log_error'] = $e->getMessage();
        }
        
        return response()->json($result);
    } catch (\Throwable $e) {
        return response()->json(['fatal' => $e->getMessage()], 500);
    }
});
