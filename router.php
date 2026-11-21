<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/health' || $uri === '/health.php') {
    require __DIR__ . '/health.php';
    exit;
}

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

if ($uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/index.html')) {
        require __DIR__ . '/index.html';
        exit;
    }
    if (file_exists(__DIR__ . '/index.php')) {
        require __DIR__ . '/index.php';
        exit;
    }
}

return false;
