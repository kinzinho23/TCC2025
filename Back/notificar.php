<?php

function criarNotificacao($conn, $idUsuario, $tipoDestino, $titulo, $mensagem, $link = null)
{
    $sql = "
    INSERT INTO notificacoes
    (idUsuario, tipoDestino, titulo, mensagem, link)
    VALUES (?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        "issss",
        $idUsuario,
        $tipoDestino,
        $titulo,
        $mensagem,
        $link
    );

    return $stmt->execute();
}

?>