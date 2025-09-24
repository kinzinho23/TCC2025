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
    </main>

    <footer>

    </footer>
    <script src="../Js/sidebar.js"></script>
</body>
</html>