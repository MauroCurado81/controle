<?php
require('fpdf186/fpdf.php'); // Use caminhos relativos em produção

// Conexão com o banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "cadastro_exames";
$conn = new mysqli($servidor, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Capturar filtros e sanitizar dados de entrada
$nome = isset($_GET['nome']) ? $conn->real_escape_string($_GET['nome']) : '';
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';

// Validar formato das datas
if (!empty($data_inicio) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_inicio)) {
    die("Formato de data de início inválido.");
}
if (!empty($data_fim) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_fim)) {
    die("Formato de data de fim inválido.");
}

// Construir consulta SQL dinâmica
$sql = "SELECT * FROM exames WHERE 1=1";
$params = [];
$types = "";

// Filtros
if (!empty($nome)) {
    $sql .= " AND nome LIKE ?";
    $params[] = "%$nome%";
    $types .= "s";
}
if (!empty($data_inicio) && !empty($data_fim)) {
    $sql .= " AND data_exame BETWEEN ? AND ?";
    $params[] = $data_inicio;
    $params[] = $data_fim;
    $types .= "ss";
}

// Preparar a consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

// Vincular parâmetros
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Executar a consulta
$stmt->execute();
$result = $stmt->get_result();

// Criando o PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Relatório de Exames', 1, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'ID', 1);
$pdf->Cell(50, 10, 'Nome', 1);
$pdf->Cell(40, 10, 'Cargo', 1);
$pdf->Cell(40, 10, 'Data Exame', 1);
$pdf->Cell(30, 10, 'Próximo', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(30, 10, $row['id'], 1);
        $pdf->Cell(50, 10, $row['nome'], 1);
        $pdf->Cell(40, 10, $row['cargo'], 1);
        $pdf->Cell(40, 10, date("d/m/Y", strtotime($row['data_exame'])), 1);
        $pdf->Cell(30, 10, date("d/m/Y", strtotime($row['data_proximo_exame'])), 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(190, 10, 'Nenhum resultado encontrado.', 1, 1, 'C');
}

// Exibir o PDF no navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="relatorio_exames.pdf"');
$pdf->Output('I', 'relatorio_exames.pdf');

// Fechar a conexão
$stmt->close();
$conn->close();
?>
