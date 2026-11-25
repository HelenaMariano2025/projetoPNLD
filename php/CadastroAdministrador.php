<?php

class CadastroAdministrador
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function cadastrar($matricula, $nome, $senha)
    {
        $erro = $this->validar($matricula, $nome, $senha);

        if ($erro !== null) {
            return $erro;
        }

        if ($this->repository->inserir($matricula, $nome, $senha)) {
            return "Administrador cadastrado com sucesso.";
        }

        return "Não foi possível cadastrar o administrador.";
    }

    private function validar($matricula, $nome, $senha): ?string
    {
        $erros = [
            "A matrícula é obrigatória." => trim($matricula) === "",
            "O nome é obrigatório." => trim($nome) === "",
            "A senha é obrigatória." => $senha === "",
        ];

        return array_search(true, $erros, true) ?: null;
    }
}
