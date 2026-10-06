<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/CadastroAdministradorRepository.php';

class CadastroAdministradorRepositoryTest extends TestCase
{
    public function testInserirAdministrador()
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
             ->with(
                 "INSERT INTO administrador
                (matricula, nome, senha)
                VALUES (?, ?, ?)"
             )
             ->willReturn($stmt);

        $repository = new CadastroAdministradorRepository($conn);

        $resultado = $repository->inserir(
            123456,
            'João da Silva',
            '123456'
        );

        $this->assertTrue($resultado);
    }
}
