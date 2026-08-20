<?php
$mapping = [
    'users' => 'users', 'firms' => 'firms', 'parties' => 'parties', 'machines' => 'machines',
    'karigars' => 'karigars', 'dh-cutting-people' => 'dh_cutting', 'input-chalan' => 'input_chalan',
    'generate-chalans' => 'generate_chalan', 'output-chalans' => 'output_chalan',
    'generate-bills' => 'generate_bill', 'purchase-bill' => 'purchase_bill',
    'purchase-bills' => 'purchase_bill', 'bank-book' => 'bank_book',
    'dhaga-cuttings' => 'dh_cutting', 'inter-exchange' => 'inter_exchange',
    'thread-boxes' => 'thread_boxes'
];
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($files as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $orig = $content;
        
        foreach ($mapping as $route => $key) {
            // + Add Button (create)
            $content = preg_replace('/(<a[^>]*href="{{[^}]*route\(\'' . $route . '\.create\'[^}]*}}[^>]*>.*?<\/a>)/s', "@canpage('$key', 'edit')\n$1\n@endcanpage", $content);
            
            // Edit Button (edit)
            $content = preg_replace('/(<a[^>]*href="{{[^}]*route\(\'' . $route . '\.edit\'[^}]*}}[^>]*>.*?<\/a>)/s', "@canpage('$key', 'edit')\n$1\n@endcanpage", $content);
            
            // Delete Form (destroy)
            $content = preg_replace('/(<form[^>]*action="{{[^}]*route\(\'' . $route . '\.destroy\'[^}]*}}[^>]*>.*?<\/form>)/s', "@canpage('$key', 'remove')\n$1\n@endcanpage", $content);
        }
        
        // Clean up doubles
        $content = preg_replace('/@canpage\(\'([^\']+)\', \'([^\']+)\'\)\n*@canpage\(\'([^\']+)\', \'([^\']+)\'\)/', "@canpage('$1', '$2')", $content);
        $content = preg_replace('/@endcanpage\n*@endcanpage/', "@endcanpage", $content);
        
        if ($content !== $orig) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
