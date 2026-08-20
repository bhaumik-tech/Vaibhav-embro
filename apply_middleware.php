<?php
$controllers = [
    'UserController' => 'users',
    'FirmController' => 'firms',
    'PartyController' => 'parties',
    'MachineController' => 'machines',
    'KarigarController' => 'karigars',
    'InputChalanController' => 'input_chalan',
    'GenerateChalanController' => 'generate_chalan',
    'OutputChalanController' => 'output_chalan',
    'GenerateBillController' => 'generate_bill',
    'PurchaseBillController' => 'purchase_bill',
    'BankBookController' => 'bank_book',
    'DhagaCuttingController' => 'dh_cutting',
    'InterExchangeController' => 'inter_exchange',
    'ThreadBoxSetupController' => 'thread_boxes',
    'InterExchangeSetupController' => 'inter_exchange',
    'DhCuttingPersonController' => 'dh_cutting',
];

foreach ($controllers as $controller => $key) {
    $path = __DIR__ . "/app/Http/Controllers/{$controller}.php";
    if (!file_exists($path)) {
        continue;
    }

    $content = file_get_contents($path);

    // Skip if already implemented
    if (strpos($content, 'HasMiddleware') !== false) {
        continue;
    }

    // Add use statement for HasMiddleware and Middleware
    if (strpos($content, 'use Illuminate\Routing\Controllers\HasMiddleware;') === false) {
        $content = str_replace(
            "use Illuminate\Http\Request;",
            "use Illuminate\Http\Request;\nuse Illuminate\Routing\Controllers\HasMiddleware;\nuse Illuminate\Routing\Controllers\Middleware;",
            $content
        );
    }

    // Add implements HasMiddleware
    $content = preg_replace(
        '/class ' . $controller . ' extends Controller( implements [a-zA-Z0-9_]+)?\s*\{/',
        "class {$controller} extends Controller implements HasMiddleware\n{",
        $content
    );

    // Add middleware method
    $middlewareMethod = <<<PHP

    public static function middleware(): array
    {
        return [
            new Middleware('page.permission:{$key},edit', only: ['create', 'store', 'edit', 'update', 'quickStore']),
            new Middleware('page.permission:{$key},remove', only: ['destroy']),
        ];
    }

PHP;

    $content = preg_replace('/\{/', "{" . $middlewareMethod, $content, 1);
    
    file_put_contents($path, $content);
    echo "Updated {$controller}\n";
}
