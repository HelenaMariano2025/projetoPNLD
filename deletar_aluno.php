<?php

include 'php/conexao.php';

if (isset($_GET['matricula'])) {

    $matricula = $_GET['matricula'];

    $sql = "UPDATE aluno
            SET situacao = 'inativo'
            WHERE matricula = '$matricula'";

    if ($conn->query($sql) === TRUE) {

        $conn->close();

        header("Location: aluno.php?deletado=1");
        exit;

    } else {

        echo "Erro ao inativar aluno: " . $conn->error;
    }

} else {

    echo "Matrícula não informada.";
}

?>