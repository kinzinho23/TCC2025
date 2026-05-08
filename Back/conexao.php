<?php
$host = "localhost:3308";
$user = "root";
$password = "etec123";
$dbname = "banco";

// Criar conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
//  ENUM('professor', 'aluno', 'direcao', 'admin')
?>

