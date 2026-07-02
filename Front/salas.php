<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
include("../Back/conexao.php");
include("../Back/preferencias.php");

$userRole = $_SESSION['tipoUsuario'] ?? null;
$podeAdicionar = in_array($userRole, ['admin', 'coordenacao']);


$sqlSalas = "
SELECT 
    idSala,
    nomeSala,
    stts,
    tipoSala
FROM salas
ORDER BY idSala ASC
";

$resultSalas = $conn->query($sqlSalas);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/salas.css">
    <link rel="shortcut icon" href="../img/favicon.ico"type="image/x-icon">
    <title>MyClass - Mapa de Sala</title>
</head>
<body>

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="salas-container">

    <div class="salas-header">
        <div>
            <h1>Mapa de sala</h1>
            <p>Gerencie os mapas das turmas disponíveis.</p>
        </div>

        <?php if ($podeAdicionar): ?>
            <a href="adicionarSala.php" class="btn-add">+ Nova Turma</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="classroom-list">

        <?php if ($resultSalas && $resultSalas->num_rows > 0): ?>

            <?php while ($sala = $resultSalas->fetch_assoc()): ?>

                <?php
                    $statusClass = 'livre';
                    $icone = '🏫';

                    if ($sala['stts'] === 'Em uso') {
                        $statusClass = 'uso';
                        $icone = '💻';
                    } else if ($sala['stts'] === 'Agendada') {
                        $statusClass = 'agendada';
                        $icone = '📚';
                    } else if ($sala['stts'] === 'Manutenção') {
                        $statusClass = 'manutencao';
                        $icone = '🛠️';
                    }
                ?>

                <div class="classroom-card">
                    <div class="card-top">
                        <span class="class-icon">
                            <?php echo $icone; ?>
                        </span>

                        <span class="status <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($sala['stts']); ?>
                        </span>
                    </div>

                    <h2>
                        <?php echo htmlspecialchars($sala['nomeSala']); ?>
                    </h2>

                    <p>Acessar ambiente da turma.</p>

                    <a 
                        href="salaDetalhe.php?id=<?php echo $sala['idSala']; ?>" 
                        class="enter"
                    >
                        Entrar
                    </a>
                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="classroom-card">
                <div class="card-top">
                    <span class="class-icon">🏫</span>
                    <span class="status livre">Vazio</span>
                </div>

                <h2>Nenhuma sala cadastrada</h2>
                <p>Crie uma nova turma para começar.</p>
            </div>

        <?php endif; ?>


        <?php if ($podeAdicionar): ?>
            <div class="classroom-card add-card">
                <span class="add-icon">+</span>
                <h2>Nova turma</h2>
                <p>Adicionar turma ao sistema.</p>
                <a href="adicionarSala.php" class="enter">Adicionar</a>
            </div>
        <?php endif; ?>

    </div>

</main>

<script src="../Js/modal.js"></script>

</body>
</html>