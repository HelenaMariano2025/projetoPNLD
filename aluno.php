<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once __DIR__ . '/php/conexao.php';

$conn->set_charset('utf8mb4');

function escapar($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function buscarAluno($conn, $matricula)
{
    $stmt = $conn->prepare(
        "SELECT * FROM aluno WHERE matricula = ?"
    );
    $stmt->bind_param("s", $matricula);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

// Token usado para validar as operações enviadas pelo formulário.
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
            if (!buscarAluno($conn, $dados['matricula'])) {
                throw new InvalidArgumentException(
                    'Aluno não encontrado.'
                );
            }

            // Não apaga o aluno nem seus empréstimos e devoluções.
            $stmt = $conn->prepare(
                "UPDATE aluno
                 SET situacao = 'inativo'
                 WHERE matricula = ?"
            );

            $stmt->bind_param("s", $dados['matricula']);
            $stmt->execute();

            $_SESSION['mensagem_alunos'] =
                'Aluno inativado com sucesso!';

            header('Location: aluno.php');
            exit;
        }

        if (!in_array($acao, ['cadastrar', 'atualizar'], true)) {
            throw new InvalidArgumentException(
                'Operação inválida.'
            );
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
            throw new InvalidArgumentException(
                'Informe um e-mail válido.'
            );
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
            throw new InvalidArgumentException(
                'Selecione uma situação válida.'
            );
        }

        if (!ctype_digit($dados['codigoTurma'])) {
            throw new InvalidArgumentException(
                'Selecione uma turma válida.'
            );
        }

        // Confirma que a turma existe.
        $stmt = $conn->prepare(
            "SELECT codigo FROM turma WHERE codigo = ?"
        );
        $stmt->bind_param("s", $dados['codigoTurma']);
        $stmt->execute();

        if (!$stmt->get_result()->fetch_assoc()) {
            throw new InvalidArgumentException(
                'A turma selecionada não existe.'
            );
        }

        if ($acao === 'cadastrar') {
            if (buscarAluno($conn, $dados['matricula'])) {
                throw new InvalidArgumentException(
                    'Já existe um aluno com essa matrícula.'
                );
            }

            $stmt = $conn->prepare(
                "INSERT INTO aluno
                    (matricula, nome, datnasc, endereco, sexo,
                     email, situacao, codigoTurma)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "ssssssss",
                $dados['matricula'],
                $dados['nome'],
                $dados['datnasc'],
                $dados['endereco'],
                $dados['sexo'],
                $dados['email'],
                $dados['situacao'],
                $dados['codigoTurma']
            );

            $stmt->execute();

            $_SESSION['mensagem_alunos'] =
                'Aluno cadastrado com sucesso!';
        } else {
            if (!buscarAluno($conn, $dados['matricula'])) {
                throw new InvalidArgumentException(
                    'Aluno não encontrado.'
                );
            }

            $stmt = $conn->prepare(
                "UPDATE aluno
                 SET nome = ?,
                     datnasc = ?,
                     endereco = ?,
                     sexo = ?,
                     email = ?,
                     situacao = ?,
                     codigoTurma = ?
                 WHERE matricula = ?"
            );

            $stmt->bind_param(
                "ssssssss",
                $dados['nome'],
                $dados['datnasc'],
                $dados['endereco'],
                $dados['sexo'],
                $dados['email'],
                $dados['situacao'],
                $dados['codigoTurma'],
                $dados['matricula']
            );

            $stmt->execute();

            $_SESSION['mensagem_alunos'] =
                'Dados do aluno atualizados com sucesso!';
        }

        header('Location: aluno.php');
        exit;
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    } catch (mysqli_sql_exception $e) {
        $erro = 'Não foi possível concluir a operação. '
              . 'Confira a matrícula e os dados informados.';
    }

    if ($acao === 'atualizar') {
        $alunoEdicao = $dados;
    }
}

// Carrega o aluno quando o botão Editar é acionado.
if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' &&
    isset($_GET['editar'])
) {
    $matricula = is_string($_GET['editar'])
        ? $_GET['editar']
        : '';

    if (preg_match('/^[0-9]{1,18}$/', $matricula)) {
        $alunoEdicao = buscarAluno($conn, $matricula);

        if ($alunoEdicao) {
            $dados = $alunoEdicao;
        } else {
            $erro = 'Aluno não encontrado.';
        }
    } else {
        $erro = 'Matrícula inválida.';
    }
}

// Turmas disponíveis para cadastro e alteração.
$turmas = $conn->query(
    "SELECT codigo, curso, periodo, serie
     FROM turma
     ORDER BY codigo"
)->fetch_all(MYSQLI_ASSOC);

// Pesquisa por nome ou parte dele, somente entre alunos ativos.
$pesquisa = is_string($_GET['nome'] ?? null)
    ? trim($_GET['nome'])
    : '';

if ($pesquisa !== '') {
    $termo = '%' . strtr(
        $pesquisa,
        ['!' => '!!', '%' => '!%', '_' => '!_']
    ) . '%';

    $stmt = $conn->prepare(
        "SELECT a.*, t.curso
         FROM aluno a
         JOIN turma t ON t.codigo = a.codigoTurma
         WHERE a.situacao = 'ativo'
           AND a.nome LIKE ? ESCAPE '!'
         ORDER BY a.nome"
    );

    $stmt->bind_param("s", $termo);
    $stmt->execute();

    $alunos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $alunos = $conn->query(
        "SELECT a.*, t.curso
         FROM aluno a
         JOIN turma t ON t.codigo = a.codigoTurma
         WHERE a.situacao = 'ativo'
         ORDER BY a.nome"
    )->fetch_all(MYSQLI_ASSOC);
}

$mensagem = $_SESSION['mensagem_alunos'] ?? '';
unset($_SESSION['mensagem_alunos']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gerenciar alunos — HBL Control</title>

    <link rel="stylesheet" href="css/bootstrap.css">

    <style>
        body {
            background: #f5f5f5;
        }

        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .painel {
            background: white;
            padding: 24px;
            margin-bottom: 24px;
            border-radius: 8px;
        }

        .campos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .botoes,
        .pesquisa {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .tabela {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }

        .acoes form {
            display: inline-block;
            margin-top: 6px;
        }
    </style>
</head>

<body>
<main>
    <div class="painel">
        <h1>Gerenciar alunos</h1>
        <a href="funcoes.php">Voltar para a página inicial</a>
    </div>

    <?php if ($mensagem !== ''): ?>
        <div class="alert alert-success" role="status">
            <?= escapar($mensagem) ?>
        </div>
    <?php endif; ?>

    <?php if ($erro !== ''): ?>
        <div class="alert alert-danger" role="alert">
            <?= escapar($erro) ?>
        </div>
    <?php endif; ?>

    <section class="painel">
        <h2>
            <?= $alunoEdicao ? 'Editar aluno' : 'Cadastrar aluno' ?>
        </h2>

        <form action="aluno.php" method="post">
            <input
                type="hidden"
                name="csrf"
                value="<?= escapar($_SESSION['csrf_alunos']) ?>"
            >

            <input
                type="hidden"
                name="acao"
                value="<?= $alunoEdicao ? 'atualizar' : 'cadastrar' ?>"
            >

            <div class="campos">
                <div>
                    <label for="matricula">Matrícula</label>
                    <input
                        id="matricula"
                        name="matricula"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]{1,18}"
                        maxlength="18"
                        value="<?= escapar($dados['matricula']) ?>"
                        <?= $alunoEdicao ? 'readonly' : '' ?>
                        required
                    >
                </div>

                <div>
                    <label for="nome">Nome completo</label>
                    <input
                        id="nome"
                        name="nome"
                        type="text"
                        maxlength="100"
                        value="<?= escapar($dados['nome']) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="datnasc">Data de nascimento</label>
                    <input
                        id="datnasc"
                        name="datnasc"
                        type="date"
                        value="<?= escapar($dados['datnasc']) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="endereco">Endereço</label>
                    <input
                        id="endereco"
                        name="endereco"
                        type="text"
                        maxlength="200"
                        value="<?= escapar($dados['endereco']) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option
                            value="F"
                            <?= $dados['sexo'] === 'F' ? 'selected' : '' ?>
                        >
                            Feminino
                        </option>
                        <option
                            value="M"
                            <?= $dados['sexo'] === 'M' ? 'selected' : '' ?>
                        >
                            Masculino
                        </option>
                    </select>
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        maxlength="100"
                        value="<?= escapar($dados['email']) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="situacao">Situação</label>
                    <select id="situacao" name="situacao" required>
                        <option
                            value="ativo"
                            <?= $dados['situacao'] === 'ativo' ? 'selected' : '' ?>
                        >
                            Ativo
                        </option>
                        <option
                            value="inativo"
                            <?= $dados['situacao'] === 'inativo' ? 'selected' : '' ?>
                        >
                            Inativo
                        </option>
                    </select>
                </div>

                <div>
                    <label for="codigoTurma">Turma</label>
                    <select id="codigoTurma" name="codigoTurma" required>
                        <option value="">Selecione uma turma</option>

                        <?php foreach ($turmas as $turma): ?>
                            <option
                                value="<?= escapar($turma['codigo']) ?>"
                                <?= (string) $dados['codigoTurma'] ===
                                    (string) $turma['codigo']
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= escapar($turma['codigo']) ?>
                                — <?= escapar($turma['curso']) ?>
                                — Série <?= escapar($turma['serie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="botoes">
                <button type="submit" class="btn btn-success">
                    <?= $alunoEdicao
                        ? 'Salvar alterações'
                        : 'Cadastrar aluno' ?>
                </button>

                <?php if ($alunoEdicao): ?>
                    <a href="aluno.php" class="btn btn-secondary">
                        Cancelar edição
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="painel">
        <h2>Alunos ativos</h2>

        <form action="aluno.php" method="get" class="pesquisa">
            <input
                type="text"
                name="nome"
                aria-label="Pesquisar aluno pelo nome"
                placeholder="Nome ou parte do nome"
                value="<?= escapar($pesquisa) ?>"
            >

            <button type="submit" class="btn btn-primary">
                Buscar
            </button>

            <a href="aluno.php" class="btn btn-secondary">
                Limpar
            </a>
        </form>

        <br>

        <?php if (!$alunos): ?>
            <p>Nenhum aluno encontrado.</p>
        <?php else: ?>
            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Dados pessoais</th>
                            <th>Turma</th>
                            <th>Ações</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?= escapar($aluno['matricula']) ?></td>

                            <td><?= escapar($aluno['nome']) ?></td>

                            <td>
                                Nascimento:
                                <?= escapar($aluno['datnasc']) ?><br>

                                Endereço:
                                <?= escapar($aluno['endereco']) ?><br>

                                Sexo:
                                <?= escapar($aluno['sexo']) ?><br>

                                E-mail:
                                <?= escapar($aluno['email']) ?><br>

                                Situação:
                                <?= escapar($aluno['situacao']) ?>
                            </td>

                            <td>
                                <?= escapar($aluno['codigoTurma']) ?>
                                — <?= escapar($aluno['curso']) ?>
                            </td>

                            <td class="acoes">
                                <a
                                    href="aluno.php?editar=<?= escapar($aluno['matricula']) ?>"
                                    class="btn btn-primary"
                                >
                                    Editar
                                </a>

                                <form
                                    action="aluno.php"
                                    method="post"
                                    onsubmit="return confirm('Deseja inativar este aluno? O histórico será preservado.');"
                                >
                                    <input
                                        type="hidden"
                                        name="csrf"
                                        value="<?= escapar($_SESSION['csrf_alunos']) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="acao"
                                        value="inativar"
                                    >

                                    <input
                                        type="hidden"
                                        name="matricula"
                                        value="<?= escapar($aluno['matricula']) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Inativar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>