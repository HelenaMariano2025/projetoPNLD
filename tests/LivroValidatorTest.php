<?php

use PHPUnit\Framework\Attributes\DataProvider;
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

    private const MSG_TEXTOS = 'Preencha ISBN, título e autor.';
    private const MSG_TAMANHO = 'Título e autor devem ter até 100 caracteres.';
    private const MSG_ISBN = 'Informe um ISBN numérico válido.';
    private const MSG_NUMEROS = 'Código da editora, ano e edição devem ser inteiros positivos.';
    private const MSG_QTDE = 'A quantidade disponível deve ser um inteiro maior ou igual a zero.';
    private const MSG_SITUACAO = 'Selecione uma situação válida.';

    // ---------- ISBN (mata mutantes 42, 53-57) ----------

    public function testAceitarIsbnComEspacosNasPontas(): void
    {
        $dados = $this->dadosValidos();
        $dados['isbn'] = ' 9788535914849 ';

        $this->assertNull(LivroValidator::validar($dados));
    }

    public function testAceitarIsbnMinimoIgualAUm(): void
    {
        $dados = $this->dadosValidos();
        $dados['isbn'] = '1';

        $this->assertNull(LivroValidator::validar($dados));
    }

    #[DataProvider('isbnsInvalidos')]
    public function testRejeitarIsbnInvalido(string $isbn): void
    {
        $dados = $this->dadosValidos();
        $dados['isbn'] = $isbn;

        $this->assertSame(self::MSG_ISBN, LivroValidator::validar($dados));
    }

    public static function isbnsInvalidos(): iterable
    {
        yield 'zero' => ['0'];
        yield 'com sinal de mais' => ['+5'];
        yield 'com sinal de menos' => ['-5'];
        yield 'letras' => ['abc'];
    }

    // ---------- Textos (mata mutantes 45-52) ----------

    #[DataProvider('camposTextoObrigatorios')]
    public function testCampoSoComEspacosEhRejeitadoComMensagemDePreenchimento(string $campo): void
    {
        $dados = $this->dadosValidos();
        $dados[$campo] = '   ';

        $this->assertSame(self::MSG_TEXTOS, LivroValidator::validar($dados));
    }

    public static function camposTextoObrigatorios(): iterable
    {
        yield 'isbn' => ['isbn'];
        yield 'titulo' => ['titulo'];
        yield 'autor' => ['autor'];
    }

    #[DataProvider('camposComLimiteDeTamanho')]
    public function testRejeitarTextoCom101Caracteres(string $campo): void
    {
        $dados = $this->dadosValidos();
        $dados[$campo] = str_repeat('a', 101);

        $this->assertSame(self::MSG_TAMANHO, LivroValidator::validar($dados));
    }

    #[DataProvider('camposComLimiteDeTamanho')]
    public function testAceitarTextoCom100Caracteres(string $campo): void
    {
        $dados = $this->dadosValidos();
        $dados[$campo] = str_repeat('a', 100);

        $this->assertNull(LivroValidator::validar($dados));
    }

    public static function camposComLimiteDeTamanho(): iterable
    {
        yield 'titulo' => ['titulo'];
        yield 'autor' => ['autor'];
    }

    public function testContarCaracteresEmVezDeBytes(): void
    {
        $dados = $this->dadosValidos();
        $dados['titulo'] = str_repeat('á', 100);

        $this->assertNull(LivroValidator::validar($dados));
    }

    public function testIgnorarEspacosNasPontasAoContarCaracteres(): void
    {
        $dados = $this->dadosValidos();
        $dados['titulo'] = ' ' . str_repeat('a', 100) . ' ';

        $this->assertNull(LivroValidator::validar($dados));
    }

    public function testAceitarQuebraDeLinhaNoMeioDoTitulo(): void
    {
        $dados = $this->dadosValidos();
        $dados['titulo'] = "Linha 1\nLinha 2";

        $this->assertNull(LivroValidator::validar($dados));
    }

    // ---------- Números (mata mutantes 58-63) ----------

    #[DataProvider('camposNumericosInvalidos')]
    public function testRejeitarCampoNumericoInvalido(string $campo, string $valor): void
    {
        $dados = $this->dadosValidos();
        $dados[$campo] = $valor;

        $this->assertSame(self::MSG_NUMEROS, LivroValidator::validar($dados));
    }

    public static function camposNumericosInvalidos(): iterable
    {
        foreach (['codigo_editora', 'ano', 'edicao'] as $campo) {
            foreach (['0', '-1', 'texto'] as $valor) {
                yield "$campo = $valor" => [$campo, $valor];
            }
        }
    }

    public function testAceitarLimiteMaximoDeInteiro(): void
    {
        $dados = $this->dadosValidos();
        $dados['ano'] = '2147483647';
        $dados['qtde_disponivel'] = '2147483647';

        $this->assertNull(LivroValidator::validar($dados));
    }

    public function testRejeitarValorAcimaDoLimiteMaximo(): void
    {
        $dados = $this->dadosValidos();
        $dados['ano'] = '2147483648';

        $this->assertSame(self::MSG_NUMEROS, LivroValidator::validar($dados));
    }

    public function testAceitarNumerosRecebidosComoInteiro(): void
    {
        $dados = $this->dadosValidos();
        $dados['ano'] = 2024;
        $dados['qtde_disponivel'] = 5;

        $this->assertNull(LivroValidator::validar($dados));
    }

    #[DataProvider('tiposNumericosInvalidos')]
    public function testRejeitarNumeroComTipoInvalido(mixed $valor): void
    {
        $dados = $this->dadosValidos();
        $dados['ano'] = $valor;

        $this->assertSame(self::MSG_NUMEROS, LivroValidator::validar($dados));
    }

    public static function tiposNumericosInvalidos(): iterable
    {
        yield 'float inteiro' => [2024.0];
        yield 'booleano' => [true];
        yield 'nulo' => [null];
        yield 'array' => [[2024]];
    }

    public function testRejeitarQuantidadeNegativaComMensagem(): void
    {
        $dados = $this->dadosValidos();
        $dados['qtde_disponivel'] = '-1';

        $this->assertSame(self::MSG_QTDE, LivroValidator::validar($dados));
    }

    public function testRejeitarQuantidadeAusente(): void
    {
        $dados = $this->dadosValidos();
        unset($dados['qtde_disponivel']);

        $this->assertSame(self::MSG_QTDE, LivroValidator::validar($dados));
    }

    // ---------- Situação e ordem das validações (mata 43 e 44) ----------

    public function testAceitarSituacaoInativo(): void
    {
        $dados = $this->dadosValidos();
        $dados['situacao'] = 'inativo';

        $this->assertNull(LivroValidator::validar($dados));
    }

    public function testRejeitarSituacaoAusenteComMensagem(): void
    {
        $dados = $this->dadosValidos();
        unset($dados['situacao']);

        $this->assertSame(self::MSG_SITUACAO, LivroValidator::validar($dados));
    }

    public function testValidaIsbnAntesDosNumeros(): void
    {
        $dados = $this->dadosValidos();
        $dados['isbn'] = 'abc';
        $dados['ano'] = 'texto';

        $this->assertSame(self::MSG_ISBN, LivroValidator::validar($dados));
    }

    public function testValidaNumerosAntesDaSituacao(): void
    {
        $dados = $this->dadosValidos();
        $dados['ano'] = 'texto';
        $dados['situacao'] = 'qualquer';

        $this->assertSame(self::MSG_NUMEROS, LivroValidator::validar($dados));
    }
}
