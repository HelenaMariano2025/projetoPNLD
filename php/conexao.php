<?php
$servername = "localhost";
$username = "pnld";
$password = "123456";
$dbname = "SistemaHBL";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}


