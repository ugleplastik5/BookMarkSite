<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $app->make('encrypter');
    fwrite(STDOUT, "Runtime encryption key: valid\n");
} catch (Throwable $e) {
    fwrite(STDERR, "APP_KEY is not valid for the configured cipher. Configure a valid Laravel key; the deployment will not replace it automatically.\n");
    exit(1);
}

try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    fwrite(STDOUT, "Runtime database connection: OK\n");
} catch (Throwable $e) {
    // SQLSTATE identifies connection failure without printing passwords or URLs.
    fwrite(STDERR, 'Runtime database connection failed (code '.$e->getCode()."). Check DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME and DB_PASSWORD.\n");
    exit(1);
}
