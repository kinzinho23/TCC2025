<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexao.php';

header('Content-Type: application/json');

if (empty($_SESSION['idUsuario'])) {
    echo json_encode([
        'total' => 0
    ]);
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

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
    echo json_encode([
        'total' => 0
    ]);
    exit;
}

$tipoUsuario = $user['tipoUsuario'];

$sql = "
SELECT COUNT(*) AS total
FROM notificacoes
WHERE 
    lida = 0
    AND (
        idUsuario = ?
        OR tipoDestino = ?
    )
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $idUsuario, $tipoUsuario);
$stmt->execute();

$res = $stmt->get_result();
$row = $res->fetch_assoc();

$stmt->close();

echo json_encode([
    'total' => (int) ($row['total'] ?? 0)
]);

exit;

?>