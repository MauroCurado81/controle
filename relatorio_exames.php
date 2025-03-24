<?php
// Dados de conexão
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastro_exames";  // Nome do banco de dados

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se houve erro na conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultar os exames cadastrados
$sql = "SELECT * FROM exames";
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
            background-color: white;
            margin: 0;
            padding: 10px;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }
        a {
            display: inline-block;
            margin-bottom: 10px;
            padding: 5px 10px;
            background-color: #4e4e4e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        /* Responsividade */
        .table-container {
            width: 100%;
            overflow-x: auto; /* Permite rolagem horizontal */
        }
    </style>
</head>
<body>

    <div class="container">
        <a href="relatorios.html">Voltar</a>
        <h1>Relatório de Exames Cadastrados</h1>
        
        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
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
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['nome']; ?></td>
                                <td><?php echo $row['cargo']; ?></td>
                                <td><?php echo $row['tipo_exame']; ?></td>
                                <td><?php echo date("d/m/Y", strtotime($row['data_exame'])); ?></td>
                                <td><?php echo date("d/m/Y", strtotime($row['data_proximo_exame'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum exame cadastrado até o momento.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php $conn->close(); ?>

</body>
</html>