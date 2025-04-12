<?php
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

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Exames</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px 12px;
            text-align: center;
        }
        form {
            width: 90%;
            margin: 20px auto;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
    </style>
</head>
<body>

<h1>Relatório de Exames</h1>

<form method="get" action="">
    <input type="text" name="nome" placeholder="Nome" value="<?php echo htmlspecialchars($nome); ?>">
    <input type="date" name="data_inicio" value="<?php echo htmlspecialchars($data_inicio); ?>">
    <input type="date" name="data_fim" value="<?php echo htmlspecialchars($data_fim); ?>">
    <button type="submit">Filtrar</button>
</form>

<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Cargo</th>
        <th>Data do Exame</th>
        <th>Próximo Exame</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                <td><?php echo htmlspecialchars($row['cargo']); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['data_exame'])); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['data_proximo_exame'])); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Nenhum resultado encontrado.</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
