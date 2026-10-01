<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Livros Disponíveis</title>

  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/responsive.css" rel="stylesheet">
</head>

<body class="sub_page">
  <div class="hero_area">
    <header class="header_section">
      <div class="header_top">
        <div class="container">
          <div class="contact_nav">
            <a href="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>Contato : +01 123455678990</span>
            </a>

            <a href="">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>Email : ifpb@gmail.com</span>
            </a>

            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>Localização</span>
            </a>
          </div>
        </div>
      </div>

      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container">
            <a class="navbar-brand">
              <img src="images/White and navy simple book store logo.png" alt="">
            </a>

            <button
              class="navbar-toggler"
              type="button"
              data-toggle="collapse"
              data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link" href="funcoes.php">HOME</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="add_livro.php">ADICIONAR LIVRO</a>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header>
  </div>

  <section class="client_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2><span>Livros</span></h2>
      </div>

      <?php if (($_GET['exclusao'] ?? '') === 'sucesso'): ?>
        <p role="status">Livro excluído da listagem com sucesso.</p>
      <?php elseif (($_GET['exclusao'] ?? '') === 'erro'): ?>
        <p role="alert">Não foi possível excluir o livro.</p>
      <?php endif; ?>
    </div>

    <div class="container px-0">
      <div id="customCarousel2" class="carousel carousel-fade" data-ride="carousel">
        <div class="carousel-inner">
          <div class="search_container">
            <form
              action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>"
              method="get"
            >
              <input type="text" name="titulo" placeholder="Buscar por título do livro">
              <button type="submit">Buscar</button>
            </form>
          </div>

          <?php
          require_once __DIR__ . '/php/conexao.php';
          require_once __DIR__ . '/php/LivroRepository.php';

          $repository = new LivroRepository($conn);
          $titulo = isset($_GET['titulo']) ? (string) $_GET['titulo'] : null;
          $result = $repository->consultarDisponiveis($titulo);

          if ($result !== false && $result->num_rows > 0) {
              $count = 0;

              while ($row = $result->fetch_assoc()) {
                  $activeClass = $count === 0 ? 'active' : '';
                  $codigo = (int) $row['codigo'];

                  echo "<div class='carousel-item $activeClass'>";
                  echo "<div class='box'>";
                  echo "<div class='client_info'>";
                  echo "<div class='client_name'>";
                  echo '<h5>' . htmlspecialchars((string) $row['isbn'], ENT_QUOTES, 'UTF-8') . '</h5>';
                  echo '<h6>' . htmlspecialchars((string) $row['titulo'], ENT_QUOTES, 'UTF-8') . '</h6>';
                  echo '</div>';
                  echo '</div>';

                  echo '<p>' . htmlspecialchars((string) $row['autor'], ENT_QUOTES, 'UTF-8') . '</p>';
                  echo '<p><a href="editar_livro.php?codigo=' . $codigo . '">Editar livro</a></p>';

                  echo '<form action="excluir_livro.php" method="post" '
                      . 'onsubmit="return confirm(\'Deseja excluir este livro da listagem?\')">';
                  echo '<input type="hidden" name="codigo" value="' . $codigo . '">';
                  echo '<button type="submit">Excluir livro</button>';
                  echo '</form>';

                  echo '</div>';
                  echo '</div>';

                  $count++;
              }
          } else {
              echo "<div class='carousel-item active'>";
              echo "<div class='box'>";
              echo '<p>Nenhum livro disponível no momento.</p>';
              echo '</div>';
              echo '</div>';
          }

          $conn->close();
          ?>
        </div>

        <div class="carousel_btn-box">
          <a class="carousel-control-prev" href="#customCarousel2" role="button" data-slide="prev">
            <i class="fa fa-angle-left" aria-hidden="true"></i>
            <span class="sr-only">Anterior</span>
          </a>

          <a class="carousel-control-next" href="#customCarousel2" role="button" data-slide="next">
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            <span class="sr-only">Próximo</span>
          </a>
        </div>
      </div>
    </div>
  </section>

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <script src="js/custom.js"></script>
</body>
</html>