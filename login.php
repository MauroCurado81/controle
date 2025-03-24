<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root"; // padrão no XAMPP
$password = ""; // padrão no XAMPP
$dbname = "seu_banco_de_dados"; // substitua pelo nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber dados do formulário
$user = $_POST['username'];
$pass = $_POST['password'];

// Preparar a consulta para evitar SQL Injection
$sql = "SELECT * FROM usuarios WHERE nome = ? AND senha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user, $pass); // "ss" indica que ambos são strings
$stmt->execute();
$result = $stmt->get_result();

// Verificar se existe o usuário
if ($result->num_rows > 0) {
    // Login bem-sucedido, redirecionar para a página "selecionar.html"
    header("Location: selecionar.html");
    exit(); // Certifique-se de que o script termina aqui
} else {
    // Caso contrário, exibir uma mensagem de erro
    echo "Usuário ou senha inválidos.";
}

// Fechar a conexão
$conn->close();
?>
