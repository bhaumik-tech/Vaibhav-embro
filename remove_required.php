<?php
$files = [
    __DIR__ . '/resources/views/make-program-create.blade.php',
    __DIR__ . '/resources/views/make-program-edit.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace(' required>', '>', $content);
        $content = str_replace(' required ', ' ', $content);
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}
echo "Done.\n";
