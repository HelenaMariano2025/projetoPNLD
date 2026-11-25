<?php

class AlunoValidador
{
    private const CAMPOS_OBRIGATORIOS = [
        'nome',
        'datnasc',
        'endereco',
        'sexo',
        'email',
        'codigoTurma',
    ];

    public static function validarMatricula(string $matricula): ?string
    {
        $valida = preg_match('/^\d{1,18}$/D', $matricula) === 1;

        return $valida
            ? null
            : 'Informe uma matrícula numérica válida, com até 18 dígitos.';
    }

    public static function validarDados(array $dados): ?string
    {
        return self::validarObrigatorios($dados)
            ?? self::validarEmail($dados['email'])
            ?? self::validarDataNascimento($dados['datnasc'])
            ?? self::validarSexo($dados['sexo'])
            ?? self::validarSituacao($dados['situacao'] ?? '')
            ?? self::validarCodigoTurma($dados['codigoTurma']);
    }

    private static function validarObrigatorios(array $dados): ?string
    {
        foreach (self::CAMPOS_OBRIGATORIOS as $campo) {
            if (($dados[$campo] ?? '') === '') {
                return 'Preencha todos os campos obrigatórios.';
            }
        }

        return null;
    }

    private static function validarEmail(string $email): ?string
    {
        $valido = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

        return $valido ? null : 'Informe um e-mail válido.';
    }

    private static function validarDataNascimento(string $data): ?string
    {
        $objeto = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
        $valida = $objeto !== false && $objeto->format('Y-m-d') === $data;

        return $valida ? null : 'Informe uma data de nascimento válida.';
    }

    private static function validarSexo(string $sexo): ?string
    {
        $valido = in_array($sexo, ['M', 'F'], true);

        return $valido ? null : 'Selecione uma opção válida para sexo.';
    }

    private static function validarSituacao(string $situacao): ?string
    {
        $valida = in_array($situacao, ['ativo', 'inativo'], true);

        return $valida ? null : 'Selecione uma situação válida.';
    }

    private static function validarCodigoTurma(string $codigo): ?string
    {
        $valido = ctype_digit($codigo)
            && strlen($codigo) <= 10
            && (int) $codigo <= 2147483647;

        return $valido ? null : 'Selecione uma turma válida.';
    }
}
