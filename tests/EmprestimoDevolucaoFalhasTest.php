<?php

use App\EmprestimoRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class ConexaoQueFalhaNoPrepare extends mysqli
{
    private int $chamadas = 0;

    public function __construct(
        private int $falharNaChamada,
        string $host,
        string $usuario,
        string $senha,
        string $banco
    ) {
        parent::__construct($host, $usuario, $senha, $banco);
    }

    public function prepare(string $query): mysqli_stmt|false
    {
        $this->chamadas++;

        if ($this->chamadas === $this->falharNaChamada) {
            return false;
        }

        return parent::prepare($query);
    }
}

class EmprestimoDevolucaoFalhasTest extends TestCase
{
    private mysqli $conn;
    private int $codigoTurma;
    private int $matriculaAluno;
    private int $codigoLivro;
    private int $codigoEmprestimo;
    private int $matriculaAdministrador = 1234567890;

    protected function setUp(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->codigoTurma = random_int(100000, 199999);
        $this->matriculaAluno = random_int(30000000, 39999999);

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

        $isbn = random_int(8000000000000, 8999999999999);

        $this->codigoLivro = $this->executar(
            "INSERT INTO livro
             (isbn, titulo, autor, codigo_editora, ano, situacao, edicao, qtde_disponivel)
             VALUES (?, 'Livro de Teste', 'Autor de Teste', 1, 2026, 'ativo', 1, 0)",
            'i',
            $isbn
        );

        $this->codigoEmprestimo = $this->executar(
            'INSERT INTO emprestimo
             (codigo_livro, dataEmprestimo, dataDevolucao,
              matricula_aluno, adm_responsavel)
             VALUES (?, ?, ?, ?, ?)',
            'issii',
            $this->codigoLivro,
            '2026-10-01',
            '2026-10-21',
            $this->matriculaAluno,
            $this->matriculaAdministrador
        );
    }

    protected function tearDown(): void
    {
        $this->executar(
            'DELETE FROM devolucao WHERE codigo_emprestimo = ?',
            'i',
            $this->codigoEmprestimo
        );
        $this->executar(
            'DELETE FROM emprestimo WHERE codigo_emprestimo = ?',
            'i',
            $this->codigoEmprestimo
        );
        $this->executar(
            'DELETE FROM livro WHERE codigo = ?',
            'i',
            $this->codigoLivro
        );
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

    public function testNaoRegistraDevolucaoDeEmprestimoInexistente(): void
    {
        $repository = new EmprestimoRepository($this->conn);

        $this->assertFalse(
            $repository->registrarDevolucao(2000000000, '2026-10-02')
        );
    }

    #[DataProvider('etapasQueFalham')]
    public function testNaoGravaNadaQuandoOPrepareFalha(int $chamada): void
    {
        $conexao = new ConexaoQueFalhaNoPrepare(
            $chamada,
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        try {
            $repository = new EmprestimoRepository($conexao);

            $this->assertFalse(
                $repository->registrarDevolucao(
                    $this->codigoEmprestimo,
                    '2026-10-02'
                )
            );
        } finally {
            $conexao->close();
        }

        $this->assertSame(0, $this->contarDevolucoes());
        $this->assertSame(0, $this->estoqueDoLivro());
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

    public function testReverteQuandoOBancoRejeitaOsDados(): void
    {
        $repository = new EmprestimoRepository($this->conn);

        $this->assertFalse(
            $repository->registrarDevolucao(
                $this->codigoEmprestimo,
                'data-invalida'
            )
        );

        $this->assertSame(0, $this->contarDevolucoes());
        $this->assertSame(0, $this->estoqueDoLivro());
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

    private function contarDevolucoes(): int
    {
        $codigo = $this->codigoEmprestimo;

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) AS total FROM devolucao
             WHERE codigo_emprestimo = ?'
        );
        $stmt->bind_param('i', $codigo);
        $stmt->execute();

        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) $linha['total'];
    }

    private function estoqueDoLivro(): int
    {
        $codigo = $this->codigoLivro;

        $stmt = $this->conn->prepare(
            'SELECT qtde_disponivel FROM livro WHERE codigo = ?'
        );
        $stmt->bind_param('i', $codigo);
        $stmt->execute();

        $linha = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return (int) $linha['qtde_disponivel'];
    }
}
