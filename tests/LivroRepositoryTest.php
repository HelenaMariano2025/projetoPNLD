<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/LivroRepository.php';

class LivroRepositoryTest extends TestCase
{
    public function testInserirLivro(): void
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

        $repository = new LivroRepository($conn);

        $resultado = $repository->inserir(
            '9788535914849',
            'Livro de teste',
            'Autora de teste',
            1,
            2024,
            'ativo',
            1,
            5
        );

        $this->assertTrue($resultado);
    }
}