<?php
$c = file_get_contents('resources/views/register.blade.php');
echo 'if: ' . substr_count($c, '@if') . ' endif: ' . substr_count($c, '@endif') . PHP_EOL;
echo 'foreach: ' . substr_count($c, '@foreach') . ' endforeach: ' . substr_count($c, '@endforeach') . PHP_EOL;
echo 'forelse: ' . substr_count($c, '@forelse') . ' endforelse: ' . substr_count($c, '@endforelse') . PHP_EOL;
echo 'canpage: ' . substr_count($c, '@canpage') . ' endcanpage: ' . substr_count($c, '@endcanpage') . PHP_EOL;
echo 'php: ' . substr_count($c, '@php') . ' endphp: ' . substr_count($c, '@endphp') . PHP_EOL;
