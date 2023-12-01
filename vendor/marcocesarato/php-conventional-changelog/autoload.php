<?php

// Autoload
$files = [
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/vendor/autoload.php',
];
foreach ($files as $file) {
    if (file_exists($file)) {
        require_once $file;
        break;
    }
}
