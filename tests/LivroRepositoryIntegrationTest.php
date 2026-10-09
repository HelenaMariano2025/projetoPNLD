<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/LivroRepository.php';

class LivroRepositoryIntegrationTest extends TestCase
{
    public function testOperacoesDoLivroComBancoReal(): void
    {
        $conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $conn->begin_transaction();

        try {
            $repository = new LivroRepository($conn);
            $isbn = (string) random_int(1000000000000, 9999999999999);
            $titulo = 'Livro teste integração ' . $isbn;

            $this->assertTrue($repository->inserir(
                $isbn,
                $titulo,
                'Autora de teste',
                1,
                2024,
                'ativo',
                1,
                5
            ));

            $codigo = $conn->insert_id;
            $this->assertGreaterThan(0, $codigo);

            $consulta = $repository->consultarTodos($titulo);
            $this->assertInstanceOf(mysqli_result::class, $consulta);

            $livroCadastrado = $consulta->fetch_assoc();
            $this->assertNotNull($livroCadastrado);
            $this->assertSame($isbn, (string) $livroCadastrado['isbn']);
            $this->assertSame($titulo, $livroCadastrado['titulo']);
            $this->assertSame('Autora de teste', $livroCadastrado['autor']);
            $this->assertSame(1, (int) $livroCadastrado['codigo_editora']);
            $this->assertSame(2024, (int) $livroCadastrado['ano']);
            $this->assertSame('ativo', $livroCadastrado['situacao']);
            $this->assertSame(1, (int) $livroCadastrado['edicao']);
            $this->assertSame(5, (int) $livroCadastrado['qtde_disponivel']);

            $tituloAtualizado = 'Título atualizado ' . $isbn;

            $this->assertTrue($repository->atualizar(
                $codigo,
                $isbn,
                $tituloAtualizado,
                'Autora atualizada',
                2,
                2025,
                'inativo',
                2,
                0
            ));

            $consultaAtualizada = $repository->consultarTodos($tituloAtualizado);
            $this->assertInstanceOf(mysqli_result::class, $consultaAtualizada);

            $livroAtualizado = $consultaAtualizada->fetch_assoc();
            $this->assertNotNull($livroAtualizado);
            $this->assertSame($tituloAtualizado, $livroAtualizado['titulo']);
            $this->assertSame('Autora atualizada', $livroAtualizado['autor']);
            $this->assertSame(2, (int) $livroAtualizado['codigo_editora']);
            $this->assertSame(2025, (int) $livroAtualizado['ano']);
            $this->assertSame('inativo', $livroAtualizado['situacao']);
            $this->assertSame(2, (int) $livroAtualizado['edicao']);
            $this->assertSame(0, (int) $livroAtualizado['qtde_disponivel']);

            $this->assertTrue($repository->excluir($codigo));

            $stmt = $conn->prepare(
                'SELECT codigo FROM livro WHERE codigo = ?'
            );
            $stmt->bind_param('i', $codigo);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $this->assertInstanceOf(mysqli_result::class, $resultado);
            $this->assertSame(0, $resultado->num_rows);

            $consultaAposExclusao = $repository->consultarTodos($tituloAtualizado);
            $this->assertInstanceOf(mysqli_result::class, $consultaAposExclusao);
            $this->assertSame(0, $consultaAposExclusao->num_rows);
        } finally {
            $conn->rollback();
            $conn->close();
        }
    }
}