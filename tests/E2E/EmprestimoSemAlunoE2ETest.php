<?php

use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/EmprestimoE2ETestCase.php';

class EmprestimoSemAlunoE2ETest extends EmprestimoE2ETestCase
{
    public function testDeveBloquearEmprestimoSemMatriculaDeAluno(): void
    {
        $this->criarDadosDeTeste(false, 2);

        $this->loginAdministrador();
        $this->abrirFormularioEmprestimo();

        $this->driver->findElement(
            WebDriverBy::name('codLivro')
        )->sendKeys($this->isbn);

        $this->driver->findElement(
            WebDriverBy::name('dataEmprestimo')
        )->sendKeys(date('Y-m-d'));

        $campoMatricula = $this->driver->findElement(
            WebDriverBy::name('matricula')
        );

        $this->driver->executeScript(
            "arguments[0].removeAttribute('required');",
            [$campoMatricula]
        );

        $campoMatricula->clear();

        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();

        $pagina = $this->driver->getPageSource();

        $this->assertStringContainsString(
            'Preencha corretamente o livro e a matrícula do aluno.',
            $pagina
        );

        $this->assertSame(
            2,
            $this->consultarQuantidadeDisponivel(),
            'A quantidade disponível não deveria ser alterada.'
        );

        $this->assertSame(
            0,
            $this->contarEmprestimos(),
            'Nenhum empréstimo deveria ser registrado.'
        );
    }
}