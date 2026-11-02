<?php

use App\EmprestimoRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class StatementEspia extends mysqli_stmt
{
    public function __construct(private ConexaoEspiaDeTransacao $espia, string $query)
    {
        parent::__construct($espia, $query);
    }

    public function close(): bool
    {
        $this->espia->closes++;

        return parent::close();
    }
}

class ConexaoEspiaDeTransacao extends mysqli
{
    public int $prepares = 0;
    public int $closes = 0;
    public int $begins = 0;
    public int $commits = 0;
    public int $rollbacks = 0;
    public ?int $prepareRetornaFalseNaChamada = null;
    public ?int $prepareLancaExcecaoNaChamada = null;
    public bool $beginLancaExcecao = false;

    public function __construct()
    {
        parent::__construct(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );
    }

    public function prepare(string $query): mysqli_stmt|false
    {
        $this->prepares++;

        if ($this->prepares === $this->prepareRetornaFalseNaChamada) {
            return false;
        }

        if ($this->prepares === $this->prepareLancaExcecaoNaChamada) {
            throw new mysqli_sql_exception('falha simulada no prepare');
        }

        return new StatementEspia($this, $query);
    }

    public function begin_transaction(int $flags = 0, ?string $name = null): bool
    {
        $this->begins++;

        if ($this->beginLancaExcecao) {
            throw new mysqli_sql_exception('falha simulada no begin');
        }

        return parent::begin_transaction($flags, $name);
    }

    public function commit(int $flags = 0, ?string $name = null): bool
    {
        $this->commits++;

        return parent::commit($flags, $name);
    }

    public function rollback(int $flags = 0, ?string $name = null): bool
    {
        $this->rollbacks++;

        return parent::rollback($flags, $name);
    }
}

class EmprestimoTransacaoEspiaoTest extends TestCase
{
    private mysqli $conn;
    private int $codigoTurma;
    private int $matriculaAluno;
    private int $livroDisponivel;
    private int $livroEmprestado;
    private int $codigoEmprestimo;
    private int $matriculaAdministrador = 1234567890;

    /** @var ConexaoEspiaDeTransacao[] */
    private array $espioes = [];

    protected function setUp(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->codigoTurma = random_int(200000, 299999);
        $this->matriculaAluno = random_int(40000000, 49999999);

        $this->executar(
            "INSERT INTO turma (codigo, curso, periodo, serie)
             VALUES (?, 'Turma de Teste', 1, 1)",
            'i',
            $this->codigoTurma
        );

        $email = $this->matriculaAluno . '@teste.com';

        $this->executar(
            "INSERT INTO aluno (matricula, nome, email, codigoTurma)
             VALUES (?, 'Aluno de Teste', ?, ?)",
            'isi',
            $this->matriculaAluno,
            $email,
            $this->codigoTurma
        );

        $this->livroDisponivel = $this->criarLivro(
            random_int(6000000000000, 6499999999999),
            1
        );
        $this->livroEmprestado = $this->criarLivro(
            random_int(6500000000000, 6999999999999),
            0
        );

        $this->codigoEmprestimo = $this->executar(
            'INSERT INTO emprestimo
             (codigo_livro, dataEmprestimo, dataDevolucao,
              matricula_aluno, adm_responsavel)
             VALUES (?, ?, ?, ?, ?)',
            'issii',
            $this->livroEmprestado,
            '2026-10-01',
            '2026-10-21',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );
    }

    protected function tearDown(): void
    {
        foreach ($this->espioes as $espiao) {
            $espiao->close();
        }

        $this->executar(
            'DELETE FROM devolucao WHERE codigo_emprestimo = ?',
            'i',
            $this->codigoEmprestimo
        );

        foreach ([$this->livroDisponivel, $this->livroEmprestado] as $livro) {
            $this->executar(
                'DELETE FROM emprestimo WHERE codigo_livro = ?',
                'i',
                $livro
            );
            $this->executar(
                'DELETE FROM livro WHERE codigo = ?',
                'i',
                $livro
            );
        }

        $this->executar(
            'DELETE FROM aluno WHERE matricula = ?',
            'i',
            $this->matriculaAluno
        );
        $this->executar(
            'DELETE FROM turma WHERE codigo = ?',
            'i',
            $this->codigoTurma
        );

        $this->conn->close();
    }

    // ---------- registrarEmprestimo (mata 11, 12, 13, 14, 16) ----------

    public function testEmprestimoComSucessoConfirmaTransacaoEFechaStatements(): void
    {
        $espiao = $this->novoEspiao();

        $resultado = (new EmprestimoRepository($espiao))->registrarEmprestimo(
            $this->livroDisponivel,
            '2026-10-09',
            '2026-10-29',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );

        $this->assertTrue($resultado);
        $this->assertSame(1, $espiao->begins);
        $this->assertSame(1, $espiao->commits);
        $this->assertSame(0, $espiao->rollbacks);
        $this->assertGreaterThan(0, $espiao->prepares);
        $this->assertSame($espiao->prepares, $espiao->closes);
    }

    public function testEmprestimoDeLivroIndisponivelDesfazTransacao(): void
    {
        $espiao = $this->novoEspiao();

        $resultado = (new EmprestimoRepository($espiao))->registrarEmprestimo(
            $this->livroEmprestado,
            '2026-10-09',
            '2026-10-29',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );

        $this->assertFalse($resultado);
        $this->assertSame(1, $espiao->begins);
        $this->assertSame(0, $espiao->commits);
        $this->assertSame(1, $espiao->rollbacks);
        $this->assertSame($espiao->prepares, $espiao->closes);
    }

    public function testEmprestimoComViolacaoDeChaveDesfazERegistraLog(): void
    {
        $espiao = $this->novoEspiao();
        $administradorInexistente = random_int(1800000000, 1900000000);

        [$resultado, $log] = $this->comLogCapturado(
            fn () => (new EmprestimoRepository($espiao))->registrarEmprestimo(
                $this->livroDisponivel,
                '2026-10-09',
                '2026-10-29',
                $this->matriculaAluno,
                $administradorInexistente
            )
        );

        $this->assertFalse($resultado);
        $this->assertSame(0, $espiao->commits);
        $this->assertSame(1, $espiao->rollbacks);
        $this->assertStringContainsString('foreign key constraint', $log);
    }

    // ---------- registrarEmprestimo: erro no begin (mata 10) ----------

    public function testFalhaAoIniciarTransacaoDeEmprestimoNaoChamaRollback(): void
    {
        $espiao = $this->novoEspiao();
        $espiao->beginLancaExcecao = true;

        [$resultado] = $this->comLogCapturado(
            fn () => (new EmprestimoRepository($espiao))->registrarEmprestimo(
                $this->livroDisponivel,
                '2026-10-09',
                '2026-10-29',
                $this->matriculaAluno,
                $this->matriculaAdministrador
            )
        );

        $this->assertFalse($resultado);
        $this->assertSame(0, $espiao->commits);
        $this->assertSame(0, $espiao->rollbacks);
    }

    // ---------- registrarDevolucao (mata 19, 25, 27, 29, 30) ----------

    public function testDevolucaoComSucessoConfirmaTransacaoEFechaStatements(): void
    {
        $espiao = $this->novoEspiao();

        $resultado = (new EmprestimoRepository($espiao))
            ->registrarDevolucao($this->codigoEmprestimo, '2026-10-02');

        $this->assertTrue($resultado);
        $this->assertSame(1, $espiao->begins);
        $this->assertSame(1, $espiao->commits);
        $this->assertSame(0, $espiao->rollbacks);
        $this->assertGreaterThan(0, $espiao->prepares);
        $this->assertSame($espiao->prepares, $espiao->closes);
    }

    // ---------- Devolução sem transação (mata 17, 26, 25) ----------

    public function testDevolucaoDeEmprestimoInexistenteNaoUsaTransacao(): void
    {
        $espiao = $this->novoEspiao();

        $resultado = (new EmprestimoRepository($espiao))
            ->registrarDevolucao(2000000000, '2026-10-02');

        $this->assertFalse($resultado);
        $this->assertSame(0, $espiao->begins);
        $this->assertSame(0, $espiao->commits);
        $this->assertSame(0, $espiao->rollbacks);
        $this->assertGreaterThan(0, $espiao->prepares);
        $this->assertSame($espiao->prepares, $espiao->closes);
    }

    public function testDevolucaoDuplicadaNaoUsaTransacaoEFechaStatements(): void
    {
        $this->assertTrue(
            (new EmprestimoRepository($this->conn))
                ->registrarDevolucao($this->codigoEmprestimo, '2026-10-02')
        );

        $espiao = $this->novoEspiao();

        $resultado = (new EmprestimoRepository($espiao))
            ->registrarDevolucao($this->codigoEmprestimo, '2026-10-03');

        $this->assertFalse($resultado);
        $this->assertSame(0, $espiao->begins);
        $this->assertSame(0, $espiao->rollbacks);
        $this->assertSame($espiao->prepares, $espiao->closes);
    }

    // ---------- Falhas de prepare em cada etapa (mata 17, 18, 20) ----------

    #[DataProvider('etapasQueFalham')]
    public function testTodaTransacaoIniciadaEDesfeitaQuandoOPrepareFalha(int $chamada): void
    {
        $espiao = $this->novoEspiao();
        $espiao->prepareRetornaFalseNaChamada = $chamada;

        $resultado = (new EmprestimoRepository($espiao))
            ->registrarDevolucao($this->codigoEmprestimo, '2026-10-02');

        $this->assertFalse($resultado);
        $this->assertSame(0, $espiao->commits);
        $this->assertSame($espiao->begins, $espiao->rollbacks);
    }

    public static function etapasQueFalham(): array
    {
        return [
            'ao verificar devolução existente' => [1],
            'ao buscar o livro do empréstimo' => [2],
            'ao inserir a devolução' => [3],
            'ao atualizar o estoque' => [4],
        ];
    }

    // ---------- Exceções (mata 18, 22, 23, 24 e reforça 17) ----------

    public function testExcecaoDepoisDoBeginDesfazTransacaoERegistraLog(): void
    {
        $espiao = $this->novoEspiao();

        [$resultado, $log] = $this->comLogCapturado(
            fn () => (new EmprestimoRepository($espiao))
                ->registrarDevolucao($this->codigoEmprestimo, 'data-invalida')
        );

        $this->assertFalse($resultado);
        $this->assertSame(1, $espiao->begins);
        $this->assertSame(0, $espiao->commits);
        $this->assertSame(1, $espiao->rollbacks);
        $this->assertStringContainsString("data-invalida", $log);
    }

    public function testExcecaoAntesDoBeginNaoChamaRollback(): void
    {
        $espiao = $this->novoEspiao();
        $espiao->prepareLancaExcecaoNaChamada = 1;

        [$resultado] = $this->comLogCapturado(
            fn () => (new EmprestimoRepository($espiao))
                ->registrarDevolucao($this->codigoEmprestimo, '2026-10-02')
        );

        $this->assertFalse($resultado);
        $this->assertSame(0, $espiao->begins);
        $this->assertSame(0, $espiao->rollbacks);
    }

    // ---------- Auxiliares ----------

    private function novoEspiao(): ConexaoEspiaDeTransacao
    {
        $espiao = new ConexaoEspiaDeTransacao();
        $this->espioes[] = $espiao;

        return $espiao;
    }

    private function comLogCapturado(callable $acao): array
    {
        $arquivo = tempnam(sys_get_temp_dir(), 'pnld_log_');
        $anterior = (string) ini_get('error_log');
        $log = '';

        ini_set('error_log', $arquivo);

        try {
            $resultado = $acao();
            $log = (string) file_get_contents($arquivo);
        } finally {
            ini_set('error_log', $anterior);
            @unlink($arquivo);
        }

        return [$resultado, $log];
    }

    private function criarLivro(int $isbn, int $quantidade): int
    {
        return $this->executar(
            "INSERT INTO livro
             (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
             VALUES (?, 'Livro de Teste', 'Autor de Teste', 1, 2026, 'ativo', 1, ?)",
            'ii',
            $isbn,
            $quantidade
        );
    }

    private function executar(string $sql, string $tipos, mixed ...$valores): int
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($tipos, ...$valores);
        $stmt->execute();

        $id = (int) $this->conn->insert_id;
        $stmt->close();

        return $id;
    }
}