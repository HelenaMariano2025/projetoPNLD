<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

$codigo = filter_input(INPUT_POST, 'codigo', FILTER_VALIDATE_INT);

if ($codigo === null || $codigo === false || $codigo <= 0) {
    http_response_code(400);
    exit('Código do livro inválido.');
}

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/LivroRepository.php';

try {
    $repository = new LivroRepository($conn);
    $excluiu = $repository->excluir($codigo);
} catch (mysqli_sql_exception $erro) {
    $excluiu = false;
} finally {
    $conn->close();
}

header('Location: livros.php?exclusao=' . ($excluiu ? 'sucesso' : 'erro'));
exit;