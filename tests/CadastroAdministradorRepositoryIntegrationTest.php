<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/CadastroAdministradorRepository.php';

class CadastroAdministradorRepositoryIntegrationTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli(
    	getenv('DB_HOST') ?: 'localhost',
    	getenv('DB_USER') ?: 'pnld',
    	getenv('DB_PASSWORD') ?: '123456',
    	getenv('DB_NAME') ?: 'SistemaHBL'
	);

        if ($this->conn->connect_error) {
            $this->fail('Não foi possível conectar ao banco: ' . $this->conn->connect_error);
        }
    }

    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM administrador WHERE matricula = 999999");
        $this->conn->close();
    }

    public function testInserirAdministradorNoBancoReal()
    {
        $repository = new CadastroAdministradorRepository($this->conn);

        $resultado = $repository->inserir(
            999999,
            'Administrador Teste',
            '123456'
        );

        $this->assertTrue($resultado);

        $consulta = $this->conn->query(
            "SELECT * FROM administrador WHERE matricula = 999999"
        );

        $this->assertEquals(1, $consulta->num_rows);
    }
}
