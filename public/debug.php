<?php
header('Content-Type: text/plain');
echo "=== PHP Debug Info ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Loaded Extensions: " . implode(', ', get_loaded_extensions()) . "\n\n";

echo "=== Environment Variables ===\n";
$envVars = ['APP_KEY', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_CONNECTION'];
foreach ($envVars as $var) {
    $val = getenv($var);
    if ($val === false) $val = 'NOT SET';
    if ($var === 'APP_KEY' && strlen($val) > 10) $val = substr($val, 0, 10) . '...';
    echo "$var = $val\n";
}

echo "\n=== Directory Permissions ===\n";
$dirs = ['/var/www/html/storage', '/var/www/html/storage/logs', '/var/www/html/storage/framework', '/var/www/html/bootstrap/cache'];
foreach ($dirs as $dir) {
    $exists = is_dir($dir) ? 'EXISTS' : 'MISSING';
    $writable = is_writable($dir) ? 'WRITABLE' : 'NOT WRITABLE';
    echo "$dir: $exists, $writable\n";
}

echo "\n=== Laravel Bootstrap ===\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Autoload: OK\n";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "App Bootstrap: OK\n";
    echo "App Class: " . get_class($app) . "\n";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel: OK\n";
} catch (Throwable $e) {
    echo "ERROR: " . get_class($e) . ": " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
