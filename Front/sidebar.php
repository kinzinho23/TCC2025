<?php
session_start();
require_once __DIR__ . '/../Back/conexao.php';
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
            <button class="icon" onclick="toggleSidebar()" aria-label="Abrir menu">☰</button>
            <span class="logo">MyClass</span>
            <nav class="nav">
                <a href="#">Início</a>
                <a href="#">Projetos</a>
                <a href=".">Contato</a>
            </nav>
        </div>
        <?php if (!isset($_SESSION['usuario'])): ?>
        <div class="actions">
            <button class="icon" title="Notificações">🔔</button>
            <!-- Colocar img aqui -->
            <img src="../img/gatobobo.jpg" class="avatar" title="Usuário" alt="Avatar do Usuário">
        </div>
        <?php else: ?>
        <div class="actions">
            <a href="../Front/login.php" class="login-btn">Login</a>
        </div>
        <?php endif; ?>

    </header>
<?php if (!isset($_SESSION['usuario'])): ?>
    <div class="sidebar" id="sidebar">
        <button class="close-btn" onclick="closeSidebar()">&times;</button>
        <!-- Conteúdo da sidebar -->
        <nav>
            <ul>
                <li><a href="../Front">Início</a></li>
                <li><a href="../Front/salas.php">Salas de aula</a></li>
                <li><a href="../Front/materias.php">Materias</a></li>
                <li><a href="../Front/dashboard.php">Dashboard</a></li>
                <li><a href="../Front/perfil.php">Perfil</a></li>
                <li><a href="../Front/configuracoes.php">Configurações</a></li>
                <?php //if (isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'dev'): ?>
                <li><a href="../Front/configuracoesDev.php">ConfiguraçõesDev</a></li>
                <?php //endif; ?>
                <li><a href="../Back/sair.php">Sair</a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>

    <script src="../Js/sidebar.js"></script>
  
</body>

</html>