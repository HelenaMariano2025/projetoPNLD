<?php

class LivroValidator
{
    public static function validar(array $dados): ?string
    {
        foreach (['isbn', 'titulo', 'autor'] as $campo) {
            if (
                !isset($dados[$campo])
                || !is_string($dados[$campo])
                || trim($dados[$campo]) === ''
            ) {
                return 'Preencha ISBN, título e autor.';
            }
        }

        foreach (['titulo', 'autor'] as $campo) {
            if (preg_match('/^.{1,100}$/us', trim($dados[$campo])) !== 1) {
                return 'Título e autor devem ter até 100 caracteres.';
            }
        }

        $isbn = trim($dados['isbn']);

        if (
            !ctype_digit($isbn)
            || filter_var(
                $isbn,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            ) === false
        ) {
            return 'Informe um ISBN numérico válido.';
        }

        foreach (['codigo_editora', 'ano', 'edicao'] as $campo) {
            if (!self::inteiroValido($dados[$campo] ?? null, 1)) {
                return 'Código da editora, ano e edição devem ser inteiros positivos.';
            }
        }

        if (!self::inteiroValido($dados['qtde_disponivel'] ?? null, 0)) {
            return 'A quantidade disponível deve ser um inteiro maior ou igual a zero.';
        }

        if (!in_array($dados['situacao'] ?? null, ['ativo', 'inativo'], true)) {
            return 'Selecione uma situação válida.';
        }

        return null;
    }

    private static function inteiroValido(mixed $valor, int $minimo): bool
    {
        if (!is_string($valor) && !is_int($valor)) {
            return false;
        }

        return filter_var(
            $valor,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => $minimo,
                    'max_range' => 2147483647,
                ],
            ]
        ) !== false;
    }
}