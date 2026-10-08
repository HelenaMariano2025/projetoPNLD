<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AlunoRepository.php';

class AlunoRepositoryIntegrationTest extends TestCase
{
    private mysqli $conn;
    private AlunoRepository $repository;
    private int $codigoTurma;
    private int $outraTurma;
    private int $matricula;

    protected function setUp(): void
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = new mysqli(
            getenv('DB_HOST') ?: 'db',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: 'pnld_local',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $this->conn->set_charset('utf8mb4');
        $this->conn->begin_transaction();

        $this->repository = new AlunoRepository($this->conn);

        $this->codigoTurma = random_int(100000000, 900000000);
        $this->outraTurma = $this->codigoTurma + 1;
        $this->matricula = $this->codigoTurma;

        $stmt = $this->conn->prepare(
            "INSERT INTO turma
                (codigo, curso, periodo, serie, situacao)
             VALUES (?, 'Curso de teste', 1, 1, 'ativo')"
        );

        $stmt->bind_param('i', $this->codigoTurma);
        $stmt->execute();

        $stmt->bind_param('i', $this->outraTurma);
        $stmt->execute();
    }

    protected function tearDown(): void
    {
        if (isset($this->conn)) {
            $this->conn->rollback();
            $this->conn->close();
        }
    }

    private function cadastrarAluno(): void
    {
        $this->assertTrue(
            $this->repository->inserir(
                $this->matricula,
                'Aluno Teste ' . $this->matricula,
                '2000-01-01',
                'Rua de teste',
                'F',
                'aluno@example.com',
                'ativo',
                $this->codigoTurma
            )
        );
    }

    public function testCadastrarEConsultarAlunoNoBanco(): void
    {
        $this->cadastrarAluno();

        $aluno = $this->repository->consultarPorMatricula(
            $this->matricula
        );

        $this->assertNotNull($aluno);
        $this->assertEquals($this->matricula, $aluno['matricula']);
        $this->assertSame(
            'Aluno Teste ' . $this->matricula,
            $aluno['nome']
        );
        $this->assertSame('ativo', $aluno['situacao']);
        $this->assertEquals(
            $this->codigoTurma,
            $aluno['codigoTurma']
        );
    }

    public function testAlterarDadosETrocarTurmaNoBanco(): void
    {
        $this->cadastrarAluno();

        $this->assertTrue(
            $this->repository->atualizar(
                $this->matricula,
                'Nome atualizado',
                '2001-02-03',
                'Outro endereço',
                'M',
                'atualizado@example.com',
                'ativo',
                $this->outraTurma
            )
        );

        $aluno = $this->repository->consultarPorMatricula(
            $this->matricula
        );

        $this->assertSame('Nome atualizado', $aluno['nome']);
        $this->assertSame('2001-02-03', $aluno['datnasc']);
        $this->assertSame('Outro endereço', $aluno['endereco']);
        $this->assertSame('M', $aluno['sexo']);
        $this->assertSame(
            'atualizado@example.com',
            $aluno['email']
        );
        $this->assertEquals(
            $this->outraTurma,
            $aluno['codigoTurma']
        );
        $this->assertEquals(
            $this->matricula,
            $aluno['matricula']
        );
    }

    public function testPesquisarEInativarAlunoNoBanco(): void
    {
        $this->cadastrarAluno();

        $termo = 'Teste ' . $this->matricula;

        $encontrados = $this->repository
            ->pesquisarPorNome($termo)
            ->fetch_all(MYSQLI_ASSOC);

        $this->assertCount(1, $encontrados);
        $this->assertEquals(
            $this->matricula,
            $encontrados[0]['matricula']
        );

        $this->assertSame(
            0,
            $this->repository
                ->pesquisarPorNome(
                    'SemCorrespondencia-' . $this->matricula
                )
                ->num_rows
        );

        $this->assertTrue(
            $this->repository->inativar($this->matricula)
        );

        $aluno = $this->repository->consultarPorMatricula(
            $this->matricula
        );

        $this->assertNotNull($aluno);
        $this->assertSame('inativo', $aluno['situacao']);

        $this->assertSame(
            0,
            $this->repository->pesquisarPorNome($termo)->num_rows
        );

        $ativos = $this->repository
            ->consultarAtivos()
            ->fetch_all(MYSQLI_ASSOC);

        $matriculas = array_map(
            'strval',
            array_column($ativos, 'matricula')
        );

        $this->assertNotContains(
            (string) $this->matricula,
            $matriculas
        );
    }

    public function testInativarPreservaEmprestimoEDevolucao(): void
    {
        $this->cadastrarAluno();

        $stmt = $this->conn->prepare(
            "INSERT INTO administrador (matricula, nome, senha)
             VALUES (?, 'Administrador teste', 'senha-teste')"
        );
        $stmt->bind_param('i', $this->matricula);
        $stmt->execute();

        $stmt = $this->conn->prepare(
            "INSERT INTO livro
                (isbn, titulo, autor, codigo_editora, ano,
                 situacao, edicao, qtde_disponivel)
             VALUES (?, 'Livro teste', 'Autor teste', 1,
                     2026, 'ativo', 1, 1)"
        );
        $stmt->bind_param('i', $this->matricula);
        $stmt->execute();

        $codigoLivro = $this->conn->insert_id;

        $stmt = $this->conn->prepare(
            "INSERT INTO emprestimo
                (codigo_livro, dataEmprestimo, dataDevolucao,
                 matricula_aluno, adm_responsavel)
             VALUES (?, '2026-10-01', '2026-10-06', ?, ?)"
        );
        $stmt->bind_param(
            'iii',
            $codigoLivro,
            $this->matricula,
            $this->matricula
        );
        $stmt->execute();

        $codigoEmprestimo = $this->conn->insert_id;

        $stmt = $this->conn->prepare(
            "INSERT INTO devolucao (codigo_emprestimo, dataDevolucao)
             VALUES (?, '2026-10-06')"
        );
        $stmt->bind_param('i', $codigoEmprestimo);
        $stmt->execute();

        $codigoDevolucao = $this->conn->insert_id;

        $this->assertTrue(
            $this->repository->inativar($this->matricula)
        );

        $aluno = $this->repository->consultarPorMatricula(
            $this->matricula
        );

        $this->assertNotNull($aluno);
        $this->assertSame('inativo', $aluno['situacao']);

        $stmt = $this->conn->prepare(
            "SELECT e.matricula_aluno, e.codigo_livro,
                    e.adm_responsavel, d.codigo_emprestimo
             FROM emprestimo e
             JOIN devolucao d
                ON d.codigo_emprestimo = e.codigo_emprestimo
             WHERE e.codigo_emprestimo = ?
               AND d.codigo_devolucao = ?"
        );
        $stmt->bind_param(
            'ii',
            $codigoEmprestimo,
            $codigoDevolucao
        );
        $stmt->execute();

        $historico = $stmt->get_result()->fetch_assoc();

        $this->assertNotNull($historico);
        $this->assertEquals(
            $this->matricula,
            $historico['matricula_aluno']
        );
        $this->assertEquals(
            $codigoLivro,
            $historico['codigo_livro']
        );
        $this->assertEquals(
            $this->matricula,
            $historico['adm_responsavel']
        );
        $this->assertEquals(
            $codigoEmprestimo,
            $historico['codigo_emprestimo']
        );

        $this->assertSame(
            0,
            $this->repository
                ->pesquisarPorNome('Teste ' . $this->matricula)
                ->num_rows
        );
    }
}