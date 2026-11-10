<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;

require_once __DIR__ . '/E2ETestCase.php';

class EdicaoLivroE2ETest extends E2ETestCase
{
    private ?int $codigoLivro = null;
    private ?string $isbnTeste = null;

    public function testDeveEditarLivroCadastrado(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Cria os dados iniciais do livro que será editado.
        $identificador = date('ymdHis') . random_int(100, 999);

        $this->isbnTeste = '979' . $identificador;

        $conn = new mysqli(
            'db',
            'pnld',
            'pnld_local',
            'SistemaHBL'
        );

        $stmt = $conn->prepare(
            "INSERT INTO livro
            (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $tituloOriginal = 'Livro E2E Original ' . $identificador;
        $autor = 'Autor Teste E2E';
        $editora = 1;
        $ano = 2026;
        $situacao = 'ativo';
        $edicao = 1;
        $quantidade = 3;

        $stmt->bind_param(
            'sssiisii',
            $this->isbnTeste,
            $tituloOriginal,
            $autor,
            $editora,
            $ano,
            $situacao,
            $edicao,
            $quantidade
        );

        $stmt->execute();

        $this->codigoLivro = $conn->insert_id;

        $stmt->close();
        $conn->close();

        // Acessa a página de edição do livro.
        $this->driver->get(
            $this->baseUrl . '/editar_livro.php?codigo=' . $this->codigoLivro
        );

        // Altera o título.
        $tituloAtualizado = 'Livro E2E Editado ' . $identificador;

        $campoTitulo = $this->driver->findElement(
            WebDriverBy::name('titulo')
        );

        $campoTitulo->clear();
        $campoTitulo->sendKeys($tituloAtualizado);

        // Salva as alterações.
        $this->driver->findElement(
            WebDriverBy::cssSelector('button[type="submit"]')
        )->click();

        // Verifica a mensagem de sucesso.
        $this->assertStringContainsString(
            'Livro atualizado com sucesso!',
            $this->driver->getPageSource(),
            'A aplicação deveria confirmar a edição do livro.'
        );

        // Verifica se o título atualizado permanece no formulário.
        $tituloSalvo = $this->driver->findElement(
            WebDriverBy::name('titulo')
        )->getAttribute('value');

        $this->assertSame(
            $tituloAtualizado,
            $tituloSalvo,
            'O título do livro deveria apresentar o valor atualizado.'
        );
    }

    protected function tearDown(): void
    {
        try {
            if ($this->codigoLivro !== null) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                $conn = new mysqli(
                    'db',
                    'pnld',
                    'pnld_local',
                    'SistemaHBL'
                );

                $stmt = $conn->prepare(
                    'DELETE FROM livro WHERE codigo = ?'
                );

                $stmt->bind_param('i', $this->codigoLivro);
                $stmt->execute();

                $stmt->close();
                $conn->close();
            }
        } finally {
            parent::tearDown();
        }
    }
}