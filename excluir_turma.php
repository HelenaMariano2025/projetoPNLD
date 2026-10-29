<?php
session_start();

require_once 'php/auth.php';
exigirAutenticacao();

require_once 'php/conexao.php';
require_once 'php/TurmaRepository.php';

if (!isset($_GET['codigo']) || !ctype_digit((string) $_GET['codigo'])) {
    $_SESSION['mensagem'] = 'Código de turma inválido.';
    header('Location: turmas.php');
    exit();
}

$codigo = (int) $_GET['codigo'];
$repository = new TurmaRepository($conn);

try {
    if ($repository->temAlunosVinculados($codigo)) {
        $_SESSION['mensagem'] =
            'Não é possível excluir esta turma porque existem alunos vinculados.';
    } else {
        $resultado = $repository->excluir($codigo);

        if ($resultado) {
            $_SESSION['mensagem'] = 'Turma excluída com sucesso.';
        } else {
            $_SESSION['mensagem'] =
                'Não foi possível excluir a turma.';
        }
    }
} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    $_SESSION['mensagem'] =
        'Ocorreu um erro ao excluir a turma. Verifique se existem registros vinculados.';
}

$conn->close();

header('Location: turmas.php');
exit();