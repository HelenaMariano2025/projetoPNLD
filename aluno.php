<?php
require_once __DIR__ . '/php/http.php';
iniciarSessao();

require_once __DIR__ . '/php/auth.php';
exigirAutenticacao();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once __DIR__ . '/php/conexao.php';
require_once __DIR__ . '/php/AlunoRepository.php';
require_once __DIR__ . '/php/AlunoValidador.php';

const ALUNO_NAO_ENCONTRADO = 'Aluno não encontrado.';

class FalhaPersistenciaAlunoException extends RuntimeException
{
}

$conn->set_charset('utf8mb4');
$repository = new AlunoRepository($conn);


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

                $erroMatricula = AlunoValidador::validarMatricula($dados['matricula']);

        if ($erroMatricula !== null) {
            throw new InvalidArgumentException($erroMatricula);
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

        $erroDados = AlunoValidador::validarDados($dados);

        if ($erroDados !== null) {
            throw new InvalidArgumentException($erroDados);
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


        $salvo = $acao === 'cadastrar'
            ? $repository->inserir($dados)
            : $repository->atualizar($dados);

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

$paginaAtiva = 'alunos';
$tituloPagina = 'Gerenciar Alunos — Sistema HBL Control';
require_once __DIR__ . '/includes/cabecalho.php';
?>
<main class="container my-4">
    <div class="painel">
        <h1>Gerenciar alunos</h1>
        <p class="text-muted mb-0">Cadastre, consulte e gerencie os alunos vinculados às turmas do PNLD.</p>
    </div>

    <?php if ($mensagem !== ''): ?>
        <output class="alert alert-success d-block">
            <?= escapar($mensagem) ?>
        </output>
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
            <div class="tabela table-responsive">
                <table class="table table-hover table-striped">
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
                                       class="btn btn-primary btn-sm">
                                        Editar
                                    </a>

                                    <form action="aluno.php" method="post"
                                          onsubmit="return confirm('Deseja inativar este aluno? O histórico será preservado.');">
                                        <input type="hidden" name="csrf"
                                               value="<?= escapar($_SESSION['csrf_alunos']) ?>">
                                        <input type="hidden" name="acao" value="inativar">
                                        <input type="hidden" name="matricula"
                                               value="<?= escapar($aluno['matricula']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
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
<?php require_once __DIR__ . '/includes/rodape.php'; ?>
