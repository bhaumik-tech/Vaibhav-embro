<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('programs');
foreach($columns as $col) {
    $notnull = \Illuminate\Support\Facades\Schema::getConnection()->getDoctrineColumn('programs', $col)->getNotnull();
    echo $col . ' : ' . ($notnull ? 'NOT NULL' : 'NULL') . PHP_EOL;
}
