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
        if (trim($matricula) === "") {
            return "A matrícula é obrigatória.";
        }

        if (trim($nome) === "") {
            return "O nome é obrigatório.";
        }

        if ($senha === "") {
            return "A senha é obrigatória.";
        }

        if ($this->repository->inserir($matricula, $nome, $senha)) {
            return "Administrador cadastrado com sucesso.";
        }

        return "Não foi possível cadastrar o administrador.";
    }
}