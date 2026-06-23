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
$telaInicial = $_POST['telaInicial'] ?? 'dashboard';

$temasPermitidos = ['claro', 'escuro'];
$notificacoesPermitidas = ['ativadas', 'desativadas'];
$mostrarFotoPermitido = ['sim', 'nao'];
$telasPermitidas = ['dashboard', 'materias', 'salas', 'configuracoesDev'];

if (
    !in_array($temaSite, $temasPermitidos) ||
    !in_array($notificacoes, $notificacoesPermitidas) ||
    !in_array($mostrarFoto, $mostrarFotoPermitido) ||
    !in_array($telaInicial, $telasPermitidas)
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

if (
    $telaInicial === 'configuracoesDev' &&
    !in_array($user['tipoUsuario'], ['admin', 'coordenacao'])
) {
    header("Location: ../Front/configuracoes.php?error=" . urlencode("Você não tem permissão para essa tela inicial"));
    exit;
}

$sql = "
UPDATE usuario
SET 
    temaSite = ?,
    notificacoes = ?,
    mostrarFoto = ?,
    telaInicial = ?
WHERE idUsuario = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssi",
    $temaSite,
    $notificacoes,
    $mostrarFoto,
    $telaInicial,
    $idUsuario
);

if ($stmt->execute()) {
    header("Location: ../Front/configuracoes.php?success=" . urlencode("Configurações salvas com sucesso"));
    exit;
}

header("Location: ../Front/configuracoes.php?error=" . urlencode("Erro ao salvar configurações"));
exit;

?>