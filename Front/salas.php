<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
include("../Back/conexao.php");

$userRole = $_SESSION['tipoUsuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/salas.css">
    <title>MyClass - Salas de Aula</title>
</head>
<body>
    <header>
        <?php include 'sidebar.php'; ?>
    </header>
    <main>
        <h1 id="title">Salas de Aula</h1>

        <div class="classroom-list">
            <div class="classroom-item">Turma <?php //echo $turma_id; ?>

             <button class="enter"><a href="#">Entrar</a></button>
        </div>
            <div class="classroom-item">Turma <?php //echo $turma_id + 1; ?>

            <button class="enter"><a href="#">Entrar</a></button>
        </div>
            <div class="classroom-item">Turma <?php //echo $turma_id + 2; ?>

            <button class="enter"><a href="#">Entrar</a></button>
        </div>
        <!-- lembrar de separar o Adicionar -->
         <?php 

         if (in_array($userRole, ['admin', 'coordenacao'])) {
         echo '
            <div class="classroom-item">Adicionar nova turma

            <button id="add-classroom"><a href="#">+</a></button>
        </div>
        ';
        } ?>
        </div>
    </main>
</body>
</html>