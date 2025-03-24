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
$sql = "SELECT * FROM exames";  // A consulta SQL para buscar os dados da tabela exames
$result = $conn->query($sql);   // Executa a consulta

// Inicia a exibição do HTML
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Exames</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
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
    </style>
</head>
<body>
    <a href="relatorios.html">Voltar</a>
    <div class="container">
        <h1>Relatório de Exames Cadastrados</h1>
        <?php if ($result->num_rows > 0): ?>
            <!-- Exibir a tabela com os exames cadastrados -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Tipo de Exame</th>
                        <th>Data do Exame</th>
                        <th>Data do Proximo Exame</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['cargo']; ?></td>
                            <td><?php echo $row['tipo_exame']; ?></td>
                            <td><?php echo $row['data_exame']; ?></td>
                            <td><?php echo $row['data_proximo_exame']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum exame cadastrado até o momento.</p>
        <?php endif; ?>
    </div>

    <?php
    // Fechar a conexão com o banco
    $conn->close();
    ?>

</body>
</html>
