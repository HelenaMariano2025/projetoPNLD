<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AlunoRepository.php';

class AlunoRepositoryIntegrationTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
            'db',
            'pnld',
            'pnld_local',
            'SistemaHBL'
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