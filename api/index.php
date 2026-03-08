<?php

require __DIR__ . '/../vendor/autoload.php';

foreach (['/tmp/storage/app','/tmp/storage/framework/cache','/tmp/storage/framework/sessions','/tmp/storage/framework/views','/tmp/storage/logs','/tmp/views'] as $d) {
    if (!is_dir($d)) mkdir($d, 0777, true);
}

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());
$response->send();
$kernel->terminate($request, $response);
