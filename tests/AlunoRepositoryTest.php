<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AlunoRepository.php';

class AlunoRepositoryTest extends TestCase
{
    private function prepararOperacao($sqlEsperado, $parametros, $sucesso = true)
    {
        $stmt = $this->createMock(mysqli_stmt::class);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with(...$parametros)
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn($sucesso);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('prepare')
            ->with($this->callback(
                static function ($sql) use ($sqlEsperado) {
                    $normalizado = preg_replace('/\s+/', ' ', trim($sql));

                    return $normalizado === $sqlEsperado;
                }
            ))
            ->willReturn($stmt);

        return [new AlunoRepository($conn), $stmt];
    }

    public function testInserirAluno(): void
    {
        [$repository] = $this->prepararOperacao(
            'INSERT INTO aluno '
            . '(matricula, nome, datnasc, endereco, sexo, email, situacao, codigoTurma) '
            . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                'issssssi',
                123456,
                'João da Silva',
                '2000-01-01',
                'Rua A',
                'M',
                'joao@example.com',
                'ativo',
                1
            ]
        );

        $resultado = $repository->inserir(
            123456,
            'João da Silva',
            '2000-01-01',
            'Rua A',
            'M',
            'joao@example.com',
            'ativo',
            1
        );

        $this->assertTrue($resultado);
    }

    public function testConsultarSomenteAlunosAtivos(): void
    {
        $resultadoMock = $this->createStub(mysqli_result::class);
        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('query')
            ->with("SELECT * FROM aluno WHERE situacao = 'ativo'")
            ->willReturn($resultadoMock);

        $repository = new AlunoRepository($conn);

        $this->assertSame(
            $resultadoMock,
            $repository->consultarAtivos()
        );
    }

    public function testConsultarAlunoPorMatricula(): void
    {
        [$repository, $stmt] = $this->prepararOperacao(
            'SELECT * FROM aluno WHERE matricula = ?',
            ['s', '123456']
        );

        $aluno = [
            'matricula' => '123456',
            'nome' => 'João da Silva',
            'codigoTurma' => 1
        ];

        $resultadoMock = $this->createMock(mysqli_result::class);

        $resultadoMock->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn($aluno);

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoMock);

        $this->assertSame(
            $aluno,
            $repository->consultarPorMatricula('123456')
        );
    }

    public function testConsultaDeMatriculaInexistenteRetornaNull(): void
    {
        [$repository, $stmt] = $this->prepararOperacao(
            'SELECT * FROM aluno WHERE matricula = ?',
            ['s', '999999']
        );

        $resultadoMock = $this->createMock(mysqli_result::class);

        $resultadoMock->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(null);

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoMock);

        $this->assertNull(
            $repository->consultarPorMatricula('999999')
        );
    }

    public function testAtualizarDadosETurmaDoAluno(): void
    {
        [$repository] = $this->prepararOperacao(
            'UPDATE aluno SET nome = ?, datnasc = ?, endereco = ?, '
            . 'sexo = ?, email = ?, situacao = ?, codigoTurma = ? '
            . 'WHERE matricula = ?',
            [
                'ssssssii',
                'João Atualizado',
                '2000-01-01',
                'Rua B',
                'M',
                'outro@example.com',
                'ativo',
                2,
                123456
            ]
        );

        $resultado = $repository->atualizar(
            123456,
            'João Atualizado',
            '2000-01-01',
            'Rua B',
            'M',
            'outro@example.com',
            'ativo',
            2
        );

        $this->assertTrue($resultado);
    }

    public function testInativarUsaUpdateSemApagarAluno(): void
    {
        [$repository] = $this->prepararOperacao(
            "UPDATE aluno SET situacao = 'inativo' WHERE matricula = ?",
            ['i', 123456]
        );

        $this->assertTrue($repository->inativar(123456));
    }

    public function testPesquisarPorParteDoNome(): void
    {
        [$repository, $stmt] = $this->prepararOperacao(
            "SELECT * FROM aluno WHERE situacao = 'ativo' "
            . "AND nome LIKE ? ESCAPE '!' ORDER BY nome",
            ['s', '%Silva%']
        );

        $resultadoMock = $this->createStub(mysqli_result::class);

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoMock);

        $this->assertSame(
            $resultadoMock,
            $repository->pesquisarPorNome('Silva')
        );
    }

    public function testPesquisaTrataCaracteresEspeciaisComoTexto(): void
    {
        [$repository, $stmt] = $this->prepararOperacao(
            "SELECT * FROM aluno WHERE situacao = 'ativo' "
            . "AND nome LIKE ? ESCAPE '!' ORDER BY nome",
            ['s', '%Ana!_!%!!%']
        );

        $resultadoMock = $this->createStub(mysqli_result::class);

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoMock);

        $this->assertSame(
            $resultadoMock,
            $repository->pesquisarPorNome('Ana_%!')
        );
    }

    public function testRetornaFalseQuandoInativacaoFalha(): void
    {
        [$repository] = $this->prepararOperacao(
            "UPDATE aluno SET situacao = 'inativo' WHERE matricula = ?",
            ['i', 123456],
            false
        );

        $this->assertFalse($repository->inativar(123456));
    }
}