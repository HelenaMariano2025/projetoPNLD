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

            $consulta = $repository->consultarDisponiveis($titulo);
            $this->assertInstanceOf(mysqli_result::class, $consulta);
            $this->assertSame($isbn, (string) $consulta->fetch_assoc()['isbn']);

            $this->assertTrue($repository->atualizar(
                $codigo,
                $isbn,
                'Título atualizado na integração',
                'Autora de teste',
                1,
                2025,
                'ativo',
                2,
                8
            ));

            $stmt = $conn->prepare(
                'SELECT titulo, edicao FROM livro WHERE codigo = ?'
            );
            $stmt->bind_param('i', $codigo);
            $stmt->execute();
            $livroAtualizado = $stmt->get_result()->fetch_assoc();

            $this->assertSame(
                'Título atualizado na integração',
                $livroAtualizado['titulo']
            );
            $this->assertSame(2, (int) $livroAtualizado['edicao']);

            $this->assertTrue($repository->excluir($codigo));

            $stmt = $conn->prepare(
                'SELECT situacao FROM livro WHERE codigo = ?'
            );
            $stmt->bind_param('i', $codigo);
            $stmt->execute();
            $livroExcluido = $stmt->get_result()->fetch_assoc();

            $this->assertSame('inativo', $livroExcluido['situacao']);
        } finally {
            $conn->rollback();
            $conn->close();
        }
    }
}