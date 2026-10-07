<?php

function validarEmprestimo($matriculaAluno, $codigoLivro)
{
    return !empty($matriculaAluno) && !empty($codigoLivro);
}