<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/regras.php';
require_once __DIR__ . '/../php/AdministradorRepository.php';

class LoginTest extends TestCase
{
    public function testNaoPermiteLoginComCamposVazios()
    {
        $matricula = '';
        $senha = '';

        $this->assertFalse(validarCampoObrigatorio($matricula));
        $this->assertFalse(validarCampoObrigatorio($senha));
    }

    public function testAutenticaAdministradorComCredenciaisValidas()
    {
        $repository = $this->createStub(AdministradorRepository::class);

        $repository
            ->method('buscarPorMatricula')
            ->willReturn([
                'matricula' => 20250101,
                'nome' => 'Jaine Luz',
                'senha' => '123456'
            ]);

        $resultado = autenticarAdministrador(
            20250101,
            '123456',
            $repository
        );

        $this->assertNotNull($resultado);
        $this->assertSame('Jaine Luz', $resultado['nome']);
    }

    public function testNaoAutenticaComSenhaInvalida()
    {
        $repository = $this->createStub(AdministradorRepository::class);

        $repository
            ->method('buscarPorMatricula')
            ->willReturn([
                'matricula' => 20250101,
                'nome' => 'Jaine Luz',
                'senha' => '123456'
            ]);

        $resultado = autenticarAdministrador(
            20250101,
            'senha-errada',
            $repository
        );

        $this->assertNull($resultado);
    }

    public function testNaoAutenticaMatriculaNaoCadastrada()
    {
        $repository = $this->createStub(AdministradorRepository::class);

        $repository
            ->method('buscarPorMatricula')
            ->willReturn(null);

        $resultado = autenticarAdministrador(
            99999999,
            '123456',
            $repository
        );

        $this->assertNull($resultado);
    }
}