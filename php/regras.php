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