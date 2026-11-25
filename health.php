<?php

header('Content-Type: application/json; charset=utf-8');

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_NAME');

if (!$host || !$user || !$database) {
    http_response_code(503);
    echo json_encode(['status' => 'unhealthy', 'database' => 'not_configured']);
    exit;
}

mysqli_report(MYSQLI_REPORT_OFF);

$conn = @new mysqli($host, $user, $password ?: '', $database);

if ($conn->connect_error) {
    http_response_code(503);
    echo json_encode(['status' => 'unhealthy', 'database' => 'disconnected']);
    exit;
}

$conn->close();

http_response_code(200);
echo json_encode(['status' => 'healthy', 'database' => 'connected']);
