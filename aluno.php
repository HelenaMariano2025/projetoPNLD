<?php
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/AlunoRepository.php';

const ALUNO_NAO_ENCONTRADO = 'Aluno não encontrado.';

class FalhaPersistenciaAlunoException extends RuntimeException
{
}

$conn->set_charset('utf8mb4');
$repository = new AlunoRepository($conn);
<body class="sub_page">

  <div class="hero_area">
    <!-- header section starts -->
    <header class="header_section">
      <div class="header_top">
        <div class="container">
          <div class="contact_nav">
            <a href="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Contato : +01 123455678990
              </span>
            </a>
            <a href="">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                Email : ifpb@gmail.com
              </span>
            </a>
            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                <a href="https://www.bing.com/maps?osid=2d9cc22c-c352-4b15-84e1-a645eaf97d8a&cp=-7.025562~-37.280592&lvl=17&pi=0&v=2&sV=2&form=S00027">Localização</a>
              </span>
            </a>
          </div>
        </div>
      </div>
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" >
              <img src="images/White and navy simple book store logo.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav  ">
                  <li class="nav-item ">
                    <a class="nav-link" href="funcoes.php">HOME <span class="sr-only">(current)</span></a>
                  </li>
                 
                  <li class="nav-item">
                    <a class="nav-link" href="add_alunos.php">ADICIONAR ALUNO</a>
                  </li>
                 
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- Livros section -->
<section class="client_section layout_padding">
  <div class="container">
    <div class="heading_container">
      <h2>
        <span>Alunos</span>
      </h2>
    </div>
  </div>
  <div class="container px-0">
    <div id="customCarousel2" class="carousel  carousel-fade" data-ride="carousel">
      <div class="carousel-inner">
      <div class="search_container">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <input type="text" name="nome" placeholder="Buscar por nome do aluno">
    <button type="submit">Buscar</button>
</form>
</div>
<?php


session_start();

require_once 'php/auth.php';
exigirAutenticacao();


// Incluir o arquivo de conexão
include 'php/conexao.php';

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

        foreach (array_keys($dados) as $campo) {
            $dados[$campo] = is_string($_POST[$campo] ?? null)
                ? trim($_POST[$campo])
                : '';
        }

        if (!preg_match('/^\d{1,18}$/D', $dados['matricula'])) {
            throw new InvalidArgumentException(
                'Informe uma matrícula numérica válida, com até 18 dígitos.'
            );
        }

        if ($acao === 'inativar') {
            if (!$repository->consultarPorMatricula($dados['matricula'])) {
                throw new InvalidArgumentException(ALUNO_NAO_ENCONTRADO);
            }

            if (!$repository->inativar($dados['matricula'])) {
                throw new FalhaPersistenciaAlunoException(
                    'Não foi possível inativar o aluno.'
                );
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
            throw new InvalidArgumentException(ALUNO_NAO_ENCONTRADO);
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
            throw new FalhaPersistenciaAlunoException(
                'Não foi possível salvar os dados do aluno.'
            );
        }

        $_SESSION['mensagem_alunos'] = $acao === 'cadastrar'
            ? 'Aluno cadastrado com sucesso!'
            : 'Dados do aluno atualizados com sucesso!';

        header('Location: aluno.php');
        exit;
    } catch (mysqli_sql_exception $e) {
        $erro = 'Não foi possível concluir a operação. Confira os dados informados.';
    } catch (InvalidArgumentException | FalhaPersistenciaAlunoException $e) {
        $erro = $e->getMessage();
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

    if (preg_match('/^\d{1,18}$/D', $matricula)) {
        $alunoEdicao = $repository->consultarPorMatricula($matricula);

        if ($alunoEdicao) {
            $dados = $alunoEdicao;
        } else {
            $erro = ALUNO_NAO_ENCONTRADO;
        }
    } else {
        $erro = 'Matrícula inválida.';
    }
}

$turmas = $conn->query(
    'SELECT codigo, curso, periodo, serie FROM turma ORDER BY codigo'
)->fetch_all(MYSQLI_ASSOC);

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
            flex-wrap: wrap;
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
        <h2><?= $alunoEdicao ? 'Editar aluno' : 'Cadastrar aluno' ?></h2>

        <form action="aluno.php" method="post">
            <input type="hidden" name="csrf"
                   value="<?= escapar($_SESSION['csrf_alunos']) ?>">
            <input type="hidden" name="acao"
                   value="<?= $alunoEdicao ? 'atualizar' : 'cadastrar' ?>">

            <div class="campos">
                <div>
                    <label for="matricula">Matrícula</label>
                    <input id="matricula" name="matricula" type="text"
                           inputmode="numeric" pattern="\d{1,18}" maxlength="18"
                           value="<?= escapar($dados['matricula']) ?>"
                           <?= $alunoEdicao ? 'readonly' : '' ?> required>
                </div>

                <div>
                    <label for="nome">Nome completo</label>
                    <input id="nome" name="nome" type="text" maxlength="100"
                           value="<?= escapar($dados['nome']) ?>" required>
                </div>

                <div>
                    <label for="datnasc">Data de nascimento</label>
                    <input id="datnasc" name="datnasc" type="date"
                           value="<?= escapar($dados['datnasc']) ?>" required>
                </div>

                <div>
                    <label for="endereco">Endereço</label>
                    <input id="endereco" name="endereco" type="text" maxlength="200"
                           value="<?= escapar($dados['endereco']) ?>" required>
                </div>

                <div>
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="F"
                            <?= $dados['sexo'] === 'F' ? 'selected' : '' ?>>
                            Feminino
                        </option>
                        <option value="M"
                            <?= $dados['sexo'] === 'M' ? 'selected' : '' ?>>
                            Masculino
                        </option>
                    </select>
                </div>

                <div>
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" maxlength="100"
                           value="<?= escapar($dados['email']) ?>" required>
                </div>

                <div>
                    <label for="situacao">Situação</label>
                    <select id="situacao" name="situacao" required>
                        <option value="ativo"
                            <?= $dados['situacao'] === 'ativo' ? 'selected' : '' ?>>
                            Ativo
                        </option>
                        <option value="inativo"
                            <?= $dados['situacao'] === 'inativo' ? 'selected' : '' ?>>
                            Inativo
                        </option>
                    </select>
                </div>

                <div>
                    <label for="codigoTurma">Turma</label>
                    <select id="codigoTurma" name="codigoTurma" required>
                        <option value="">Selecione uma turma</option>
                        <?php foreach ($turmas as $turma): ?>
                            <option value="<?= escapar($turma['codigo']) ?>"
                                <?= (string) $dados['codigoTurma'] ===
                                    (string) $turma['codigo'] ? 'selected' : '' ?>>
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
                    <?= $alunoEdicao ? 'Salvar alterações' : 'Cadastrar aluno' ?>
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
            <input type="text" name="nome"
                   aria-label="Pesquisar aluno pelo nome"
                   placeholder="Nome ou parte do nome"
                   value="<?= escapar($pesquisa) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="aluno.php" class="btn btn-secondary">Limpar</a>
        </form>

        <?php if (!$alunos): ?>
            <p>Nenhum aluno encontrado.</p>
        <?php else: ?>
            <div class="tabela">
                <table>
                    <caption>Alunos ativos e suas respectivas turmas</caption>
                    <thead>
                        <tr>
                            <th scope="col">Matrícula</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Dados pessoais</th>
                            <th scope="col">Turma</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos as $aluno): ?>
                            <tr>
                                <td><?= escapar($aluno['matricula']) ?></td>
                                <td><?= escapar($aluno['nome']) ?></td>
                                <td>
                                    Nascimento: <?= escapar($aluno['datnasc']) ?><br>
                                    Endereço: <?= escapar($aluno['endereco']) ?><br>
                                    Sexo: <?= escapar($aluno['sexo']) ?><br>
                                    E-mail: <?= escapar($aluno['email']) ?><br>
                                    Situação: <?= escapar($aluno['situacao']) ?>
                                </td>
                                <td>
                                    <?= escapar($aluno['codigoTurma']) ?>
                                    — <?= escapar($aluno['curso']) ?>
                                </td>
                                <td class="acoes">
                                    <a href="aluno.php?editar=<?= escapar($aluno['matricula']) ?>"
                                       class="btn btn-primary">
                                        Editar
                                    </a>

                                    <form action="aluno.php" method="post"
                                          onsubmit="return confirm('Deseja inativar este aluno? O histórico será preservado.');">
                                        <input type="hidden" name="csrf"
                                               value="<?= escapar($_SESSION['csrf_alunos']) ?>">
                                        <input type="hidden" name="acao" value="inativar">
                                        <input type="hidden" name="matricula"
                                               value="<?= escapar($aluno['matricula']) ?>">
                                        <button type="submit" class="btn btn-danger">
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