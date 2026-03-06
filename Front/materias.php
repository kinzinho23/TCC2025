<?php
include '../Back/conexao.php';

$stmt = $conn->prepare('SELECT m.idMateria, stts, m.nomeMateria, m.codigoMateria, m.detalhesMateria, u.nomeUsuario AS professor FROM materias m LEFT JOIN usuario u ON m.idUsuario = u.idUsuario'
);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/materias.css">
    <title>Myclass - Matérias</title>
</head>
<body>
    <header>
        <?php include 'sidebar.php'; ?>
    </header>

    <main>
        <div id="title">
        <h1>Matérias</h1>
        <p>Aqui você pode acessar as matérias disponíveis, visualizar conteúdos, e acompanhar seu progresso em cada disciplina.</p>
        </div>
        <div class="materias-list">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo '<div class="materia-item" onclick="window.location.href=\'materiaDetalhes.php?id=' . $row['idMateria'] . '\'">';
            echo '<h2 class="materia-name">' . htmlspecialchars($row['nomeMateria']) . '</h2>';
            echo '<h5 class="materia-info">' . htmlspecialchars($row['detalhesMateria']) . '</h5>';
            echo '<h5 class="materia-codigo">'. htmlspecialchars($row['codigoMateria']) . '</h5>';
            echo '<span class="materia-status">' . htmlspecialchars($row['stts']) . '</span>';
            echo '<h6 class="materia-professor">Prof. ' . htmlspecialchars($row['professor']) . '</h6>';
            echo '</div>';
        }
        ?>
        </div>
    </main>
</body>
</html>