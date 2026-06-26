<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
include("../Back/conexao.php");
include("../Back/preferencias.php");

$userRole = $_SESSION['tipoUsuario'] ?? null;

if (!in_array($userRole, ['admin', 'coordenacao'])) {
    header("Location: salas.php?error=" . urlencode("Acesso negado"));
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyClass - Adicionar Sala</title>
    <link rel="stylesheet" href="../css/salas.css">
</head>
<body>

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="salas-container">

    <div class="salas-header">
        <div>
            <h1>Adicionar Sala</h1>
            <p>Cadastre uma nova sala de aula no sistema.</p>
        </div>

        <a href="salas.php" class="btn-add">Voltar</a>
    </div>

    <div class="classroom-card">

        <form action="../Back/salas_process.php" method="POST">

            <input type="hidden" name="action" value="create">

            <label>Nome da sala</label>
            <input 
                type="text" 
                name="nomeSala" 
                placeholder="Ex: Turma 1, 3º DS, Laboratório 2"
                required
            >

          <label>Capacidade</label>

<input 
    type="number" 
    name="capacidade" 
    placeholder="Ex: 30"
    min="1"
    max="30"
    required
>

         <label>Tipo da sala</label>

<select name="tipoSala" required>
    <option value="">Selecione o tipo da sala</option>

    <option value="Sala comum">
        Sala comum
    </option>

    <option value="Laboratório">
        Laboratório
    </option>

    <option value="Auditório">
        Auditório
    </option>

    <option value="Biblioteca">
        Biblioteca
    </option>

    <option value="Sala multimídia">
        Sala multimídia
    </option>
</select>

            <label>Status</label>
            <select name="stts" required>
                <option value="Livre">Livre</option>
                <option value="Em uso">Em uso</option>
                <option value="Agendada">Agendada</option>
                <option value="Manutenção">Manutenção</option>
            </select>

            <button type="submit" class="enter">
                Salvar sala
            </button>

        </form>

    </div>

</main>

</body>
</html>