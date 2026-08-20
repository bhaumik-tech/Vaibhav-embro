<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bills = App\Models\GenerateBill::with('items', 'party', 'firm')->where('bill_no', '16')->get();
foreach($bills as $bill) {
    echo "Bill 16 found. ID: " . $bill->id . ", Party: " . $bill->party->name . ", Firm: " . $bill->firm->name . "\n";
    echo "Items ch_nos:\n";
    foreach ($bill->items as $item) {
        echo "- " . $item->ch_no . "\n";
    }
}
