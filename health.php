<?php

if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');

if (!$host || !$user || !$database) {
    if (!headers_sent()) {
        http_response_code(503);
    }
    echo json_encode([
        'status' => 'unhealthy',
        'database' => 'not_configured',
        'service' => 'Sistema HBL - PNLD',
        'timestamp' => date('c'),
    ], JSON_UNESCAPED_SLASHES);
    return;
}

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli($host, $user, $password ?: '', $database);

if ($conn->connect_error) {
    if (!headers_sent()) {
        http_response_code(503);
    }
    echo json_encode([
        'status' => 'unhealthy',
        'database' => 'disconnected',
        'service' => 'Sistema HBL - PNLD',
        'timestamp' => date('c'),
    ], JSON_UNESCAPED_SLASHES);
    return;
}

$conn->close();

if (!headers_sent()) {
    http_response_code(200);
}
echo json_encode([
    'status' => 'healthy',
    'database' => 'connected',
    'service' => 'Sistema HBL - PNLD',
    'timestamp' => date('c'),
], JSON_UNESCAPED_SLASHES);
