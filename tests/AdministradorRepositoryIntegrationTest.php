<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AdministradorRepository.php';

class AdministradorRepositoryIntegrationTest extends TestCase
{
    private mysqli $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );

        if ($this->conn->connect_error) {
            $this->fail(
                'Falha na conexão com o banco: ' . $this->conn->connect_error
            );
        }
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testBuscaAdministradorCadastradoNoBanco()
    {
        $repository = new AdministradorRepository($this->conn);

        $administrador = $repository->buscarPorMatricula(1234567890);

        $this->assertNotNull($administrador);
        $this->assertSame(1234567890, (int) $administrador['matricula']);
    }
}