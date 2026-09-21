<?php
include 'php/conexao.php';

/*
|--------------------------------------------------------------------------
| ATUALIZAR ALUNO
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $datnasc = $_POST['datnasc'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];
    $email = $_POST['email'];
    $situacao = $_POST['situacao'];
    $codigoTurma = $_POST['codigoTurma'];

    $sql = "UPDATE aluno SET
            nome = '$nome',
            datnasc = '$datnasc',
            endereco = '$endereco',
            sexo = '$sexo',
            email = '$email',
            situacao = '$situacao',
            codigoTurma = '$codigoTurma'
            WHERE matricula = '$matricula'";

    if ($conn->query($sql) === TRUE) {

        $conn->close();

        // Volta para a lista de alunos depois de salvar
        header("Location: aluno.php?atualizado=1");
        exit;

    } else {

        $erro = "Erro ao atualizar aluno: " . $conn->error;
    }
}


/*
|--------------------------------------------------------------------------
| BUSCAR ALUNO PARA EDIÇÃO
|--------------------------------------------------------------------------
*/

if (isset($_GET['matricula'])) {

    $matricula = $_GET['matricula'];

    $sql = "SELECT * FROM aluno WHERE matricula = '$matricula'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $aluno = $result->fetch_assoc();

    } else {

        echo "Aluno não encontrado.";
        exit;
    }

} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {

    echo "Matrícula não informada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Aluno</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-8 col-lg-7">

                <div class="card shadow-sm">

                    <div class="card-header bg-success text-white">

                        <h4 class="mb-0">
                            Editar Aluno
                        </h4>

                    </div>

                    <div class="card-body">

                        <?php if (isset($erro)) { ?>

                            <div class="alert alert-danger">
                                <?php echo $erro; ?>
                            </div>

                        <?php } ?>

                        <form action="editar_aluno.php" method="POST">

                            <!-- Matrícula -->
                            <input
                                type="hidden"
                                name="matricula"
                                value="<?php echo $aluno['matricula']; ?>"
                            >

                            <div class="mb-3">

                                <label class="form-label">
                                    Matrícula
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?php echo $aluno['matricula']; ?>"
                                    disabled
                                >

                            </div>


                            <!-- Nome -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Nome
                                </label>

                                <input
                                    type="text"
                                    name="nome"
                                    class="form-control"
                                    value="<?php echo $aluno['nome']; ?>"
                                    required
                                >

                            </div>


                            <!-- Data de nascimento -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Data de nascimento
                                </label>

                                <input
                                    type="date"
                                    name="datnasc"
                                    class="form-control"
                                    value="<?php echo $aluno['datnasc']; ?>"
                                >

                            </div>


                            <!-- Endereço -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Endereço
                                </label>

                                <input
                                    type="text"
                                    name="endereco"
                                    class="form-control"
                                    value="<?php echo $aluno['endereco']; ?>"
                                >

                            </div>


                            <!-- Sexo -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Sexo
                                </label>

                                <input
                                    type="text"
                                    name="sexo"
                                    class="form-control"
                                    value="<?php echo $aluno['sexo']; ?>"
                                >

                            </div>


                            <!-- Email -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?php echo $aluno['email']; ?>"
                                    required
                                >

                            </div>


                            <!-- Situação -->
                            <div class="mb-3">

                                <label class="form-label">
                                    Situação
                                </label>

                                <select
                                    name="situacao"
                                    class="form-select"
                                >

                                    <option
                                        value="ativo"
                                        <?php
                                        if ($aluno['situacao'] == 'ativo')
                                            echo 'selected';
                                        ?>
                                    >
                                        Ativo
                                    </option>

                                    <option
                                        value="inativo"
                                        <?php
                                        if ($aluno['situacao'] == 'inativo')
                                            echo 'selected';
                                        ?>
                                    >
                                        Inativo
                                    </option>

                                </select>

                            </div>


                            <!-- Código da turma -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Código da turma
                                </label>

                                <input
                                    type="number"
                                    name="codigoTurma"
                                    class="form-control"
                                    value="<?php echo $aluno['codigoTurma']; ?>"
                                    required
                                >

                            </div>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between">

                                <a
                                    href="aluno.php"
                                    class="btn btn-secondary"
                                >
                                    Voltar
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                >
                                    Salvar alterações
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>