<?php

$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "pnld";
$password = getenv("DB_PASSWORD") ?: "123456";
$dbname = getenv("DB_NAME") ?: "SistemaHBL";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}