<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" 
    crossorigin="anonymous">
    <link rel="stylesheet" href="../css/Home.css">
    <title>Início</title>
</head>
<body>
    <header>
        <button class="bi bi-list btnMenu" onclick="openSidebar()"></button>    
        <?php include 'HomeBar.php'; ?> 
    </header>

    <main>
        <div class="imgHome">
            <img src="../img/backgroundHome.svg" alt="Imagem inicial do site">
            <div id="Title">
            <h1>Seu gerenciador </h1>
            <h1>de </h1>
            <h1>sala de aula</h1>
            </div>
        </div> 
        <div class="sobre fade-in" id="sobre">
            <h2>Sobre nós</h2>
            <div class="sobre-divider"></div>
            <h3>Inovação e praticidade para a gestão escolar</h3>
            <p>
                O <strong>Sala de Aula</strong> é uma plataforma web criada para transformar a experiência de professores, alunos e administradores. Nosso objetivo é simplificar processos, promover a colaboração e potencializar o ensino com tecnologia de ponta.
            </p>
            <div class="sobre-beneficios">
                <div class="beneficio-item">
                    <span class="beneficio-icon"> 
                        <svg width="32" height="32" fill="#dcaf8b" viewBox="0 0 24 24"><path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 8h14v-2H7v2zm0-4h14v-2H7v2zm0-6v2h14V7H7z"/></svg>
                    </span>
                    <span><strong>Gestão eficiente:</strong> Organize turmas, disciplinas e atividades de forma centralizada.</span>
                </div>
                <div class="beneficio-item">
                    <span class="beneficio-icon">
                        <svg width="32" height="32" fill="#dcaf8b" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </span>
                    <span><strong>Comunicação integrada:</strong> Facilite o contato entre todos os membros da comunidade escolar.</span>
                </div>
                <div class="beneficio-item">
                    <span class="beneficio-icon">
                        <svg width="32" height="32" fill="#dcaf8b" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/></svg>
                    </span>
                    <span><strong>Ambiente intuitivo:</strong> Interface moderna, responsiva e fácil de usar.</span>
                </div>
                <div class="beneficio-item">
                    <span class="beneficio-icon">
                        <svg width="32" height="32" fill="#dcaf8b" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-2.83.48-5.48-1.51-5.96-4.34-.07-.39.23-.76.63-.76.31 0 .58.22.63.52.41 2.36 2.7 3.97 5.06 3.56 2.36-.41 3.97-2.7 3.56-5.06-.41-2.36-2.7-3.97-5.06-3.56-.39.07-.76-.23-.76-.63 0-.31.22-.58.52-.63 2.83-.48 5.48 1.51 5.96 4.34.07.39-.23.76-.63.76-.31 0-.58-.22-.63-.52-.41-2.36-2.7-3.97-5.06-3.56-2.36.41-3.97 2.7-3.56 5.06.41 2.36 2.7 3.97 5.06 3.56.39-.07.76.23.76.63 0 .31-.22.58-.52.63z"/></svg>
                    </span>
                    <span><strong>Foco na inovação:</strong> Soluções tecnológicas para otimizar o tempo e melhorar resultados.</span>
                </div>
            </div>
            <p class="sobre-missao">
                Nossa missão é impulsionar a educação, tornando a gestão escolar mais simples, organizada e inovadora.
            </p>
        </div>
    </main>
       

    <footer>

    </footer>
    <script src="../Js/sidebar.js"></script>
</body>
</html>