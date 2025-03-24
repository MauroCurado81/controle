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
    // Receber os dados do formulário
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $exame = $_POST['exame'];
    $data_exame = $_POST['data_exame'];

    // Calcular a data do próximo exame (365 dias após a data do exame atual)
    $next_exam_date = date('Y-m-d', strtotime($data_exame . ' + 365 days'));

    // Inserir os dados na tabela exames
    $sql = "INSERT INTO exames (nome, cargo, tipo_exame, data_exame, data_proximo_exame) 
            VALUES ('$nome', '$cargo', '$exame', '$data_exame', '$next_exam_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Exame cadastrado com sucesso! Próximo exame agendado para: " . $next_exam_date;
    } else {
        echo "Erro ao cadastrar exame: " . $conn->error;
    }

    // Fechar a conexão
    $conn->close();
}
?>

