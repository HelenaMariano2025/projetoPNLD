<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AlunoRepository.php';

class AlunoRepositoryIntegrationTest extends TestCase
{
    private $conn;

    protected function setUp(): void
{
    $this->conn = new mysqli(
        getenv('DB_HOST') ?: 'db',
        getenv('DB_USER') ?: 'pnld',
        getenv('DB_PASSWORD') ?: 'pnld_local',
        getenv('DB_NAME') ?: 'SistemaHBL'
    );

    if ($this->conn->connect_error) {
        $this->fail('Não foi possível conectar ao banco: ' . $this->conn->connect_error);
    }
}

    protected function tearDown(): void
    {
        $this->conn->close();
    }

    public function testConsultarAlunosAtivosNoBancoReal()
    {
        $repository = new AlunoRepository($this->conn);

        $resultado = $repository->consultarAtivos();

        $this->assertInstanceOf(mysqli_result::class, $resultado);
    }
}