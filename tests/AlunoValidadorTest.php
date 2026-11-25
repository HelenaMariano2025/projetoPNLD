<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/AlunoValidador.php';

class AlunoValidadorTest extends TestCase
{
    private function dadosValidos(): array
    {
        return [
            'matricula' => '20260001',
            'nome' => 'Maria Souza',
            'datnasc' => '2008-05-17',
            'endereco' => 'Rua das Flores, 10',
            'sexo' => 'F',
            'email' => 'maria@exemplo.com',
            'situacao' => 'ativo',
            'codigoTurma' => '1',
        ];
    }

    public function testAceitarMatriculaNumerica(): void
    {
        $this->assertNull(AlunoValidador::validarMatricula('20260001'));
    }

    #[DataProvider('matriculasInvalidas')]
    public function testRejeitarMatriculaInvalida(string $matricula): void
    {
        $this->assertSame(
            'Informe uma matrícula numérica válida, com até 18 dígitos.',
            AlunoValidador::validarMatricula($matricula)
        );
    }

    public static function matriculasInvalidas(): array
    {
        return [
            'vazia' => [''],
            'com letras' => ['12ab'],
            'negativa' => ['-1'],
            'com espaço' => ['12 34'],
            'quebra de linha no fim' => ["123\n"],
            'mais de 18 digitos' => ['1234567890123456789'],
        ];
    }

    public function testAceitarDadosValidos(): void
    {
        $this->assertNull(AlunoValidador::validarDados($this->dadosValidos()));
    }

    #[DataProvider('camposObrigatorios')]
    public function testRejeitarCampoObrigatorioVazio(string $campo): void
    {
        $dados = $this->dadosValidos();
        $dados[$campo] = '';

        $this->assertSame(
            'Preencha todos os campos obrigatórios.',
            AlunoValidador::validarDados($dados)
        );
    }

    public static function camposObrigatorios(): array
    {
        return [
            'nome' => ['nome'],
            'data de nascimento' => ['datnasc'],
            'endereco' => ['endereco'],
            'sexo' => ['sexo'],
            'email' => ['email'],
            'turma' => ['codigoTurma'],
        ];
    }

    public function testRejeitarEmailInvalido(): void
    {
        $dados = $this->dadosValidos();
        $dados['email'] = 'maria@';

        $this->assertSame(
            'Informe um e-mail válido.',
            AlunoValidador::validarDados($dados)
        );
    }

    #[DataProvider('datasInvalidas')]
    public function testRejeitarDataDeNascimentoInvalida(string $data): void
    {
        $dados = $this->dadosValidos();
        $dados['datnasc'] = $data;

        $this->assertSame(
            'Informe uma data de nascimento válida.',
            AlunoValidador::validarDados($dados)
        );
    }

    public static function datasInvalidas(): array
    {
        return [
            'texto' => ['texto'],
            'dia inexistente' => ['2008-02-30'],
            'formato brasileiro' => ['17/05/2008'],
        ];
    }

    public function testRejeitarSexoInvalido(): void
    {
        $dados = $this->dadosValidos();
        $dados['sexo'] = 'X';

        $this->assertSame(
            'Selecione uma opção válida para sexo.',
            AlunoValidador::validarDados($dados)
        );
    }

    public function testAceitarSituacaoInativo(): void
    {
        $dados = $this->dadosValidos();
        $dados['situacao'] = 'inativo';

        $this->assertNull(AlunoValidador::validarDados($dados));
    }

    public function testRejeitarSituacaoInvalidaOuAusente(): void
    {
        $dados = $this->dadosValidos();
        $dados['situacao'] = 'pendente';

        $this->assertSame(
            'Selecione uma situação válida.',
            AlunoValidador::validarDados($dados)
        );

        unset($dados['situacao']);

        $this->assertSame(
            'Selecione uma situação válida.',
            AlunoValidador::validarDados($dados)
        );
    }

    #[DataProvider('turmasInvalidas')]
    public function testRejeitarCodigoDeTurmaInvalido(string $codigo): void
    {
        $dados = $this->dadosValidos();
        $dados['codigoTurma'] = $codigo;

        $this->assertSame(
            'Selecione uma turma válida.',
            AlunoValidador::validarDados($dados)
        );
    }

    public static function turmasInvalidas(): array
    {
        return [
            'letras' => ['abc'],
            'negativo' => ['-1'],
            'mais de 10 digitos' => ['12345678901'],
            'acima do limite do inteiro' => ['2147483648'],
        ];
    }

    public function testAceitarCodigoDeTurmaNoLimiteDoInteiro(): void
    {
        $dados = $this->dadosValidos();
        $dados['codigoTurma'] = '2147483647';

        $this->assertNull(AlunoValidador::validarDados($dados));
    }

    public function testMostrarPrimeiroErroQuandoHaVarios(): void
    {
        $dados = $this->dadosValidos();
        $dados['nome'] = '';
        $dados['email'] = 'invalido';

        $this->assertSame(
            'Preencha todos os campos obrigatórios.',
            AlunoValidador::validarDados($dados)
        );
    }
}