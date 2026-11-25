<?php

if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}

$responderHealth = static function (int $statusCode, string $status, string $databaseStatus): void {
    if (!headers_sent()) {
        http_response_code($statusCode);
    }
    echo json_encode([
        'status' => $status,
        'database' => $databaseStatus,
        'service' => 'Sistema HBL - PNLD',
        'timestamp' => date('c'),
    ], JSON_UNESCAPED_SLASHES);
};

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');

if (!$host || !$user || !$database) {
    $responderHealth(503, 'unhealthy', 'not_configured');
    return;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password ?: '', $database);
    $conn->close();
    $responderHealth(200, 'healthy', 'connected');
} catch (\Throwable $e) {
    $responderHealth(503, 'unhealthy', 'disconnected');
}
