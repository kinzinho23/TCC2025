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
            <span class="logo">MeuApp</span>
            <nav class="nav">
                <a href="#">Início</a>
                <a href="#">Projetos</a>
                <a href="#">Contato</a>
            </nav>
        </div>
        
        <div class="actions">
            <button class="icon" title="Notificações">🔔</button>
            <div class="avatar" title="Usuário"></div>
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <button class="close-btn" onclick="closeSidebar()">&times;</button>
        <!-- Conteúdo da sidebar -->
        <nav>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">Perfil</a></li>
                <li><a href="#">Configurações</a></li>
            </ul>
        </nav>
    </div>
    <script src="../Js/sidebar.js"></script>
    <script>
        function toggleSidebar() { var sb = document.getElementById('sidebar'); sb.classList.toggle('open'); }
        function closeSidebar() { var sb = document.getElementById('sidebar'); sb.classList.remove('open'); }
    </script>
</body>

</html>