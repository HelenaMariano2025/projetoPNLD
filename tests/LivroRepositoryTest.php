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

        $this->assertTrue($repository->inserir(
            '9788535914849',
            'Livro de teste',
            'Autora de teste',
            1,
            2024,
            'ativo',
            1,
            5
        ));
    }

    public function testConsultarLivrosDisponiveisPorTitulo(): void
    {
        $resultadoEsperado = $this->createStub(mysqli_result::class);

        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
            ->method('bind_param')
            ->with('s', '%Química%')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoEsperado);

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->once())
            ->method('prepare')
            ->with($this->callback(
                fn (string $sql): bool => str_contains($sql, 'titulo LIKE ?')
            ))
            ->willReturn($stmt);

        $repository = new LivroRepository($conn);

        $this->assertSame(
            $resultadoEsperado,
            $repository->consultarDisponiveis('Química')
        );
    }

    public function testConsultarAcervoSemFiltrarSituacaoOuEstoque(): void
    {
        $resultadoEsperado = $this->createStub(mysqli_result::class);

        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($resultadoEsperado);

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM livro ORDER BY titulo, codigo')
            ->willReturn($stmt);

        $repository = new LivroRepository($conn);

        $this->assertSame(
            $resultadoEsperado,
            $repository->consultarTodos()
        );
    }

    public function testAtualizarLivro(): void
    {
        $stmt = $this->createMock(mysqli_stmt::class);
        $stmt->expects($this->once())
            ->method('bind_param')
            ->with(
                'sssiisiii',
                '9788535914849',
                'Título atualizado',
                'Autora de teste',
                1,
                2025,
                'ativo',
                2,
                8,
                42
            )
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->once())
            ->method('prepare')
            ->with($this->callback(
                fn (string $sql): bool =>
                    str_contains($sql, 'UPDATE livro')
                    && str_contains($sql, 'WHERE codigo = ?')
            ))
            ->willReturn($stmt);

        $repository = new LivroRepository($conn);

        $this->assertTrue($repository->atualizar(
            42,
            '9788535914849',
            'Título atualizado',
            'Autora de teste',
            1,
            2025,
            'ativo',
            2,
            8
        ));
    }

    public function testExcluirLivroSemVinculos(): void
    {
        $resultado = $this->createMock(mysqli_result::class);
        $resultado->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(['total' => '0']);

        $consulta = $this->createMock(mysqli_stmt::class);
        $consulta->expects($this->once())
            ->method('bind_param')
            ->with('i', 42)
            ->willReturn(true);
        $consulta->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $consulta->expects($this->once())
            ->method('get_result')
            ->willReturn($resultado);

        $exclusao = $this->createMock(mysqli_stmt::class);
        $exclusao->expects($this->once())
            ->method('bind_param')
            ->with('i', 42)
            ->willReturn(true);
        $exclusao->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $conn = $this->createMock(mysqli::class);
        $conn->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnCallback(
                function (string $sql) use ($consulta, $exclusao): mysqli_stmt {
                    if (
                        $sql === 'SELECT COUNT(*) AS total FROM emprestimo WHERE codigo_livro = ?'
                    ) {
                        return $consulta;
                    }

                    $this->assertSame(
                        'DELETE FROM livro WHERE codigo = ?',
                        $sql
                    );

                    return $exclusao;
                }
            );

        $repository = new LivroRepository($conn);

        $this->assertTrue($repository->excluir(42));
    }
}