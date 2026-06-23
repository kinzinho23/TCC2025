<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    require_once __DIR__ . '/conexao.php';
}

$preferencias = [
    'temaSite' => 'claro',
    'notificacoes' => 'ativadas',
    'mostrarFoto' => 'sim',
    'telaInicial' => 'dashboard'
];

if (!empty($_SESSION['idUsuario'])) {

    $idUsuario = (int) $_SESSION['idUsuario'];

    $sql = "
    SELECT 
        temaSite,
        notificacoes,
        mostrarFoto,
        telaInicial
    FROM usuario
    WHERE idUsuario = ?
    LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $res = $stmt->get_result();
        $dados = $res->fetch_assoc();

        if ($dados) {

            $preferencias['temaSite'] = $dados['temaSite'] ?? 'claro';
            $preferencias['notificacoes'] = $dados['notificacoes'] ?? 'ativadas';
            $preferencias['mostrarFoto'] = $dados['mostrarFoto'] ?? 'sim';
            $preferencias['telaInicial'] = $dados['telaInicial'] ?? 'dashboard';

        }

        $stmt->close();

    }

}

?>