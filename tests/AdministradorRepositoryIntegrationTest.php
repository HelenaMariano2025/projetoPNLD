<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AdministradorRepository.php';

class AdministradorRepositoryIntegrationTest extends TestCase
{
    public function testBuscaAdministradorCadastradoNoBanco(): void
    {
        $conn = new mysqli(
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_USER') ?: 'pnld',
            getenv('DB_PASSWORD') ?: '123456',
            getenv('DB_NAME') ?: 'SistemaHBL'
        );

        $conn->begin_transaction();

        try {
            $matricula = random_int(100000000000, 999999999999);
            $nome = 'Administrador de teste';
            $senha = 'senhaTeste123';

            $stmt = $conn->prepare(
                'INSERT INTO administrador (matricula, nome, senha)
                 VALUES (?, ?, ?)'
            );
            $stmt->bind_param('iss', $matricula, $nome, $senha);

            $this->assertTrue($stmt->execute());
            $stmt->close();

            $repository = new AdministradorRepository($conn);
            $administrador = $repository->buscarPorMatricula($matricula);

            $this->assertNotNull($administrador);
            $this->assertSame(
                $matricula,
                (int) $administrador['matricula']
            );
        } finally {
            $conn->rollback();
            $conn->close();
        }
    }
}