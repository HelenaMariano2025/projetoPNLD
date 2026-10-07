<?php

session_start();

include 'php/conexao.php';
require_once 'php/EmprestimoRepository.php';

$id = (int) ($_GET['id'] ?? 0);
$data = date('Y-m-d');

if ($id > 0) {
    $repository = new EmprestimoRepository($conn);

    $sucesso = $repository->registrarDevolucao(
        $id,
        $data
    );

    if ($sucesso) {
        $_SESSION['mensagem'] = 'Livro devolvido com sucesso!';
    } else {
        $_SESSION['mensagem'] = 'Não foi possível registrar a devolução. Verifique se o empréstimo já foi devolvido.';
    }
}

$conn->close();

header('Location: emprestimosDevolucoes.php');
exit;
