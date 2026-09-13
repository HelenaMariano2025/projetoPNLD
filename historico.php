<?php
require 'php/conexao.php';

$query = "
    SELECT 
        emprestimo.codigo_emprestimo,
        livro.titulo AS livro,
        aluno.nome AS aluno,
        aluno.matricula,
        emprestimo.dataEmprestimo,
        emprestimo.dataDevolucao,
        devolucao.dataDevolucao AS data_devolucao_real
    FROM emprestimo
    INNER JOIN livro 
        ON emprestimo.codigo_livro = livro.codigo
    INNER JOIN aluno 
        ON emprestimo.matricula_aluno = aluno.matricula
    LEFT JOIN devolucao 
        ON emprestimo.codigo_emprestimo = devolucao.codigo_emprestimo
    ORDER BY emprestimo.codigo_emprestimo DESC
";

$resultado = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Historico</title> 
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  
  <!-- Fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- Owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- Font Awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- Nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css"
    integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- Datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- Responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>
<body>
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
            <a class="navbar-brand">
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

                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header> 
      <section class="historico_section">

          <div class="container">

              <div class="heading_container">

                  <h2>
                      Relatório de Empréstimos
                  </h2>

                  <p>
                      Consulte o histórico de empréstimos realizados no sistema.
                  </p>

              </div>


              <div class="table-responsive">

                  <table class="table">

                      <thead>

                          <tr>
                              <th>Código</th>
                              <th>Livro</th>
                              <th>Aluno</th>
                              <th>Matrícula</th>
                              <th>Data do empréstimo</th>
                              <th>Data prevista</th>
                              <th>Data da devolução</th>
                              <th>Status</th>
                          </tr>

                      </thead>

                      <tbody> 
                        <?php

                        if ($resultado && $resultado->num_rows > 0) {

                            while ($row = $resultado->fetch_assoc()) {

                                $dataEmprestimo = date(
                                    "d/m/Y",
                                    strtotime($row['dataEmprestimo'])
                                );

                                $dataPrevista = date(
                                    "d/m/Y",
                                    strtotime($row['dataDevolucao'])
                                );


                                if (!empty($row['data_devolucao_real'])) {

                                    $dataDevolucao = date(
                                        "d/m/Y",
                                        strtotime($row['data_devolucao_real'])
                                    );

                                    $status = "Devolvido";

                                } else {

                                    $dataDevolucao = "-";

                                    if (strtotime($row['dataDevolucao']) < time()) {

                                        $status = "Atrasado";

                                    } else {

                                        $status = "Em andamento";

                                    }

                                }

                                ?>

                                <tr>

                                    <td>
                                        <?php
                                        echo $row['codigo_emprestimo'];
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['livro']);
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['aluno']);
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $row['matricula'];
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $dataEmprestimo;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $dataPrevista;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $dataDevolucao;
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $status;
                                        ?>
                                    </td>

                                </tr>

                                <?php
                            }
                        } else {  
                            ?>
                            <tr>
                                <td colspan="8">Nenhum registro encontrado.</td>
                            </tr>
                            <?php
                        } 
                         ?>
                      </tbody>
                      </table>
              </div>
            </div>
      </section>
  </div>

</body>
</html>