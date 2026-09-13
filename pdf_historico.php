<?php

require 'vendor/autoload.php';

require 'php/conexao.php';

$query = "SELECT emprestimo.codigo_emprestimo AS id_emprestimo, livro.codigo AS id_livro, livro.titulo AS titulo_livro, livro.autor AS autor, emprestimo.dataEmprestimo AS emprestimo, emprestimo.dataDevolucao AS devolucao, aluno.nome AS aluno, aluno.matricula AS matricula_aluno, administrador.nome AS administrador, administrador.matricula AS matricula_adm FROM emprestimo, aluno, administrador, livro WHERE emprestimo.codigo_livro=livro.codigo AND emprestimo.matricula_aluno=aluno.matricula AND emprestimo.adm_responsavel=administrador.matricula ORDER BY id_emprestimo DESC LIMIT 30";

$dados = "<!DOCTYPE html>";
$dados .= "<html lang='pt-br'>";
$dados .= "<head>";
$dados .= "<meta charset='UTF-8'>";
$dados .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
$dados .= "<title>Histórico de emprestimos</title>";
$dados .= "</head>";
$dados .= "<body>";
$dados .= "<h1>Histórico de Livros</h1><hr>";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $emprestimo = DateTime::createFromFormat('Y-m-d', $row['emprestimo']);
        $devolucao= DateTime::createFromFormat('Y-m-d', $row['devolucao']);
        $dados .= "<p>ID: " . $row['id_emprestimo'] . ";</p>";
        $dados .= "<p>ID LIVRO: " . $row['id_livro'] . ";</p>";
        $dados .= "<p>TÍTULO: " . $row['titulo_livro'] . "; AUTOR: " . $row['autor'] . ";</p>";
        $dados .= "<p>DATA DE EMPRESTIMO: " . $emprestimo->format("d/m/Y") . "; DATA DE DEVOLUÇÃO: " . $devolucao->format("d/m/Y") . ";</p>";
        $dados .= "<p>ALUNO QUE REALIZOU O EMPRESTIMO: " . $row['aluno'] . ";</p>";
        $dados .= "<p>MATRÍCULA DO ALUNO: " . $row['matricula_aluno'] . "</p>";
        $dados .= "<p>ADMINISTRADOR QUE REALIZOU O EMPRESTIMO: " . $row['administrador'] . ";</p>";
        $dados .= "<p>MATRÍCULA DO ADMINISTRADOR: " . $row['matricula_adm'] . ";</p>";
        $dados .= "<hr>";
    }
} else {
    $dados .= "Nenhum livro emprestado até agora";
}

$dados .= "</body>";
$dados .= "</html>";

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($dados);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();