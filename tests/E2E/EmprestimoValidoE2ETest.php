<?php

use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/EmprestimoE2ETestCase.php';

class EmprestimoValidoE2ETest extends EmprestimoE2ETestCase
{
    public function testDeveRegistrarEmprestimoComDadosValidos(): void
    {
        $this->criarDadosDeTeste(true, 2);

        $this->loginAdministrador();
        $this->abrirFormularioEmprestimo();

        $this->driver->findElement(
            WebDriverBy::name('codLivro')
        )->sendKeys($this->isbn);

        $campoData = $this->driver->findElement(
            WebDriverBy::name('dataEmprestimo')
        );

        $this->driver->executeScript(
            "arguments[0].value = arguments[1];
             arguments[0].dispatchEvent(new Event('input', { bubbles: true }));
             arguments[0].dispatchEvent(new Event('change', { bubbles: true }));",
            [$campoData, date('Y-m-d')]
        );

        $this->driver->findElement(
            WebDriverBy::name('matricula')
        )->sendKeys((string) $this->matriculaAluno);

        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();

        $pagina = $this->driver->getPageSource();

        $this->assertStringContainsString(
            'Livro emprestado com sucesso!',
            $pagina
        );

        $this->assertSame(
            1,
            $this->consultarQuantidadeDisponivel(),
            'A quantidade disponível deveria diminuir de 2 para 1.'
        );

        $this->assertSame(
            1,
            $this->contarEmprestimos(),
            'Deveria existir um empréstimo registrado para o livro.'
        );
    }
}