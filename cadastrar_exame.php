<?php
// Dados de conexão com o banco de dados
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

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber os dados do formulário e sanitizá-los
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $cargo = mysqli_real_escape_string($conn, $_POST['cargo']);
    $exame = mysqli_real_escape_string($conn, $_POST['exame']);
    $data_exame = mysqli_real_escape_string($conn, $_POST['data_exame']);

    // Validar a data do exame (se estiver no formato correto)
    if (DateTime::createFromFormat('Y-m-d', $data_exame) === FALSE) {
        echo "Formato de data inválido.";
        exit();
    }

    // Calcular a data do próximo exame (365 dias após a data do exame atual)
    $next_exam_date = date('Y-m-d', strtotime($data_exame . ' + 365 days'));

    // Usar prepared statement para inserir os dados na tabela exames
    $stmt = $conn->prepare("INSERT INTO exames (nome, cargo, tipo_exame, data_exame, data_proximo_exame) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $cargo, $exame, $data_exame, $next_exam_date);

    if ($stmt->execute()) {
        echo "Exame cadastrado com sucesso! Próximo exame agendado para: " . $next_exam_date;
    } else {
        echo "Erro ao cadastrar exame: " . $stmt->error;
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
}
?>
