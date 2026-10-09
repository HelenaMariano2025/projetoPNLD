<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php/LivroValidator.php';

class LivroValidatorTest extends TestCase
{
    private function dadosValidos(): array
    {
        return [
            'isbn' => '9788535914849',
            'titulo' => 'Livro de teste',
            'autor' => 'Autora de teste',
            'codigo_editora' => '1',
            'ano' => '2024',
            'situacao' => 'ativo',
            'edicao' => '1',
            'qtde_disponivel' => '5',
        ];
    }

    public function testAceitarDadosValidos(): void
    {
        $this->assertNull(LivroValidator::validar($this->dadosValidos()));
    }

    public function testAceitarQuantidadeZero(): void
    {
        $dados = $this->dadosValidos();
        $dados['qtde_disponivel'] = '0';

        $this->assertNull(LivroValidator::validar($dados));
    }

    public function testRejeitarQuantidadeNegativa(): void
    {
        $dados = $this->dadosValidos();
        $dados['qtde_disponivel'] = '-1';

        $this->assertNotNull(LivroValidator::validar($dados));
    }

    public function testRejeitarNumeroInvalidoAntesDaConversao(): void
    {
        $dados = $this->dadosValidos();
        $dados['ano'] = 'texto';

        $this->assertNotNull(LivroValidator::validar($dados));
    }

    public function testRejeitarTituloEmBranco(): void
    {
        $dados = $this->dadosValidos();
        $dados['titulo'] = '   ';

        $this->assertNotNull(LivroValidator::validar($dados));
    }

    public function testRejeitarCampoAusente(): void
    {
        $dados = $this->dadosValidos();
        unset($dados['autor']);

        $this->assertNotNull(LivroValidator::validar($dados));
    }

    public function testRejeitarSituacaoInvalida(): void
    {
        $dados = $this->dadosValidos();
        $dados['situacao'] = 'qualquer';

        $this->assertNotNull(LivroValidator::validar($dados));
    }

    public function testRejeitarCampoRecebidoComoArray(): void
    {
        $dados = $this->dadosValidos();
        $dados['isbn'] = ['9788535914849'];

        $this->assertNotNull(LivroValidator::validar($dados));
    }
}
