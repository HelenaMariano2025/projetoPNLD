<?php 

session_start();

include 'php/conexao.php';

$id = $_GET['id'];
$data = date("Y-m-d");
$query_add = "INSERT INTO devolucao VALUES (NULL, '$id', '$data')";

mysqli_query($conn, $query_add);
header("Location: emprestimosDevolucoes.php");

?>