<?php 
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../Back/conexao.php';

$materia = null;

if (empty($_GET['id'])) {

    $error = 'Matéria não especificada.';

} else {

    $id = (int) $_GET['id'];

    $sql = '
    SELECT 
        m.idMateria, 
        m.nomeMateria, 
        m.detalhesMateria, 
        m.idUsuario AS professorId, 
        u.nomeUsuario AS professorName
    FROM materias m
    LEFT JOIN usuario u ON m.idUsuario = u.idUsuario
    WHERE m.idMateria = ? 
    LIMIT 1
    ';

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $res = $stmt->get_result();
        $materia = $res->fetch_assoc();

        $stmt->close();

        if (!$materia) {
            $error = 'Matéria não encontrada.';
        }

    } else {

        $error = 'Erro ao preparar consulta.';

    }

}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myclass - Detalhes da Matéria</title>
    <link rel="stylesheet" href="../css/materias.css">
</head>
<body>

    <header>
        <?php include 'sidebar.php'; ?>
    </header>

    <main>

        <?php if (!empty($error)): ?>

            <div class="card">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>

        <?php else: ?>

            <section id="title">

                <h1>
                    <?php echo htmlspecialchars($materia['nomeMateria']); ?>
                </h1>

                <p>
                    <?php echo nl2br(htmlspecialchars($materia['detalhesMateria'])); ?>
                </p>

                <p>
                    <strong>Professor:</strong> 
                    <?php echo htmlspecialchars($materia['professorName'] ?? '—'); ?>
                </p>

            </section>

        <?php endif; ?>

    </main>

</body>
</html>