<?php
if (!headers_sent()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(200);
}

echo json_encode([
    'status' => 'UP',
    'timestamp' => date('c'),
    'service' => 'Sistema HBL - PNLD',
    'environment' => getenv('APP_ENV') ?: 'production',
    'php_version' => PHP_VERSION
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
