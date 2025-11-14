<?php
$host = "localhost:3308";
$user = "root";
$password = "etec2025";
$dbname = "myclass";

// Criar conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>