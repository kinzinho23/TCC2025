<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../Front/login.php");
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

$idSala = isset($_GET['idSala']) ? (int) $_GET['idSala'] : 0;
$numeroCarteira = isset($_GET['carteira']) ? (int) $_GET['carteira'] : 0;

if ($idSala <= 0 || $numeroCarteira <= 0) {
    header("Location: ../Front/salas.php?error=" . urlencode("Dados inválidos"));
    exit;
}

$sqlUser = "SELECT tipoUsuario FROM usuario WHERE idUsuario = ? LIMIT 1";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $idUsuario);
$stmtUser->execute();

$resUser = $stmtUser->get_result();
$user = $resUser->fetch_assoc();

$stmtUser->close();

if (!$user || $user['tipoUsuario'] !== 'aluno') {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Apenas alunos podem ocupar carteiras"));
    exit;
}

$sqlCheckCarteira = "
SELECT idCarteira 
FROM sala_carteiras 
WHERE idSala = ? AND numeroCarteira = ?
LIMIT 1
";

$stmtCheck = $conn->prepare($sqlCheckCarteira);
$stmtCheck->bind_param("ii", $idSala, $numeroCarteira);
$stmtCheck->execute();

$resCheck = $stmtCheck->get_result();

if ($resCheck->num_rows > 0) {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Essa carteira já está ocupada"));
    exit;
}

$stmtCheck->close();

$sqlAlunoSala = "
SELECT idCarteira 
FROM sala_carteiras 
WHERE idSala = ? AND idUsuario = ?
LIMIT 1
";

$stmtAluno = $conn->prepare($sqlAlunoSala);
$stmtAluno->bind_param("ii", $idSala, $idUsuario);
$stmtAluno->execute();

$resAluno = $stmtAluno->get_result();

if ($resAluno->num_rows > 0) {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Você já está em uma carteira dessa sala"));
    exit;
}

$stmtAluno->close();

$sqlInsert = "
INSERT INTO sala_carteiras 
(idSala, idUsuario, numeroCarteira)
VALUES (?, ?, ?)
";

$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("iii", $idSala, $idUsuario, $numeroCarteira);

if ($stmtInsert->execute()) {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&success=" . urlencode("Você entrou na carteira"));
    exit;
} else {
    header("Location: ../Front/salaDetalhe.php?id=" . $idSala . "&error=" . urlencode("Erro ao entrar na carteira"));
    exit;
}

?>