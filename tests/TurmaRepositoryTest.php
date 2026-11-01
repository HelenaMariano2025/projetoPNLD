<?php

use App\TurmaRepository;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

class TurmaRepositoryTest extends TestCase
{
    public function testInserirTurma()
    {
        $stmt = $this->createMock(mysqli_stmt::class);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with(
                "issiiss",
                10,
                'Informática',
                'INFO',
                2,
                1,
                'Matriz 2026',
                'ativo'
            );

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $repository = new TurmaRepository($conn);

        $resultado = $repository->inserir(
            10,
            'Informática',
            'INFO',
            2,
            1,
            'Matriz 2026',
            'ativo'
        );

        $this->assertTrue($resultado);
    }

    public function testConsultarTurmasAtivas()
    {
        $resultadoMock = $this->createStub(mysqli_result::class);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('query')
            ->with("SELECT * FROM turma WHERE situacao = 'ativo'")
            ->willReturn($resultadoMock);

        $repository = new TurmaRepository($conn);

        $resultado = $repository->consultarAtivos();

        $this->assertSame($resultadoMock, $resultado);
    }

    public function testAtualizarTurma()
    {
        $stmt = $this->createMock(mysqli_stmt::class);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with(
                "ssiissi",
                'Informática',
                'INFO',
                2,
                1,
                'Matriz 2026',
                'ativo',
                10
            );

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $repository = new TurmaRepository($conn);

        $resultado = $repository->atualizar(
            10,
            'Informática',
            'INFO',
            2,
            1,
            'Matriz 2026',
            'ativo'
        );

        $this->assertTrue($resultado);
    }

    public function testInativarTurma()
    {
        $stmt = $this->createMock(mysqli_stmt::class);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with("i", 10);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $repository = new TurmaRepository($conn);

        $resultado = $repository->inativar(10);

        $this->assertTrue($resultado);
    }
    
    public function testPesquisarTurmasPorCurso()
    {
        $stmt = $this->createMock(mysqli_stmt::class);
        $resultadoMock = $this->createStub(mysqli_result::class);

        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('ss', 'ativo', '%Informatica%');

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoMock);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT * FROM turma WHERE situacao = ? AND curso LIKE ?'
            )
            ->willReturn($stmt);

        $repository = new TurmaRepository($conn);

        $resultado = $repository->pesquisarPorCurso('Informatica');

        $this->assertSame($resultadoMock, $resultado);
    }

    public function testTemAlunosVinculadosRetornaVerdadeiroQuandoHaAlunos()
    {
        $this->assertTrue(
            $this->consultarVinculos(['total' => '2'])
        );
    }

    public function testTemAlunosVinculadosRetornaFalsoQuandoNaoHaAlunos()
    {
        $this->assertFalse(
            $this->consultarVinculos(['total' => '0'])
        );
    }

    private function consultarVinculos(array $linha): bool
    {
        $resultado = $this->createStub(mysqli_result::class);
        $resultado->method('fetch_assoc')->willReturn($linha);

        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('i', 10);
        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultado);
        $stmt->expects($this->once())->method('close');

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        return (new TurmaRepository($conn))->temAlunosVinculados(10);
    }

    public function testExcluirBloqueiaQuandoHaAlunosVinculados()
    {
        $resultado = $this->createStub(mysqli_result::class);
        $resultado->method('fetch_assoc')->willReturn(['total' => '3']);

        $stmtContagem = $this->createStub(mysqli_stmt::class);
        $stmtContagem->method('get_result')->willReturn($resultado);

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtContagem);

        $repository = new TurmaRepository($conn);

        $this->assertFalse($repository->excluir(10));
    }

    public function testExcluirTurmaSemAlunosVinculados()
    {
        $resultado = $this->createStub(mysqli_result::class);
        $resultado->method('fetch_assoc')->willReturn(['total' => '0']);

        $stmtContagem = $this->createStub(mysqli_stmt::class);
        $stmtContagem->method('get_result')->willReturn($resultado);

        $stmtExclusao = $this->createMock(mysqli_stmt::class);
        $stmtExclusao->expects($this->once())
            ->method('bind_param')
            ->with('i', 10);
        $stmtExclusao->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtExclusao->expects($this->once())->method('close');

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($stmtContagem, $stmtExclusao);

        $repository = new TurmaRepository($conn);

        $this->assertTrue($repository->excluir(10));
    }
}
