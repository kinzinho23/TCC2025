<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../Back/conexao.php';

$user = null;
if (!empty($_SESSION['idUsuario'])) {
    $uid = (int) $_SESSION['idUsuario'];
    if ($stmt = $conn->prepare('SELECT idUsuario, nomeUsuario, identificador, tipoUsuario, fotoUsuario FROM usuario WHERE idUsuario = ? LIMIT 1')) {
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
    }
}

function getFotoSrc($path) {
    if (empty($path)) {
        return '../img/perfil/usuario.png';
    }

    $path = trim($path);
    if (preg_match('#^(https?://|//|/|[A-Za-z]:\\\\)#', $path)) {
        return $path;
    }

    return '../' . ltrim($path, '/.');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/sidebar.css">
    <!-- Estilos: background padrão e homebar -->
</head>

<body class="app-bg">
    <header class="homebar">
        <div class="left">
            <button class="icon" onclick="openSidebar()" aria-label="Abrir menu">☰</button>
            <span class="logo">MyClass</span>
            <nav class="nav">
                <a href="../Front">Início</a>
                <a href="../Front/projetos.php">Projetos</a>
                <a href="../Front/contato.php">Contato</a>
            </nav>
        </div>

        <div class="actions">
            <?php if ($user): ?>
                <button class="icon" title="Notificações">🔔</button>
                <?php $avatarSrc = getFotoSrc($user['fotoUsuario']); ?>
                <img src="<?php echo htmlspecialchars($avatarSrc); ?>" class="avatar" title="Usuário" alt="Avatar do Usuário" onerror="this.src='../img/perfil/usuario.png'">
            <?php else: ?>
                <a href="../Front/login.php" class="login-btn">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($user): ?>
    <div class="sidebar" id="sidebar">
        <button class="close-btn" onclick="closeSidebar()">&times;</button>
        <nav>
            <ul>
                <li><a href="../Front">Início</a></li>
                <?php if (!isset($user['tipoUsuario']) || $user['tipoUsuario'] == 'aluno'): ?>
                <?php else: ?>
                <li><a href="../Front/salas.php">Salas de aula</a></li>
                <?php endif; ?>
                <li><a href="../Front/materias.php">Materias</a></li>
                <li><a href="../Front/dashboard.php">Dashboard</a></li>
                <li><a href="../Front/perfil.php">Perfil</a></li>
                <li><a href="../Front/configuracoes.php">Configurações</a></li>
                <?php if (isset($user['tipoUsuario']) && $user['tipoUsuario'] === 'admin'): ?>
                    <li><a href="../Front/configuracoesDev.php">ConfiguraçõesDev</a></li>
                <?php endif; ?>
                <li><a href="../Back/logout.php">Sair</a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>

    <script src="../Js/sidebar.js"></script>
  
</body>

</html>