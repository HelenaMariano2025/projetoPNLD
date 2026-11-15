<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;

require_once __DIR__ . '/E2ETestCase.php';

class CadastroLivroE2ETest extends E2ETestCase
{
    private ?string $isbnTeste = null;

    public function testDeveCadastrarLivroComDadosValidos(): void
    {
        // Gera dados exclusivos para este teste.
        $identificador = date('ymdHis') . random_int(100, 999);

        $this->isbnTeste = '979' . $identificador;

        $titulo = 'Livro E2E TA05.01 ' . $identificador;

        // 1. Acessa a página de cadastro.
        $this->driver->get($this->baseUrl . '/add_livro.php');

        // 2. Preenche os campos do formulário.
        $this->driver->findElement(
            WebDriverBy::name('isbn')
        )->sendKeys($this->isbnTeste);

        $this->driver->findElement(
            WebDriverBy::name('titulo')
        )->sendKeys($titulo);

        $this->driver->findElement(
            WebDriverBy::name('autor')
        )->sendKeys('Autor Teste E2E');

        $this->driver->findElement(
            WebDriverBy::name('codigo_editora')
        )->sendKeys('1');

        $this->driver->findElement(
            WebDriverBy::name('ano')
        )->sendKeys('2026');

        $situacao = new WebDriverSelect(
            $this->driver->findElement(
                WebDriverBy::name('situacao')
            )
        );

        $situacao->selectByValue('ativo');

        $this->driver->findElement(
            WebDriverBy::name('edicao')
        )->sendKeys('1');

        $this->driver->findElement(
            WebDriverBy::name('qtde_disponivel')
        )->sendKeys('3');

        // 3. Envia o formulário.
        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();

        // 4. Verifica a mensagem de sucesso.
        $this->assertStringContainsString(
            'Novo livro adicionado com sucesso!',
            $this->driver->getPageSource(),
            'A aplicação deveria confirmar o cadastro do livro.'
        );

        // 5. Acessa a página de login.
        $this->driver->get($this->baseUrl . '/login.php');

        $this->driver->findElement(
            WebDriverBy::name('matricula')
        )->sendKeys('1234567890');

        $campoSenha = $this->driver->findElement(
            WebDriverBy::name('senha')
        );

        $campoSenha->sendKeys('1234567890');

        $campoSenha->submit();

        // 6. Pesquisa o livro cadastrado.
        $urlPesquisa = $this->baseUrl
            . '/livros.php?titulo='
            . urlencode($titulo);

        $this->driver->get($urlPesquisa);

        // 7. Confirma que o livro aparece na listagem.
        $this->assertStringContainsString(
            $titulo,
            $this->driver->getPageSource(),
            'O livro cadastrado deveria aparecer na listagem.'
        );
    }

    protected function tearDown(): void
    {
        try {
            // Remove o livro criado pelo teste.
            if ($this->isbnTeste !== null) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                $conn = new mysqli(
                    'db',
                    'pnld',
                    'pnld_local',
                    'SistemaHBL'
                );

                $stmt = $conn->prepare(
                    'DELETE FROM livro WHERE isbn = ?'
                );

                $stmt->bind_param('s', $this->isbnTeste);
                $stmt->execute();

                $stmt->close();
                $conn->close();
            }
        } finally {
            parent::tearDown();
        }
    }
}