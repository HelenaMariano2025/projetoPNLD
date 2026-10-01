<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/regras_emprestimo.php';

class EmprestimoSemMatriculaTest extends TestCase
{
    public function testNaoPermiteEmprestimoSemMatricula()
    {
        $matriculaAluno = "";
        $codigoLivro = 1;

        $resultado = validarEmprestimo($matriculaAluno, $codigoLivro);

        $this->assertFalse($resultado);
    }
}