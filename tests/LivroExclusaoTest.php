<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/LivroRepository.php';

class LivroExclusaoTest extends TestCase
{
    public function testBloquearExclusaoDeLivroComEmprestimo(): void
    {
        $resultado = $this->createMock(mysqli_result::class);
        $resultado->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(['total' => '1']);

        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('i', 42)
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultado);

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->once())
            ->method('prepare')
            ->with(
                'SELECT COUNT(*) AS total FROM emprestimo WHERE codigo_livro = ?'
            )
            ->willReturn($stmt);

        $repository = new LivroRepository($conn);

        $this->assertFalse($repository->excluir(42));
    }
}