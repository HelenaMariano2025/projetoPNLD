<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/TurmaRepository.php';

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
        $resultadoMock = $this->createMock(mysqli_result::class);

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
}