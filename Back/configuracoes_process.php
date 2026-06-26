<?php

session_start();

require_once 'conexao.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../Front/login.php");
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

$temaSite = $_POST['temaSite'] ?? 'claro';
$notificacoes = $_POST['notificacoes'] ?? 'ativadas';
$mostrarFoto = $_POST['mostrarFoto'] ?? 'sim';

$temasPermitidos = ['claro', 'escuro'];
$notificacoesPermitidas = ['ativadas', 'desativadas'];
$mostrarFotoPermitido = ['sim', 'nao'];

if (
    !in_array($temaSite, $temasPermitidos) ||
    !in_array($notificacoes, $notificacoesPermitidas) ||
    !in_array($mostrarFoto, $mostrarFotoPermitido)
) {
    header("Location: ../Front/configuracoes.php?error=" . urlencode("Configuração inválida"));
    exit;
}

$sqlUser = "
SELECT tipoUsuario
FROM usuario
WHERE idUsuario = ?
LIMIT 1
";

$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $idUsuario);
$stmtUser->execute();

$resUser = $stmtUser->get_result();
$user = $resUser->fetch_assoc();

$stmtUser->close();

if (!$user) {
    header("Location: ../Front/login.php");
    exit;
}

$sql = "
UPDATE usuario
SET 
    temaSite = ?,
    notificacoes = ?,
    mostrarFoto = ?
WHERE idUsuario = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssi",
    $temaSite,
    $notificacoes,
    $mostrarFoto,
    $idUsuario
);

if ($stmt->execute()) {
    header("Location: ../Front/configuracoes.php?success=" . urlencode("Configurações salvas com sucesso"));
    exit;
}

header("Location: ../Front/configuracoes.php?error=" . urlencode("Erro ao salvar configurações"));
exit;

?>