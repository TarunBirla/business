<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$regs = \App\Models\EventRegistration::with(['event', 'user'])->get();
foreach ($regs as $r) {
    echo "ID: {$r->id} | Event ID: {$r->event_id} | User: {$r->user->name} ({$r->user->email}) | Status: '{$r->registration_status}' | Payment: '{$r->payment_status}'\n";
}
