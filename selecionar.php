<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Atestados, Exames e Aviso Prévio</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background-color: #ffffff;
            padding: 40px;
            width: 280px;
            border-radius: 10px;
            color: #333;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        .box h2 {
            margin-bottom: 25px;
            font-size: 24px;
            color: #333;
        }
        .botao {
            display: block;
            background-color: #4e4e4e;
            text-decoration: none;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .botao:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="box">
            <h2>Olá, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h2>
            <p>Escolha a opção desejada:</p>

            <a href="atestado.php" class="botao">Atestados</a>
            <a href="aviso.php" class="botao">Aviso Prévio</a>
            <a href="exames.php" class="botao">Exames</a>
            <a href="relatorios.php" class="botao">Relatórios</a>
            <a href="logout.php" class="botao" style="background-color: darkred;">Sair</a>
        </div>
    </div>

</body>
</html>
