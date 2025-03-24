<?php
// Conexão com o banco de dados
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

// Construir a consulta SQL dinâmica
$sql = "SELECT * FROM exames WHERE 1=1";
if (!empty($nome)) {
    $sql .= " AND nome LIKE '%$nome%'";
}
if (!empty($data_inicio) && !empty($data_fim)) {
    $sql .= " AND data_exame BETWEEN '$data_inicio' AND '$data_fim'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Exames</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4e4e4e;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .filters {
            margin-bottom: 20px;
        }
        .filters input, .filters button {
            padding: 5px;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Relatório de Exames</h1>

    <!-- Formulário de Filtros -->
    <form method="GET" class="filters">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?= $nome ?>">

        <label for="data_inicio">Data Início:</label>
        <input type="date" name="data_inicio" value="<?= $data_inicio ?>">

        <label for="data_fim">Data Fim:</label>
        <input type="date" name="data_fim" value="<?= $data_fim ?>">

        <button type="submit">Filtrar</button>
        <button type="button" onclick="window.location.href='relatorio.php'">Limpar</button>
    </form>

    <!-- Botões de Exportação -->
    <button onclick="exportTableToExcel()">Exportar para Excel</button>
    <button onclick="window.location.href='export_pdf.php?nome=<?= $nome ?>&data_inicio=<?= $data_inicio ?>&data_fim=<?= $data_fim ?>'">Exportar para PDF</button>

    <!-- Tabela de Exames -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Tipo de Exame</th>
                <th>Data do Exame</th>
                <th>Data do Próximo Exame</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['nome']; ?></td>
                    <td><?= $row['cargo']; ?></td>
                    <td><?= $row['tipo_exame']; ?></td>
                    <td><?= date("d/m/Y", strtotime($row['data_exame'])); ?></td>
                    <td><?= date("d/m/Y", strtotime($row['data_proximo_exame'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Script para Exportar Excel -->
<script>
function exportTableToExcel() {
    let table = document.querySelector("table");
    let file = new Blob([table.outerHTML], { type: "application/vnd.ms-excel" });
    let a = document.createElement("a");
    a.href = URL.createObjectURL(file);
    a.download = "relatorio_exames.xls";
    a.click();
}
</script>

</body>
</html>

<?php $conn->close(); ?>