<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Back/conexao.php';
require_once __DIR__ . '/../Back/preferencias.php';

if (!function_exists('getFotoSrc')) {
    function getFotoSrc($foto)
    {
        if (empty($foto)) {
            return '../img/perfil/usuario.png';
        }

        $foto = trim($foto);

        if (preg_match('#^(https?://|//|/|[A-Za-z]:\\\\)#', $foto)) {
            return $foto;
        }

        return '../' . ltrim($foto, '/.');
    }
}

$user = null;

if (!empty($_SESSION['idUsuario'])) {
    $uid = (int) $_SESSION['idUsuario'];

    $sql = 'SELECT idUsuario, nomeUsuario, identificador, tipoUsuario, fotoUsuario 
            FROM usuario 
            WHERE idUsuario = ? 
            LIMIT 1';

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $uid);
        $stmt->execute();

        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        $stmt->close();
    }
}
?>

<link rel="stylesheet" href="../css/sidebar.css">

<header class="homebar">
    <div class="left">
        <button class="icon" onclick="openSidebar()" aria-label="Abrir menu">☰</button>

        <span class="logo">MyClass</span>

        <nav class="nav">
            <a href="../Front/index.php">Início</a>
            <a href="../Front/projetos.php">Projetos</a>
            <a href="../Front/contato.php">Contato</a>
        </nav>
    </div>

    <div class="actions">
        <?php if ($user): ?>

            <?php if ($preferencias['notificacoes'] === 'ativadas'): ?>
    <button class="icon" title="Notificações">🔔</button>
<?php endif; ?>

<a href="../Front/perfil.php">
    <?php if ($preferencias['mostrarFoto'] === 'sim'): ?>

        <img 
            src="<?php echo htmlspecialchars(getFotoSrc($user['fotoUsuario'] ?? null)); ?>" 
            class="avatar" 
            title="Usuário" 
            alt="Avatar do Usuário"
            onerror="this.src='../img/perfil/usuario.png'"
        >

    <?php else: ?>

        <span class="avatar avatar-texto">
            <?php echo strtoupper(substr($user['nomeUsuario'], 0, 1)); ?>
        </span>

    <?php endif; ?>
</a>

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
            <li><a href="../Front/index.php">Início</a></li>

            <li><a href="../Front/salas.php">Salas de aula</a></li>

            <li><a href="../Front/materias.php">Matérias</a></li>
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