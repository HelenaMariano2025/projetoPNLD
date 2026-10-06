<?php

function validarCampoObrigatorio($valor)
{
    return !empty(trim($valor));
}

function podeEmprestar($quantidadeDisponivel)
{
    return $quantidadeDisponivel > 0;
}

function calcularDataDevolucao($dataEmprestimo)
{
    return date('Y-m-d', strtotime($dataEmprestimo . ' +20 days'));
}

function autenticarAdministrador($matricula, $senha, $repository)
{
    if (!validarCampoObrigatorio($matricula) || !validarCampoObrigatorio($senha)) {
        return null;
    }

    $administrador = $repository->buscarPorMatricula((int) $matricula);

    if ($administrador === null) {
        return null;
    }

    if ($senha !== $administrador['senha']) {
        return null;
    }

    return $administrador;
}