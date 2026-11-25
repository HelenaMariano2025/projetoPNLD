<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../php/http.php';

class EditarTurmaPaginaTest extends TestCase
{
    private mysqli $conn;
    private int $codigoTurma;
    private string $nomeCurso;

    protected function setUp(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = $this->novaConexao();
        $this->codigoTurma = random_int(300000, 399999);
        $this->nomeCurso = 'Curso ' . $this->codigoTurma;

        $codigo = $this->codigoTurma;
        $curso = $this->nomeCurso;

        $stmt = $this->conn->prepare(
            "INSERT INTO turma
             (codigo, curso, siglaCurso, periodo, serie,
              matrizCurricular, situacao)
             VALUES (?, ?, 'SIG', 1, 2, 'Matriz de teste', 'ativo')"
        );
        $stmt->bind_param('is', $codigo, $curso);
        $stmt->execute();
        $stmt->close();
    }

    protected function tearDown(): void
    {
        $codigo = $this->codigoTurma;

        $stmt = $this->conn->prepare('DELETE FROM turma WHERE codigo = ?');
        $stmt->bind_param('i', $codigo);
        $stmt->execute();
        $stmt->close();

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

        ob_start();

        try {
            include __DIR__ . '/../editar_turma.php';
        } catch (RedirecionamentoException $e) {
            $destino = $e->destino;
        } finally {
            $saida = ob_get_clean();
        }

        return ['saida' => $saida, 'destino' => $destino];
    }

    private function postValido(): array
    {
        return [
            'codigo' => (string) $this->codigoTurma,
            'curso' => 'Atualizado',
            'siglaCurso' => 'NOVA',
            'periodo' => '3',
            'serie' => '4',
            'matrizCurricular' => 'Matriz nova',
            'situacao' => 'inativo',
        ];
    }

    private function buscarTurma(): array
    {
        $codigo = $this->codigoTurma;

        $stmt = $this->conn->prepare('SELECT * FROM turma WHERE codigo = ?');
        $stmt->bind_param('i', $codigo);
        $stmt->execute();

        $turma = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $turma;
    }

    public function testRedirecionaParaLoginSemAutenticacao(): void
    {
        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoTurma],
            [],
            []
        );

        $this->assertSame('login.php', $resultado['destino']);
    }

    public function testRedirecionaQuandoOCodigoNaoEInformado(): void
    {
        $resultado = $this->executarPagina('GET');

        $this->assertSame('turmas.php', $resultado['destino']);
        $this->assertSame('Código de turma inválido.', $_SESSION['mensagem']);
    }

    public function testRedirecionaQuandoATurmaNaoExiste(): void
    {
        $resultado = $this->executarPagina('GET', ['codigo' => '2000000000']);

        $this->assertSame('turmas.php', $resultado['destino']);
        $this->assertSame('Turma não encontrada.', $_SESSION['mensagem']);
    }

    public function testExibeFormularioComOsDadosDaTurma(): void
    {
        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoTurma]
        );

        $this->assertNull($resultado['destino']);
        $this->assertStringContainsString(
            'value="' . $this->nomeCurso . '"',
            $resultado['saida']
        );
        $this->assertStringContainsString('value="SIG"', $resultado['saida']);
        $this->assertStringContainsString(
            'value="Matriz de teste"',
            $resultado['saida']
        );
        $this->assertStringContainsString(
            '<option value="ativo" selected>',
            $resultado['saida']
        );
    }

    public function testEscapaCaracteresEspeciaisNoFormulario(): void
    {
        $codigo = $this->codigoTurma;

        $stmt = $this->conn->prepare(
            "UPDATE turma SET curso = '<b>x</b>' WHERE codigo = ?"
        );
        $stmt->bind_param('i', $codigo);
        $stmt->execute();
        $stmt->close();

        $resultado = $this->executarPagina(
            'GET',
            ['codigo' => (string) $this->codigoTurma]
        );

        $this->assertStringContainsString(
            '&lt;b&gt;x&lt;/b&gt;',
            $resultado['saida']
        );
        $this->assertStringNotContainsString('value="<b>', $resultado['saida']);
    }

    #[DataProvider('alteracoesInvalidas')]
    public function testRejeitaAtualizacaoComDadosInvalidos(array $alteracao): void
    {
        $post = array_merge($this->postValido(), $alteracao);

        $resultado = $this->executarPagina('POST', [], $post);

        $this->assertSame('turmas.php', $resultado['destino']);
        $this->assertSame(
            'Preencha os campos obrigatórios com valores válidos.',
            $_SESSION['mensagem']
        );
        $this->assertSame($this->nomeCurso, $this->buscarTurma()['curso']);
    }

    public static function alteracoesInvalidas(): array
    {
        return [
            'curso vazio' => [['curso' => '']],
            'periodo zero' => [['periodo' => '0']],
            'serie nao numerica' => [['serie' => 'abc']],
            'situacao invalida' => [['situacao' => 'pendente']],
            'codigo ausente' => [['codigo' => '']],
        ];
    }

    public function testAtualizaATurmaComDadosValidos(): void
    {
        $resultado = $this->executarPagina('POST', [], $this->postValido());

        $this->assertSame('turmas.php', $resultado['destino']);
        $this->assertSame('Turma atualizada com sucesso!', $_SESSION['mensagem']);

        $turma = $this->buscarTurma();

        $this->assertSame('Atualizado', $turma['curso']);
        $this->assertSame('NOVA', $turma['siglaCurso']);
        $this->assertEquals(3, $turma['periodo']);
        $this->assertEquals(4, $turma['serie']);
        $this->assertSame('Matriz nova', $turma['matrizCurricular']);
        $this->assertSame('inativo', $turma['situacao']);
    }

    public function testInformaErroQuandoOBancoRejeitaOsDados(): void
    {
        $post = array_merge(
            $this->postValido(),
            ['curso' => str_repeat('x', 30)]
        );

        $resultado = $this->executarPagina('POST', [], $post);

        $this->assertSame('turmas.php', $resultado['destino']);
        $this->assertSame(
            'Ocorreu um erro ao atualizar a turma.',
            $_SESSION['mensagem']
        );
        $this->assertSame($this->nomeCurso, $this->buscarTurma()['curso']);
    }
}
