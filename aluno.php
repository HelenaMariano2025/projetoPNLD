<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/AlunoRepository.php';

$conn->set_charset('utf8mb4');

$repository = new AlunoRepository($conn);

function escapar($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

if (!isset($_SESSION['csrf_alunos'])) {
    $_SESSION['csrf_alunos'] = bin2hex(random_bytes(32));
}

$erro = '';
$alunoEdicao = null;

$dados = [
    'matricula' => '',
    'nome' => '',
    'datnasc' => '',
    'endereco' => '',
    'sexo' => 'F',
    'email' => '',
    'situacao' => 'ativo',
    'codigoTurma' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = is_string($_POST['acao'] ?? null)
        ? $_POST['acao']
        : '';

    try {
        $token = $_POST['csrf'] ?? '';

        if (
            !is_string($token) ||
            !hash_equals($_SESSION['csrf_alunos'], $token)
        ) {
            throw new InvalidArgumentException(
                'Formulário inválido. Atualize a página e tente novamente.'
            );
        }

        foreach ($dados as $campo => $valor) {
            $dados[$campo] = is_string($_POST[$campo] ?? null)
                ? trim($_POST[$campo])
                : '';
        }

        if (!preg_match('/^[0-9]{1,18}$/', $dados['matricula'])) {
            throw new InvalidArgumentException(
                'Informe uma matrícula numérica válida, com até 18 dígitos.'
            );
        }

        if ($acao === 'inativar') {
            if (!$repository->consultarPorMatricula($dados['matricula'])) {
                throw new InvalidArgumentException('Aluno não encontrado.');
            }

            if (!$repository->inativar($dados['matricula'])) {
                throw new RuntimeException('Não foi possível inativar o aluno.');
            }

            $_SESSION['mensagem_alunos'] = 'Aluno inativado com sucesso!';

            header('Location: aluno.php');
            exit;
        }

        if (!in_array($acao, ['cadastrar', 'atualizar'], true)) {
            throw new InvalidArgumentException('Operação inválida.');
        }

        foreach (
            ['nome', 'datnasc', 'endereco', 'sexo', 'email', 'codigoTurma']
            as $campo
        ) {
            if ($dados[$campo] === '') {
                throw new InvalidArgumentException(
                    'Preencha todos os campos obrigatórios.'
                );
            }
        }

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Informe um e-mail válido.');
        }

        $data = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $dados['datnasc']
        );

        if (!$data || $data->format('Y-m-d') !== $dados['datnasc']) {
            throw new InvalidArgumentException(
                'Informe uma data de nascimento válida.'
            );
        }

        if (!in_array($dados['sexo'], ['M', 'F'], true)) {
            throw new InvalidArgumentException(
                'Selecione uma opção válida para sexo.'
            );
        }

        if (!in_array($dados['situacao'], ['ativo', 'inativo'], true)) {
            throw new InvalidArgumentException('Selecione uma situação válida.');
        }

        if (
            !ctype_digit($dados['codigoTurma']) ||
            strlen($dados['codigoTurma']) > 10 ||
            (int) $dados['codigoTurma'] > 2147483647
        ) {
            throw new InvalidArgumentException('Selecione uma turma válida.');
        }

        $stmt = $conn->prepare(
            'SELECT codigo FROM turma WHERE codigo = ?'
        );
        $stmt->bind_param('s', $dados['codigoTurma']);
        $stmt->execute();

        if (!$stmt->get_result()->fetch_assoc()) {
            throw new InvalidArgumentException(
                'A turma selecionada não existe.'
            );
        }

        $existente = $repository->consultarPorMatricula($dados['matricula']);

        if ($acao === 'cadastrar' && $existente) {
            throw new InvalidArgumentException(
                'Já existe um aluno com essa matrícula.'
            );
        }

        if ($acao === 'atualizar' && !$existente) {
            throw new InvalidArgumentException('Aluno não encontrado.');
        }

        $argumentos = [
            $dados['matricula'],
            $dados['nome'],
            $dados['datnasc'],
            $dados['endereco'],
            $dados['sexo'],
            $dados['email'],
            $dados['situacao'],
            $dados['codigoTurma']
        ];

        $salvo = $acao === 'cadastrar'
            ? $repository->inserir(...$argumentos)
            : $repository->atualizar(...$argumentos);

        if (!$salvo) {
            throw new RuntimeException(
                'Não foi possível salvar os dados do aluno.'
            );
        }

        $_SESSION['mensagem_alunos'] = $acao === 'cadastrar'
            ? 'Aluno cadastrado com sucesso!'
            : 'Dados do aluno atualizados com sucesso!';

        header('Location: aluno.php');
        exit;
    } catch (InvalidArgumentException | RuntimeException $e) {
        $erro = $e instanceof mysqli_sql_exception
            ? 'Não foi possível concluir a operação. Confira os dados informados.'
            : $e->getMessage();
    }

    if ($acao === 'atualizar') {
        $alunoEdicao = $dados;
    }
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' &&
    isset($_GET['editar'])
) {
    $matricula = is_string($_GET['editar'])
        ? $_GET['editar']
        : '';

    if (preg_match('/^[0-9]{1,18}$/', $matricula)) {
        $alunoEdicao = $repository->consultarPorMatricula($matricula);

        if ($alunoEdicao) {
            $dados = $alunoEdicao;
        } else {
            $erro = 'Aluno não encontrado.';
        }
    } else {
        $erro = 'Matrícula inválida.';
    }
}

$turmas = $conn->query(
    'SELECT codigo, curso, periodo, serie FROM turma ORDER BY codigo'
)->fetch_all(MYSQLI_ASSOC);

// Associa o curso à turma para manter a informação exibida no HTML.
$cursosPorTurma = [];

foreach ($turmas as $turma) {
    $cursosPorTurma[$turma['codigo']] = $turma['curso'];
}

$pesquisa = is_string($_GET['nome'] ?? null)
    ? trim($_GET['nome'])
    : '';

$resultado = $pesquisa !== ''
    ? $repository->pesquisarPorNome($pesquisa)
    : $repository->consultarAtivos();

$alunos = $resultado->fetch_all(MYSQLI_ASSOC);

foreach ($alunos as $indice => $aluno) {
    $alunos[$indice]['curso'] =
        $cursosPorTurma[$aluno['codigoTurma']] ?? '';
}

$mensagem = $_SESSION['mensagem_alunos'] ?? '';
unset($_SESSION['mensagem_alunos']);
?>