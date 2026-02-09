<?php
require_once '../app/core/Database.php';
require_once '../config/config.php';


$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

try {
    Database::connect();
    echo "DB CONNECTED";
} catch (Exception $e) {
    echo $e->getMessage();
}
