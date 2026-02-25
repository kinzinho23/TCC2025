<?php
$host = "localhost:3306";
$user = "root";
$password = "";
$dbname = "banco";

// Criar conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
//  ENUM('professor', 'aluno', 'direcao', 'admin')
?>

