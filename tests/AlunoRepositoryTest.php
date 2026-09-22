<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AlunoRepository.php';

class AlunoRepositoryTest extends TestCase
{
    public function testInserirAluno()
    {
        $stmt = $this->createMock(mysqli_stmt::class);

        $stmt->expects($this->once())
             ->method('bind_param')
             ->willReturn(true);

        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(true);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
             ->method('prepare')
             ->willReturn($stmt);

        $repository = new AlunoRepository($conn);

        $resultado = $repository->inserir(
            123456,
            'João da Silva',
            '2000-01-01',
            'Rua A',
            'M',
            'joao@email.com',
            'ativo',
            1
        );

        $this->assertTrue($resultado);
    }

    public function testConsultarAlunosAtivos()
    {
        $resultadoMock = $this->createMock(mysqli_result::class);

        $conn = $this->createMock(mysqli::class);

        $conn->expects($this->once())
             ->method('query')
             ->with("SELECT * FROM aluno WHERE situacao = 'ativo'")
             ->willReturn($resultadoMock);

        $repository = new AlunoRepository($conn);

        $resultado = $repository->consultarAtivos();

        $this->assertSame($resultadoMock, $resultado);
    }

    public function testAtualizarAluno()
{
    $stmt = $this->createMock(mysqli_stmt::class);

    $stmt->expects($this->once())
         ->method('bind_param')
         ->willReturn(true);

    $stmt->expects($this->once())
         ->method('execute')
         ->willReturn(true);

    $conn = $this->createMock(mysqli::class);

    $conn->expects($this->once())
         ->method('prepare')
         ->with(
             "UPDATE aluno SET
                nome = ?,
                datnasc = ?,
                endereco = ?,
                sexo = ?,
                email = ?,
                situacao = ?,
                codigoTurma = ?
                WHERE matricula = ?"
         )
         ->willReturn($stmt);

    $repository = new AlunoRepository($conn);

    $resultado = $repository->atualizar(
        123456,
        'João da Silva Atualizado',
        '2000-01-01',
        'Rua B',
        'M',
        'joao@email.com',
        'ativo',
        1
    );

    $this->assertTrue($resultado);
}


public function testInativarAluno()
{
    $stmt = $this->createMock(mysqli_stmt::class);

    $stmt->expects($this->once())
         ->method('bind_param')
         ->willReturn(true);

    $stmt->expects($this->once())
         ->method('execute')
         ->willReturn(true);

    $conn = $this->createMock(mysqli::class);

    $conn->expects($this->once())
         ->method('prepare')
         ->with(
             "UPDATE aluno
                SET situacao = 'inativo'
                WHERE matricula = ?"
         )
         ->willReturn($stmt);

    $repository = new AlunoRepository($conn);

    $resultado = $repository->inativar(123456);

    $this->assertTrue($resultado);
}
}