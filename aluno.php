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

  <title>Alunos Disponíveis</title> 
  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

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
// Incluir o arquivo de conexão
include 'php/conexao.php';

if (isset($_GET['nome']) && !empty($_GET['nome'])) {
    $nome = $_GET['nome'];

    // Consulta SQL para buscar os alunos com o nome informado
    $sql = "SELECT * FROM aluno WHERE situacao = 'ativo' AND nome LIKE '%$nome%'";
} else {
    // Se nenhum termo de pesquisa foi enviado, listar todos os alunos ativos
    $sql = "SELECT * FROM aluno WHERE situacao = 'ativo'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $count = 0;
    // Exibe cada aluno disponível
    while ($row = $result->fetch_assoc()) {
        // Define a classe 'active' para o primeiro item do carousel
        $active_class = ($count == 0) ? 'active' : '';
        echo "<div class='carousel-item $active_class'>";
        echo "<div class='box'>";
        echo "<div class='client_info'>";
        echo "<div class='client_name'>";
        echo "<h5>Matrícula: " . $row["matricula"] . "</h5>";
        echo "<h6>Nome: " . $row["nome"] . "</h6>";
        echo "</div>";
        echo "</div>";
        echo "<p>Data de Nascimento: " . $row["datnasc"] . "</p>";
        echo "<p>Endereço: " . $row["endereco"] . "</p>";
        echo "<p>Sexo: " . $row["sexo"] . "</p>";
        echo "<p>Email: " . $row["email"] . "</p>";
        echo "<p>Código da Turma: " . $row["codigoTurma"] . "</p>";

        echo "<a href='editar_aluno.php?matricula=" . $row["matricula"] . "' class='btn btn-primary'>Editar</a>";

        echo "<a href='deletar_aluno.php?matricula=" . $row["matricula"] . "' 
              class='btn btn-danger'
              onclick=\"return confirm('Tem certeza que deseja excluir este aluno?');\">
              Excluir
              </a>";
        echo "</div>";
        echo "</div>";
        $count++;
    }
} else {
    // Se não houver alunos disponíveis, exibe uma mensagem
    echo "<div class='carousel-item active'>";
    echo "<div class='box'>";
    echo "<p>Nenhum aluno disponível no momento.</p>";
    echo "</div>";
    echo "</div>";
}

?>

      </div>
      <div class="carousel_btn-box">
        <a class="carousel-control-prev" href="#customCarousel2" role="button" data-slide="prev">
          <i class="fa fa-angle-left" aria-hidden="true"></i>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#customCarousel2" role="button" data-slide="next">
          <i class="fa fa-angle-right" aria-hidden="true"></i>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
  </div>
</section>


  <!-- footer section -->
  
  <script src="js/jquery-3.4.1.min.js"></script>
 
  <script src="js/bootstrap.js"></script>
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>

  <script src="js/custom.js"></script></p>

  
</body>

</html>