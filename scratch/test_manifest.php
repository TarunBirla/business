<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Manifest Route: " . route('bizcard.manifest', 2) . "\n";
$controller = new \App\Http\Controllers\PublicBusinessCardController();
$response = $controller->manifest(2);
echo "Manifest JSON content:\n" . $response->getContent() . "\n";
