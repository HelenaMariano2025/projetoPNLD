
<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/TurmaRepository.php';

class TurmaRepositoryIntegrationTest extends TestCase
{
    private $conn;
    private $repository;

    protected function setUp(): void
    {
        $servername = getenv("DB_HOST") ?: "localhost";
        $username = getenv("DB_USER") ?: "pnld";
        $password = getenv("DB_PASSWORD") ?: "123456";
        $dbname = getenv("DB_NAME") ?: "SistemaHBL";

        $this->conn = new mysqli(
            $servername,
            $username,
            $password,
            $dbname
        );

        $this->repository = new TurmaRepository($this->conn);

        $this->conn->query("DELETE FROM turma WHERE codigo = 9999");
    }

    public function testInserirEConsultarTurma()
    {
        $resultado = $this->repository->inserir(
            9999,
            'Teste Integracao',
            'TESTE',
            1,
            1,
            'Matriz Teste',
            'ativo'
        );

        $this->assertTrue($resultado);

        $resultadoConsulta = $this->repository->consultarAtivos();

        $encontrou = false;

        while ($turma = $resultadoConsulta->fetch_assoc()) {
            if ($turma['codigo'] == 9999) {
                $encontrou = true;
                break;
            }
        }

        $this->assertTrue($encontrou);
    }

    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM turma WHERE codigo = 9999");
        $this->conn->close();
    }
}
