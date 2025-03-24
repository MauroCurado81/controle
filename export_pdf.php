<?php
require('fpdf/fpdf.php'); // Biblioteca FPDF (baixe em: http://www.fpdf.org/)

// Conexão com banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastro_exames";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Capturar filtros
$nome = isset($_GET['nome']) ? $_GET['nome'] : '';
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

// Construir consulta SQL dinâmica
$sql = "SELECT * FROM exames WHERE 1=1";
if (!empty($nome)) {
    $sql .= " AND nome LIKE '%$nome%'";
}
if (!empty($data_inicio) && !empty($data_fim)) {
    $sql .= " AND data_exame BETWEEN '$data_inicio' AND '$data_fim'";
}
$result = $conn->query($sql);

// Criando PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Relatorio de Exames', 1, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Nome', 1);
$pdf->Cell(40, 10, 'Cargo', 1);
$pdf->Cell(40, 10, 'Data Exame', 1);
$pdf->Cell(30, 10, 'Proximo', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 10, $row['id'], 1);
    $pdf->Cell(50, 10, $row['nome'], 1);
    $pdf->Cell(40, 10, $row['cargo'], 1);
    $pdf->Cell(40, 10, date("d/m/Y", strtotime($row['data_exame'])), 1);
    $pdf->Cell(30, 10, date("d/m/Y", strtotime($row['data_proximo_exame'])), 1);
    $pdf->Ln();
}

$pdf->Output();
$conn->close();
?>