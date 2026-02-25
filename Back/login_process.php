<?php
session_start();
require_once 'conexao.php';

if (!isset($_POST['identificador'], $_POST['password'])) {
    header('Location: ../Front/login.php?error=empty');
    exit();
}

$identificador = trim($_POST['identificador']);
$password = $_POST['password'];

$sql = "SELECT idUsuario, tipoUsuario, senhaUsuario FROM usuario WHERE identificador = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $identificador);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['senhaUsuario'])) {

        session_regenerate_id(true);

        $_SESSION['idUsuario'] = $user['idUsuario'];
        $_SESSION['tipoUsuario'] = $user['tipoUsuario'];

        header('Location: ../Front/index.php');
        exit();
    } else {
        header('Location: ../Front/login.php?error=password');
        exit();
    }
} else {
    header('Location: ../Front/login.php?error=user');
    exit();
}
?>