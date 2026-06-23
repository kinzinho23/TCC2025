<?php

session_start();
require_once 'conexao.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../Front/login.php");
    exit;
}

$userRole = $_SESSION['tipoUsuario'] ?? null;

if (!in_array($userRole, ['admin', 'coordenacao'])) {
    header("Location: ../Front/salas.php?error=" . urlencode("Acesso negado"));
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'create') {

    $nomeSala = trim($_POST['nomeSala']);
    $capacidade = intval($_POST['capacidade']);
    $tipoSala = trim($_POST['tipoSala']);
    $stts = trim($_POST['stts']);

    if ($nomeSala === '' || $capacidade <= 0 || $tipoSala === '' || $stts === '') {
        header("Location: ../Front/adicionarSala.php?error=" . urlencode("Preencha todos os campos"));
        exit;
    }

    $sql = "
    INSERT INTO salas
    (nomeSala, capacidade, tipoSala, stts)
    VALUES (?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        header("Location: ../Front/adicionarSala.php?error=" . urlencode("Erro ao preparar cadastro"));
        exit;
    }

    $stmt->bind_param(
        "siss",
        $nomeSala,
        $capacidade,
        $tipoSala,
        $stts
    );

    if ($stmt->execute()) {
        header("Location: ../Front/salas.php?success=" . urlencode("Sala criada com sucesso"));
        exit;
    } else {
        header("Location: ../Front/adicionarSala.php?error=" . urlencode("Erro ao criar sala"));
        exit;
    }

}

header("Location: ../Front/salas.php?error=" . urlencode("Ação inválida"));
exit;

?>