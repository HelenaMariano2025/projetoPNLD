<?php

use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/E2ETestCase.php';

class ExclusaoLivroE2ETest extends E2ETestCase
{
    private ?int $codigoLivro = null;

    public function testDeveExcluirLivroDaListagem(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // 1. Cria um livro exclusivo para o teste.
        $identificador = date('ymdHis') . random_int(100, 999);
        $isbn = '979' . $identificador;
        $titulo = 'Livro E2E Exclusao ' . $identificador;
        $autor = 'Autor Teste E2E';
        $editora = 1;
        $ano = 2026;
        $situacao = 'ativo';
        $edicao = 1;
        $quantidade = 2;

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

        $stmt->bind_param(
            'sssiisii',
            $isbn,
            $titulo,
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

        // 2. Realiza o login.
        $this->driver->get($this->baseUrl . '/login.php');

        $this->driver->findElement(
            WebDriverBy::name('matricula')
        )->sendKeys('1234567890');

        $campoSenha = $this->driver->findElement(
            WebDriverBy::name('senha')
        );

        $campoSenha->sendKeys('1234567890');
        $campoSenha->submit();

        // 3. Pesquisa o livro na listagem.
        $this->driver->get(
            $this->baseUrl . '/livros.php?titulo=' . urlencode($titulo)
        );

        $this->assertStringContainsString(
            $titulo,
            $this->driver->getPageSource(),
            'O livro deveria aparecer na listagem antes da exclusão.'
        );

        // 4. Executa a exclusão pela interface.
        $this->driver->findElement(
            WebDriverBy::cssSelector(
                'form[action="excluir_livro.php"] button[type="submit"]'
            )
        )->click();

        // 5. Verifica e aceita a confirmação de exclusão.
        $alerta = $this->driver->switchTo()->alert();

        $this->assertSame(
            'Deseja excluir este livro da listagem?',
            $alerta->getText(),
            'A aplicação deveria solicitar confirmação antes da exclusão.'
        );

        $alerta->accept();

        // 6. Verifica o redirecionamento após a exclusão.
        $this->assertStringContainsString(
            'exclusao=sucesso',
            $this->driver->getCurrentURL(),
            'A aplicação deveria redirecionar após a exclusão.'
        );

        $this->assertStringContainsString(
            'Livro excluído da listagem com sucesso.',
            $this->driver->getPageSource(),
            'A aplicação deveria informar que o livro foi excluído.'
        );

        // 7. Confirma que o livro foi marcado como inativo no banco.
        $conn = new mysqli(
            'db',
            'pnld',
            'pnld_local',
            'SistemaHBL'
        );

        $stmt = $conn->prepare(
            'SELECT situacao FROM livro WHERE codigo = ?'
        );

        $stmt->bind_param('i', $this->codigoLivro);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();

        $this->assertNotNull(
            $resultado,
            'O registro deveria permanecer no banco após a exclusão lógica.'
        );

        $this->assertSame(
            'inativo',
            $resultado['situacao'],
            'O livro deveria ser marcado como inativo.'
        );

        $stmt->close();
        $conn->close();
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