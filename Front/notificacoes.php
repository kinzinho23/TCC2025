<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Back/conexao.php';
require_once __DIR__ . '/../Back/preferencias.php';

if (empty($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

$sqlUser = "
SELECT 
    idUsuario,
    nomeUsuario,
    tipoUsuario
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
    header("Location: login.php");
    exit;
}

$tipoUsuario = $user['tipoUsuario'];

$sqlNotificacoes = "
SELECT 
    idNotificacao,
    titulo,
    mensagem,
    link,
    lida,
    dataCriacao
FROM notificacoes
WHERE 
    idUsuario = ?
    OR tipoDestino = ?
ORDER BY dataCriacao DESC
";

$stmtNot = $conn->prepare($sqlNotificacoes);
$stmtNot->bind_param("is", $idUsuario, $tipoUsuario);
$stmtNot->execute();

$resNot = $stmtNot->get_result();

$notificacoes = [];

while ($row = $resNot->fetch_assoc()) {
    $notificacoes[] = $row;
}

$stmtNot->close();


$sqlMarcarLidas = "
UPDATE notificacoes
SET lida = 1
WHERE 
    idUsuario = ?
    OR tipoDestino = ?
";

$stmtLidas = $conn->prepare($sqlMarcarLidas);
$stmtLidas->bind_param("is", $idUsuario, $tipoUsuario);
$stmtLidas->execute();
$stmtLidas->close();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>MyClass - Notificações</title>
    <link rel="stylesheet" href="../css/notificacoes.css">
    <link rel="shortcut icon" href="../img/favicon.ico"type="image/x-icon">
</head>

<body class="<?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="notificacoes-container">

    <section class="notificacoes-header">
        <div>
            <h1>Notificações</h1>
            <p>Acompanhe os avisos importantes do sistema.</p>
        </div>
    </section>

    <section class="notificacoes-card">

        <?php if (empty($notificacoes)): ?>

            <div class="notificacao-vazia">
                <span>🔔</span>
                <h2>Nenhuma notificação</h2>
                <p>Quando algo importante acontecer, aparecerá aqui.</p>
            </div>

        <?php else: ?>

            <div class="notificacoes-lista">

                <?php foreach ($notificacoes as $notificacao): ?>

                    <?php
                        $classeLida = $notificacao['lida'] ? 'lida' : 'nao-lida';
                        $link = !empty($notificacao['link']) ? $notificacao['link'] : '#';
                    ?>

                    <a 
                        href="<?php echo htmlspecialchars($link); ?>" 
                        class="notificacao-item <?php echo $classeLida; ?>"
                    >

                        <div class="notificacao-icon">
                            🔔
                        </div>

                        <div class="notificacao-conteudo">

                            <div class="notificacao-topo">
                                <h3>
                                    <?php echo htmlspecialchars($notificacao['titulo']); ?>
                                </h3>

                                <?php if (!$notificacao['lida']): ?>
                                    <span class="badge-nova">Nova</span>
                                <?php endif; ?>
                            </div>

                            <p>
                                <?php echo htmlspecialchars($notificacao['mensagem']); ?>
                            </p>

                            <small>
                                <?php echo date('d/m/Y H:i', strtotime($notificacao['dataCriacao'])); ?>
                            </small>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>