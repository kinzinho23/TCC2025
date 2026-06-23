<?php

session_start();
require_once 'conexao.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../Front/login.php");
    exit;
}

$idUsuarioLogado = (int) $_SESSION['idUsuario'];

$sqlUser = "SELECT tipoUsuario FROM usuario WHERE idUsuario = ? LIMIT 1";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $idUsuarioLogado);
$stmtUser->execute();

$resUser = $stmtUser->get_result();
$user = $resUser->fetch_assoc();

$stmtUser->close();

if (!$user || !in_array($user['tipoUsuario'], ['admin', 'coordenacao'])) {
    header("Location: ../Front/salas.php?error=" . urlencode("Acesso negado"));
    exit;
}

$idSala = isset($_POST['idSala']) ? (int) $_POST['idSala'] : 0;
$idMateria = isset($_POST['idMateria']) ? (int) $_POST['idMateria'] : 0;

if ($idSala <= 0 || $idMateria <= 0) {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Dados inválidos"));
    exit;
}

$sqlMateria = "
SELECT idMateria 
FROM materias 
WHERE idMateria = ? 
AND idUsuario IS NOT NULL
LIMIT 1
";

$stmtMateria = $conn->prepare($sqlMateria);
$stmtMateria->bind_param("i", $idMateria);
$stmtMateria->execute();

$resMateria = $stmtMateria->get_result();

if ($resMateria->num_rows === 0) {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Essa matéria não possui professor vinculado"));
    exit;
}

$stmtMateria->close();

$sqlUpdate = "
UPDATE salas
SET idMateria = ?
WHERE idSala = ?
";

$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("ii", $idMateria, $idSala);

if ($stmtUpdate->execute()) {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&success=" . urlencode("Professor e matéria adicionados com sucesso"));
    exit;
}

header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Erro ao atualizar sala"));
exit;

?>''