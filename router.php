<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path === '/health') {
    require __DIR__ . '/health.php';
    return true;
}

return false;
