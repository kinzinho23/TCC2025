<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../Back/conexao.php';

$materia = null;
if (empty($_GET['id'])) {
    $error = 'Matéria não especificada.';
} else {
    $id = (int) $_GET['id'];
    $sql = 'SELECT m.idMateria, m.nomeMateria, m.detalhesMateria, m.idUsuario AS professorId, u.nomeUsuario AS professorName
            FROM materias m
            LEFT JOIN usuario u ON m.idUsuario = u.idUsuario
            WHERE m.idMateria = ? LIMIT 1';
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $materia = $res->fetch_assoc();
        $stmt->close();
        if (!$materia) $error = 'Matéria não encontrada.';
    } else {
        $error = 'Erro ao preparar consulta.';
    }
}

$user = null;
if (!empty($_SESSION['idUsuario'])) {
    $uid = (int) $_SESSION['idUsuario'];
    if ($stmt = $conn->prepare('SELECT idUsuario, nomeUsuario, tipoUsuario FROM usuario WHERE idUsuario = ? LIMIT 1')) {
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        $user = $res->fetch_assoc();
        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/Materias.css">
    <title>Myclass - Detalhes da Matéria</title>
</head>
<body>
    <header>
        <?php include 'sidebar.php'; ?>
    </header>

    <main>
        <?php if (!empty($error)): ?>
            <div class="card"><p><?php echo htmlspecialchars($error); ?></p></div>
        <?php else: ?>
            <section id="title">
                <h1 ><?php echo htmlspecialchars($materia['nomeMateria']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($materia['detalhesMateria'])); ?></p>
                <p><strong>Professor:</strong> <?php echo htmlspecialchars($materia['professorName'] ?? '—'); ?></p>

                <div style="margin-top:12px;">
                    <?php
               
                    if ($user) {
                        $tipo = isset($user['tipoUsuario']) ? $user['tipoUsuario'] : '';
                        $isOwner = ((int)$user['idUsuario'] === (int)$materia['professorId']);

                        if ($tipo === 'dev' || $tipo === 'coordenacao' || $tipo === 'admin') {
                            ?>
                            <a class="btn btn-primary" href="editarMateria.php?id=<?php echo urlencode($materia['idMateria']); ?>">Editar</a>
                            <form method="post" action="../Back/materias_process.php" style="display:inline; margin-left:8px;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="idMateria" value="<?php echo htmlspecialchars($materia['idMateria']); ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Confirma exclusão da matéria?')">Excluir</button>
                            </form>
                            <?php
                        } elseif ($tipo === 'professor' && $isOwner) {
                            ?>
                            <a class="btn btn-primary" href="editarMateria.php?id=<?php echo urlencode($materia['idMateria']); ?>">Editar</a>
                            <?php
                        } else {
                            echo '<span class="badge">Somente leitura</span>';
                        }
                    } else {
                        echo '<a href="../Front/login.php" class="btn btn-ghost">Entrar para mais ações</a>';
                    }
                    ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

</body>
</html>
