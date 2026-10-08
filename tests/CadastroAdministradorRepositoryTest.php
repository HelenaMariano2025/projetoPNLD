<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/CadastroAdministrador.php';
require_once __DIR__ . '/../php/CadastroAdministradorRepository.php';

class CadastroAdministradorRepositoryTest extends TestCase
{
    public function testCadastraAdministradorComDadosValidos()
    {
        $repository = $this->createMock(CadastroAdministradorRepository::class);

        $repository
            ->expects($this->once())
            ->method('inserir')
            ->with(
                '123456',
                'Administrador Teste',
                'senha123'
            )
            ->willReturn(true);

        $cadastro = new CadastroAdministrador($repository);

        $resultado = $cadastro->cadastrar(
            '123456',
            'Administrador Teste',
            'senha123'
        );

        $this->assertEquals(
            'Administrador cadastrado com sucesso.',
            $resultado
        );
    }

    public function testNaoCadastraSemMatricula()
    {
        $repository = $this->createMock(CadastroAdministradorRepository::class);

        $repository
            ->expects($this->never())
            ->method('inserir');

        $cadastro = new CadastroAdministrador($repository);

        $resultado = $cadastro->cadastrar(
            '',
            'Administrador Teste',
            'senha123'
        );

        $this->assertEquals(
            'A matrícula é obrigatória.',
            $resultado
        );
    }

    public function testNaoCadastraSemNome()
    {
        $repository = $this->createMock(CadastroAdministradorRepository::class);

        $repository
            ->expects($this->never())
            ->method('inserir');

        $cadastro = new CadastroAdministrador($repository);

        $resultado = $cadastro->cadastrar(
            '123456',
            '',
            'senha123'
        );

        $this->assertEquals(
            'O nome é obrigatório.',
            $resultado
        );
    }

    public function testNaoCadastraSemSenha()
    {
        $repository = $this->createMock(CadastroAdministradorRepository::class);

        $repository
            ->expects($this->never())
            ->method('inserir');

        $cadastro = new CadastroAdministrador($repository);

        $resultado = $cadastro->cadastrar(
            '123456',
            'Administrador Teste',
            ''
        );

        $this->assertEquals(
            'A senha é obrigatória.',
            $resultado
        );
    }

    public function testRetornaErroQuandoRepositorioNaoConsegueCadastrar()
    {
        $repository = $this->createMock(CadastroAdministradorRepository::class);

        $repository
            ->expects($this->once())
            ->method('inserir')
            ->with(
                '123456',
                'Administrador Teste',
                'senha123'
            )
            ->willReturn(false);

        $cadastro = new CadastroAdministrador($repository);

        $resultado = $cadastro->cadastrar(
            '123456',
            'Administrador Teste',
            'senha123'
        );

        $this->assertEquals(
            'Não foi possível cadastrar o administrador.',
            $resultado
        );
    }
}