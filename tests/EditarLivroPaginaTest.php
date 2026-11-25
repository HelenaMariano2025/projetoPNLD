<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/http.php';

class EditarLivroPaginaTest extends TestCase
{
    private mysqli $conn;
    private int $codigoLivro;
    private int $isbn;
    private array $livrosCriados = [];

    protected function setUp(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = $this->novaConexao();
        $this->isbn = random_int(7000000000000, 7499999999999);
        $this->codigoLivro = $this->inserirLivro($this->isbn);
    }

    protected function tearDown(): void
    {
        foreach ($this->livrosCriados as $codigo) {
            $stmt = $this->conn->prepare('DELETE FROM livro WHERE codigo = ?');
            $stmt->bind_param('i', $codigo);
            $stmt->execute();
            $stmt->close();
        }

        $this->conn->close();

        $_GET = [];
        $_POST = [];
        $_SESSION = [];
    }

    private function novaConexao(): mysqli
    {
        return new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );
    }

    private function inserirLivro(int $isbn): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO livro
             (isbn, titulo, autor, codigo_editora, ano, situacao,
              edicao, qtde_disponivel)
             VALUES (?, 'Livro Pagina Teste', 'Autor Teste', 1, 2026,
                     'ativo', 1, 5)"
        );
        $stmt->bind_param('i', $isbn);
        $stmt->execute();

        $codigo = (int) $this->conn->insert_id;
        $stmt->close();

        $this->livrosCriados[] = $codigo;

        return $codigo;
    }

    private function executarPagina(
        string $metodo,
        array $get = [],
        array $post = [],
        array $sessao = ['matricula' => 1234567890]
    ): array {
        $_SERVER['REQUEST_METHOD'] = $metodo;
        $_GET = $get;
        $_POST = $post;
        $_SESSION = $sessao;

        // A página fecha a conexão, então cada execução recebe uma nova.
        $conn = $this->novaConexao();

        $destino = null;
        $statusRedirecionamento = null;
        $encerramento = null;

        ob_start();

        try {
            include __DIR__ . '/../editar_livro.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
            $statusRedirecionamento = $e->status;
        } catch (EncerramentoException $e) {
            $encerramento = [
                'status' => $e->status,
                'mensagem' => $e->mensagem,
            ];
        } finally {
            $saida = ob_get_clean();
        }

        return [
            'saida' => $saida,
            'destino' => $destino,
            'statusRedirecionamento' => $statusRedirecionamento,
            'encerramento' => $encerramento,
        ];
    }

    private function postValido(): array
    {
        return [
            'codigo' => (string) $this->codigoLivro,
            'isbn' => (string) $this->isbn,
            'titulo' => 'Livro Atualizado',
            'autor' => 'Autor Novo',
            'codigo_editora' => '1',
            'ano' => '2025',
            'situacao' => 'inativo',
            'edicao' => '2',
            'qtde_disponivel' => '9',
        ];
    }

    private function buscarLivro(): array
    {
        $codigo = $this->codigoLivro;

        $stmt = $this->conn->prepare('SELECT * FROM livro WHERE codigo = ?');
        $stmt->bind_param('i', $codigo);
        $stmt->execute();

        $livro = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $livro;
    }

    public function testRedirecionaParaLoginSemAutenticacao(): void
    {
        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoLivro],
            [],
            []
        );

        $this->assertSame('login.php', $resultado['destino']);
    }

    public function testRejeitaMetodoNaoPermitido(): void
    {
        $resultado = $this->executarPagina('PUT');

        $this->assertSame(
            ['status' => 405, 'mensagem' => 'Método não permitido.'],
            $resultado['encerramento']
        );
    }

    #[DataProvider('codigosInvalidos')]
    public function testRejeitaCodigoInvalido(array $get): void
    {
        $resultado = $this->executarPagina('GET', $get);

        $this->assertSame(
            ['status' => 400, 'mensagem' => 'Código do livro inválido.'],
            $resultado['encerramento']
        );
    }

    public static function codigosInvalidos(): array
    {
        return [
            'sem codigo' => [[]],
            'texto' => [['codigo' => 'abc']],
            'zero' => [['codigo' => '0']],
            'negativo' => [['codigo' => '-5']],
        ];
    }

    public function testInformaQuandoOLivroNaoExiste(): void
    {
        $resultado = $this->executarPagina('GET', ['codigo' => '2000000000']);

        $this->assertSame(
            ['status' => 404, 'mensagem' => 'Livro não encontrado.'],
            $resultado['encerramento']
        );
    }

    public function testExibeFormularioComOsDadosDoLivro(): void
    {
        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoLivro]
        );

        $this->assertNull($resultado['encerramento']);
        $this->assertNull($resultado['destino']);
        $this->assertStringContainsString(
            'name="codigo" value="' . $this->codigoLivro . '"',
            $resultado['saida']
        );
        $this->assertStringContainsString(
            'value="Livro Pagina Teste"',
            $resultado['saida']
        );
        $this->assertStringContainsString(
            'value="Autor Teste"',
            $resultado['saida']
        );
        $this->assertStringContainsString(
            'value="' . $this->isbn . '"',
            $resultado['saida']
        );
        $this->assertStringContainsString('selected', $resultado['saida']);
    }

    public function testEscapaCaracteresEspeciaisDoTitulo(): void
    {
        $codigo = $this->codigoLivro;

        $stmt = $this->conn->prepare(
            "UPDATE livro SET titulo = '<i>x</i>' WHERE codigo = ?"
        );
        $stmt->bind_param('i', $codigo);
        $stmt->execute();
        $stmt->close();

        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoLivro]
        );

        $this->assertStringContainsString(
            '&lt;i&gt;x&lt;/i&gt;',
            $resultado['saida']
        );
        $this->assertStringNotContainsString('value="<i>', $resultado['saida']);
    }

    public function testExibeMensagemDaSessaoUmaUnicaVez(): void
    {
        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoLivro],
            [],
            [
                'matricula' => 1234567890,
                'mensagem_edicao_livro' => 'Livro atualizado com sucesso!',
            ]
        );

        $this->assertStringContainsString(
            'Livro atualizado com sucesso!',
            $resultado['saida']
        );
        $this->assertArrayNotHasKey('mensagem_edicao_livro', $_SESSION);
    }

    public function testMostraErroDeValidacaoSemSalvar(): void
    {
        $post = array_merge($this->postValido(), ['titulo' => '']);

        $resultado = $this->executarPagina(
            'POST',
            [],
            $post
        );

        $this->assertNull($resultado['destino']);
        $this->assertStringContainsString(
            'Preencha ISBN, título e autor.',
            $resultado['saida']
        );
        $this->assertSame('Livro Pagina Teste', $this->buscarLivro()['titulo']);
    }

    public function testAtualizaOLivroERedireciona(): void
    {
        $resultado = $this->executarPagina('POST', [], $this->postValido());

        $this->assertSame(
            'editar_livro.php?codigo=' . $this->codigoLivro,
            $resultado['destino']
        );
        $this->assertSame(303, $resultado['statusRedirecionamento']);
        $this->assertSame(
            'Livro atualizado com sucesso!',
            $_SESSION['mensagem_edicao_livro']
        );

        $livro = $this->buscarLivro();

        $this->assertSame('Livro Atualizado', $livro['titulo']);
        $this->assertSame('Autor Novo', $livro['autor']);
        $this->assertEquals(2025, $livro['ano']);
        $this->assertSame('inativo', $livro['situacao']);
        $this->assertEquals(2, $livro['edicao']);
        $this->assertEquals(9, $livro['qtde_disponivel']);
    }

    public function testInformaQuandoOIsbnJaPertenceAOutroLivro(): void
    {
        $isbnDoOutro = random_int(7500000000000, 7999999999999);
        $this->inserirLivro($isbnDoOutro);

        $post = array_merge(
            $this->postValido(),
            ['isbn' => (string) $isbnDoOutro]
        );

        $resultado = $this->executarPagina('POST', [], $post);

        $this->assertNull($resultado['destino']);
        $this->assertStringContainsString(
            'Verifique se o ISBN já pertence a outro livro.',
            $resultado['saida']
        );
        $this->assertEquals($this->isbn, $this->buscarLivro()['isbn']);
    }
}
