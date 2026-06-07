<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Simulating Laravel Web Request ===\n\n";

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/login', 'GET', [], [], [], [
    'HTTP_ACCEPT' => 'text/html',
]);

echo "Request URL: /login\n";
echo "Request Method: GET\n\n";

try {
    $response = $kernel->handle($request);
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Content-Type: " . $response->headers->get('Content-Type') . "\n";
    $content = $response->getContent();
    echo "Response Content (first 500 chars):\n";
    echo substr($content, 0, 500) . "\n";
    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    echo "EXCEPTION: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
