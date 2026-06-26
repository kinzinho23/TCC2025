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
$totalNotificacoes = 0;

if (!empty($_SESSION['idUsuario'])) {

    $uid = (int) $_SESSION['idUsuario'];

    $sql = "
    SELECT 
        idUsuario, 
        nomeUsuario, 
        identificador, 
        tipoUsuario, 
        fotoUsuario 
    FROM usuario 
    WHERE idUsuario = ? 
    LIMIT 1
    ";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('i', $uid);
        $stmt->execute();

        $res = $stmt->get_result();
        $user = $res->fetch_assoc();

        $stmt->close();

    }

}

if ($user) {

    $sqlCount = "
    SELECT COUNT(*) AS total
    FROM notificacoes
    WHERE 
        lida = 0
        AND (
            idUsuario = ?
            OR tipoDestino = ?
        )
    ";

    if ($stmtCount = $conn->prepare($sqlCount)) {

        $stmtCount->bind_param(
            "is",
            $user['idUsuario'],
            $user['tipoUsuario']
        );

        $stmtCount->execute();

        $resCount = $stmtCount->get_result();
        $rowCount = $resCount->fetch_assoc();

        $totalNotificacoes = (int) ($rowCount['total'] ?? 0);

        $stmtCount->close();

    }

}
?>

<link rel="stylesheet" href="../css/sidebar.css">

<header class="homebar">

    <div class="left">

        <button 
            class="icon" 
            onclick="openSidebar()" 
            aria-label="Abrir menu"
        >
            ☰
        </button>

        <span class="logo">
            MyClass
        </span>

        <nav class="nav">
            <a href="../Front/index.php">Início</a>
            <a href="../Front/projetos.php">Projetos</a>
            <a href="../Front/contato.php">Contato</a>
        </nav>

    </div>

    <div class="actions">

        <?php if ($user): ?>

            <?php if (($preferencias['notificacoes'] ?? 'ativadas') === 'ativadas'): ?>

                <a 
                    href="../Front/notificacoes.php" 
                    class="icon notification-icon" 
                    title="Notificações"
                >
                    🔔

                    <span 
                        class="notification-badge" 
                        id="notificationBadge"
                        style="<?php echo $totalNotificacoes > 0 ? '' : 'display:none;'; ?>"
                    >
                        <?php echo $totalNotificacoes; ?>
                    </span>
                </a>

            <?php endif; ?>

            <a 
                href="../Back/logout.php" 
                class="icon logout-icon" 
                title="Sair"
                onclick="return confirm('Deseja realmente sair da sua conta?')"
            >
                ⏻
            </a>

            <a href="../Front/perfil.php">

                <?php if (($preferencias['mostrarFoto'] ?? 'sim') === 'sim'): ?>

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

            <a href="../Front/login.php" class="login-btn">
                Login
            </a>

        <?php endif; ?>

    </div>

</header>

<?php if ($user): ?>

<div class="sidebar" id="sidebar">

    <button 
        class="close-btn" 
        onclick="closeSidebar()"
    >
        &times;
    </button>

    <nav>

        <ul>

            <li>
                <a href="../Front/index.php">Início</a>
            </li>

            <li>
                <a href="../Front/salas.php">Mapa de Sala</a>
            </li>

            <li>
                <a href="../Front/materias.php">Matérias</a>
            </li>

            <li>
                <a href="../Front/dashboard.php">Dashboard</a>
            </li>

            <li>
                <a href="../Front/perfil.php">Perfil</a>
            </li>

            <li>
                <a href="../Front/configuracoes.php">Configurações</a>
            </li>

            <?php if (isset($user['tipoUsuario']) && $user['tipoUsuario'] === 'admin'): ?>

                <li>
                    <a href="../Front/configuracoesDev.php">ConfiguraçõesDev</a>
                </li>

            <?php endif; ?>

            <li>
                <a 
                    href="../Back/logout.php"
                    onclick="return confirm('Deseja realmente sair da sua conta?')"
                >
                    Sair
                </a>
            </li>

        </ul>

    </nav>

</div>

<?php endif; ?>

<script src="../Js/sidebar.js"></script>

<?php if (($preferencias['temaSite'] ?? 'claro') === 'escuro'): ?>

<script>
    document.body.classList.add("tema-escuro");
</script>

<?php endif; ?>

<script>

function atualizarNotificacoes() {

    fetch("../Back/notificacoes_count.php")
        .then(response => response.json())
        .then(data => {

            const badge = document.getElementById("notificationBadge");

            if (!badge) {
                return;
            }

            const total = parseInt(data.total);

            if (total > 0) {

                badge.textContent = total;
                badge.style.display = "flex";

            } else {

                badge.textContent = "";
                badge.style.display = "none";

            }

        })
        .catch(error => {
            console.log("Erro ao buscar notificações:", error);
        });

}

setInterval(atualizarNotificacoes, 10000);
atualizarNotificacoes();

</script>