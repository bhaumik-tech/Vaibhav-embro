<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    request()->merge(['party_id' => '1']); // Test with party_id
    $view = view('register', [
        'parties' => \App\Models\Party::all(),
        'firms' => \App\Models\Firm::all(),
        'inputChalans' => collect(),
        'outputChalans' => collect()
    ])->render();
    echo "Rendered successfully with party_id\n";
    
    request()->merge(['party_id' => null]); // Test without party_id
    $view = view('register', [
        'parties' => \App\Models\Party::all(),
        'firms' => \App\Models\Firm::all(),
        'inputChalans' => collect(),
        'outputChalans' => collect()
    ])->render();
    echo "Rendered successfully without party_id\n";
    
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
}
