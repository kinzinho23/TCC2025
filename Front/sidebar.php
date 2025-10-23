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
                <a href="#">Contato</a>
            </nav>
        </div>
        
        <div class="actions">
            <button class="icon" title="Notificações">🔔</button>
            <!-- Colocar img aqui -->
            <img src="../img/gatobobo.jpg" class="avatar" title="Usuário" alt="Avatar do Usuário">
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <button class="close-btn" onclick="closeSidebar()">&times;</button>
        <!-- Conteúdo da sidebar -->
        <nav>
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Salas de aula</a></li>
                <li><a href="#">Materias</a></li>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Perfil</a></li>
                <li><a href="#">Configurações</a></li>
                <li><a href="#">Sair</a></li>
            </ul>
        </nav>
    </div>
    <script src="../Js/sidebar.js"></script>
</body>

</html>