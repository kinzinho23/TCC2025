<?php
session_start();
require_once 'conexao.php';

// Verifica se os campos foram enviados
if (!isset($_POST['identificador'], $_POST['password'])) {
    header('Location: ../Front/login.php?error=empty');
    exit();
}

$identificador = trim($_POST['identificador']);
$password = $_POST['password'];

$sql = "SELECT idUsuario, senhaUsuario FROM usuario WHERE identificador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $identificador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifica a senha
    if (password_verify($password, $user['senhaUsuario'])) {

        // Previne FIXAÇÃO DE SESSÃO
        session_regenerate_id(true);

        // Salva dados essenciais na sessão
        $_SESSION['idUsuario'] = $user['idUsuario'];

        header('Location: ../Front/index.php');
        exit();
    } else {
        // Senha incorreta
        header('Location: ../Front/login.php?error=password');
        exit();
    }
} else {
    // Usuário não encontrado
    header('Location: ../Front/login.php?error=user');
    exit();
}
?>