<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/regras.php';

class EmprestimoSemIdLivroTest extends TestCase
{
    public function testEmprestimoSemIdLivroDeveSerInvalido(): void
    {
        $idLivro = '';

        $resultado = validarIdLivro($idLivro);

        $this->assertFalse(
            $resultado,
            'O empréstimo não deve ser permitido sem o ID do livro.'
        );
    }
}