<?php

use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/EmprestimoE2ETestCase.php';

class EmprestimoLivroIndisponivelE2ETest extends EmprestimoE2ETestCase
{
    public function testDeveBloquearEmprestimoDeLivroSemExemplaresDisponiveis(): void
    {
        $this->criarDadosDeTeste(true, 0);

        $this->loginAdministrador();
        $this->abrirFormularioEmprestimo();

        $this->driver->findElement(
            WebDriverBy::name('codLivro')
        )->sendKeys($this->isbn);

        $this->driver->findElement(
            WebDriverBy::name('dataEmprestimo')
        )->sendKeys(date('Y-m-d'));

        $this->driver->findElement(
            WebDriverBy::name('matricula')
        )->sendKeys((string) $this->matriculaAluno);

        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();

        $pagina = $this->driver->getPageSource();

        $this->assertStringContainsString(
            'Não há exemplares disponíveis para empréstimo.',
            $pagina
        );

        $this->assertSame(
            0,
            $this->consultarQuantidadeDisponivel(),
            'A quantidade disponível deveria continuar zerada.'
        );

        $this->assertSame(
            0,
            $this->contarEmprestimos(),
            'Nenhum empréstimo deveria ser registrado.'
        );
    }
}