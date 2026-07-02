<?php 
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../Back/conexao.php';
require_once __DIR__ . '/../Back/preferencias.php';

$materia = null;
$salasVinculadas = [];

if (empty($_GET['id'])) {

    $error = 'Matéria não especificada.';

} else {

    $id = (int) $_GET['id'];

    $sql = "
    SELECT 
        m.idMateria, 
        m.nomeMateria, 
        m.codigoMateria,
        m.tipo,
        m.cargaHoraria,
        m.detalhesMateria,
        m.stts,
        m.idUsuario AS professorId, 
        u.nomeUsuario AS professorName
    FROM materias m
    LEFT JOIN usuario u ON m.idUsuario = u.idUsuario
    WHERE m.idMateria = ? 
    LIMIT 1
    ";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $res = $stmt->get_result();
        $materia = $res->fetch_assoc();

        $stmt->close();

        if (!$materia) {

            $error = 'Matéria não encontrada.';

        } else {

            $sqlSalas = "
            SELECT 
                idSala,
                nomeSala,
                stts
            FROM salas
            WHERE idMateria = ?
            ORDER BY nomeSala ASC
            ";

            if ($stmtSalas = $conn->prepare($sqlSalas)) {

                $stmtSalas->bind_param('i', $id);
                $stmtSalas->execute();

                $resSalas = $stmtSalas->get_result();

                while ($sala = $resSalas->fetch_assoc()) {
                    $salasVinculadas[] = $sala;
                }

                $stmtSalas->close();

            }

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
    <title>MyClass - Detalhes da Matéria</title>
    <link rel="stylesheet" href="../css/materias.css">
    <link rel="shortcut icon" href="../img/favicon.ico"type="image/x-icon">
</head>

<body class="<?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="materia-detalhe-container">

    <?php if (!empty($error)): ?>

        <section class="materia-detalhe-card">
            <h1>Ops...</h1>
            <p><?php echo htmlspecialchars($error); ?></p>

            <a href="materias.php" class="btn-voltar-materia">
                Voltar
            </a>
        </section>

    <?php else: ?>

        <section class="materia-hero">

            <div>
                <span class="materia-status">
                    <?php echo htmlspecialchars($materia['stts'] ?? 'ativa'); ?>
                </span>

                <h1>
                    <?php echo htmlspecialchars($materia['nomeMateria']); ?>
                </h1>

                <p>
                    <?php 
                        if (!empty($materia['detalhesMateria'])) {
                            echo nl2br(htmlspecialchars($materia['detalhesMateria']));
                        } else {
                            echo 'Nenhuma descrição foi cadastrada para esta matéria.';
                        }
                    ?>
                </p>
            </div>

            <a href="materias.php" class="btn-voltar-materia">
                Voltar
            </a>

        </section>

        <section class="materia-info-grid">

            <div class="materia-info-card">
                <span>👨‍🏫</span>
                <small>Professor</small>
                <strong>
                    <?php echo htmlspecialchars($materia['professorName'] ?? 'Sem professor'); ?>
                </strong>
            </div>

            <div class="materia-info-card">
                <span>🏷️</span>
                <small>Código</small>
                <strong>
                    <?php echo htmlspecialchars($materia['codigoMateria'] ?? 'Sem código'); ?>
                </strong>
            </div>

            <div class="materia-info-card">
                <span>📘</span>
                <small>Tipo</small>
                <strong>
                    <?php echo htmlspecialchars($materia['tipo'] ?? 'Não definido'); ?>
                </strong>
            </div>

            <div class="materia-info-card">
                <span>⏱️</span>
                <small>Carga horária</small>
                <strong>
                    <?php echo htmlspecialchars($materia['cargaHoraria'] ?? 0); ?>h
                </strong>
            </div>

        </section>

        <section class="materia-detalhe-card">

            <div class="materia-section-top">
                <div>
                    <h2>Mapas de sala vinculados</h2>
                    <p>Veja em quais mapas de sala essa matéria está sendo usada.</p>
                </div>
            </div>

            <?php if (empty($salasVinculadas)): ?>

                <div class="materia-empty">
                    <span>🏫</span>
                    <h3>Nenhum mapa vinculado</h3>
                    <p>Essa matéria ainda não foi adicionada a nenhum mapa de sala.</p>
                </div>

            <?php else: ?>

                <div class="salas-vinculadas-lista">

                    <?php foreach ($salasVinculadas as $sala): ?>

                        <a 
                            href="salaDetalhe.php?id=<?php echo $sala['idSala']; ?>" 
                            class="sala-vinculada-item"
                        >

                            <div>
                                <strong>
                                    <?php echo htmlspecialchars($sala['nomeSala']); ?>
                                </strong>

                                <small>
                                    Mapa de sala vinculado a esta matéria
                                </small>
                            </div>

                            <span>
                                <?php echo htmlspecialchars($sala['stts']); ?>
                            </span>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </section>

    <?php endif; ?>

</main>

</body>
</html>