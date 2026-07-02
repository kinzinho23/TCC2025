<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../Back/conexao.php';
require_once __DIR__ . '/../Back/preferencias.php';

if (empty($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

$sqlUser = "
SELECT 
    idUsuario,
    nomeUsuario,
    tipoUsuario
FROM usuario
WHERE idUsuario = ?
LIMIT 1
";

$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("i", $idUsuario);
$stmtUser->execute();

$resUser = $stmtUser->get_result();
$user = $resUser->fetch_assoc();

$stmtUser->close();

if (!$user) {
    header("Location: login.php");
    exit;
}

$nomeUsuario = $user['nomeUsuario'];
$tipoUsuario = $user['tipoUsuario'];

function contar($conn, $sql, $params = [], $types = '')
{
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return 0;
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();

    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    $stmt->close();

    return (int) ($row['total'] ?? 0);
}

$totalMaterias = 0;
$totalSalas = 0;
$totalUsuarios = 0;
$totalProfessores = 0;
$totalAlunosCarteiras = 0;

if (in_array($tipoUsuario, ['admin', 'coordenacao'])) {

    $totalUsuarios = contar($conn, "SELECT COUNT(*) AS total FROM usuario");
    $totalMaterias = contar($conn, "SELECT COUNT(*) AS total FROM materias");
    $totalSalas = contar($conn, "SELECT COUNT(*) AS total FROM salas");
    $totalProfessores = contar($conn, "SELECT COUNT(*) AS total FROM usuario WHERE tipoUsuario = 'professor'");

} elseif ($tipoUsuario === 'professor') {

    $totalMaterias = contar(
        $conn,
        "SELECT COUNT(*) AS total FROM materias WHERE idUsuario = ?",
        [$idUsuario],
        "i"
    );

    $totalSalas = contar(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM salas s
        INNER JOIN materias m ON s.idMateria = m.idMateria
        WHERE m.idUsuario = ?
        ",
        [$idUsuario],
        "i"
    );

    $totalAlunosCarteiras = contar(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM sala_carteiras sc
        INNER JOIN salas s ON sc.idSala = s.idSala
        INNER JOIN materias m ON s.idMateria = m.idMateria
        WHERE m.idUsuario = ?
        ",
        [$idUsuario],
        "i"
    );

} else {

    $totalMaterias = contar($conn, "SELECT COUNT(*) AS total FROM materias");
    $totalSalas = contar($conn, "SELECT COUNT(*) AS total FROM salas");

    $minhasCarteiras = contar(
        $conn,
        "SELECT COUNT(*) AS total FROM sala_carteiras WHERE idUsuario = ?",
        [$idUsuario],
        "i"
    );

}

$materias = [];

if ($tipoUsuario === 'professor') {

    $sqlMaterias = "
    SELECT 
        idMateria,
        nomeMateria,
        codigoMateria,
        tipo,
        cargaHoraria,
        stts
    FROM materias
    WHERE idUsuario = ?
    ORDER BY idMateria DESC
    LIMIT 6
    ";

    $stmtMaterias = $conn->prepare($sqlMaterias);
    $stmtMaterias->bind_param("i", $idUsuario);

} else {

    $sqlMaterias = "
    SELECT 
        idMateria,
        nomeMateria,
        codigoMateria,
        tipo,
        cargaHoraria,
        stts
    FROM materias
    ORDER BY idMateria DESC
    LIMIT 6
    ";

    $stmtMaterias = $conn->prepare($sqlMaterias);

}

if ($stmtMaterias) {
    $stmtMaterias->execute();
    $resMaterias = $stmtMaterias->get_result();

    while ($row = $resMaterias->fetch_assoc()) {
        $materias[] = $row;
    }

    $stmtMaterias->close();
}

$salas = [];

if ($tipoUsuario === 'professor') {

    $sqlSalas = "
    SELECT 
        s.idSala,
        s.nomeSala,
        s.stts,
        m.nomeMateria
    FROM salas s
    INNER JOIN materias m ON s.idMateria = m.idMateria
    WHERE m.idUsuario = ?
    ORDER BY s.idSala DESC
    LIMIT 6
    ";

    $stmtSalas = $conn->prepare($sqlSalas);
    $stmtSalas->bind_param("i", $idUsuario);

} elseif ($tipoUsuario === 'aluno') {

    $sqlSalas = "
    SELECT 
        s.idSala,
        s.nomeSala,
        s.stts,
        sc.numeroCarteira,
        m.nomeMateria
    FROM sala_carteiras sc
    INNER JOIN salas s ON sc.idSala = s.idSala
    LEFT JOIN materias m ON s.idMateria = m.idMateria
    WHERE sc.idUsuario = ?
    ORDER BY s.idSala DESC
    LIMIT 6
    ";

    $stmtSalas = $conn->prepare($sqlSalas);
    $stmtSalas->bind_param("i", $idUsuario);

} else {

    $sqlSalas = "
    SELECT 
        s.idSala,
        s.nomeSala,
        s.stts,
        m.nomeMateria
    FROM salas s
    LEFT JOIN materias m ON s.idMateria = m.idMateria
    ORDER BY s.idSala DESC
    LIMIT 6
    ";

    $stmtSalas = $conn->prepare($sqlSalas);

}

if ($stmtSalas) {
    $stmtSalas->execute();
    $resSalas = $stmtSalas->get_result();

    while ($row = $resSalas->fetch_assoc()) {
        $salas[] = $row;
    }

    $stmtSalas->close();
}

$usuariosRecentes = [];

if (in_array($tipoUsuario, ['admin', 'coordenacao'])) {

    $sqlUsuarios = "
    SELECT 
        idUsuario,
        nomeUsuario,
        tipoUsuario,
        identificador
    FROM usuario
    ORDER BY idUsuario DESC
    LIMIT 6
    ";

    $resUsuarios = $conn->query($sqlUsuarios);

    if ($resUsuarios) {
        while ($row = $resUsuarios->fetch_assoc()) {
            $usuariosRecentes[] = $row;
        }
    }

}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyClass - Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/favicon.ico"type="image/x-icon">
</head>

<body class="<?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="dashboard-container">

    <section class="dashboard-header">
        <div>
            <h1>Olá, <?php echo htmlspecialchars($nomeUsuario); ?> 👋</h1>

            <p>
                <?php if ($tipoUsuario === 'aluno'): ?>
                    Acompanhe suas matérias e salas de aula.
                <?php elseif ($tipoUsuario === 'professor'): ?>
                    Gerencie suas matérias e acompanhe suas turmas.
                <?php else: ?>
                    Visão geral do sistema MyClass.
                <?php endif; ?>
            </p>
        </div>

        <span class="user-badge">
            <?php echo htmlspecialchars(ucfirst($tipoUsuario)); ?>
        </span>
    </section>

    <section class="dashboard-cards">

        <?php if (in_array($tipoUsuario, ['admin', 'coordenacao'])): ?>

            <div class="dash-card">
                <span class="card-icon">👥</span>
                <h3>Usuários</h3>
                <strong><?php echo $totalUsuarios; ?></strong>
                <p>Cadastrados no sistema</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">📚</span>
                <h3>Matérias</h3>
                <strong><?php echo $totalMaterias; ?></strong>
                <p>Matérias cadastradas</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🏫</span>
                <h3>Mapas de sala</h3>
                <strong><?php echo $totalSalas; ?></strong>
                <p>Salas disponíveis</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">👨‍🏫</span>
                <h3>Professores</h3>
                <strong><?php echo $totalProfessores; ?></strong>
                <p>Professores registrados</p>
            </div>

        <?php elseif ($tipoUsuario === 'professor'): ?>

            <div class="dash-card">
                <span class="card-icon">📚</span>
                <h3>Minhas matérias</h3>
                <strong><?php echo $totalMaterias; ?></strong>
                <p>Vinculadas ao seu usuário</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🏫</span>
                <h3>Minhas salas</h3>
                <strong><?php echo $totalSalas; ?></strong>
                <p>Salas vinculadas às matérias</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🪑</span>
                <h3>Alunos em sala</h3>
                <strong><?php echo $totalAlunosCarteiras; ?></strong>
                <p>Carteiras ocupadas</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🔔</span>
                <h3>Notificações</h3>
                <strong><?php echo ucfirst($preferencias['notificacoes'] ?? 'ativadas'); ?></strong>
                <p>Preferência atual</p>
            </div>

        <?php else: ?>

            <div class="dash-card">
                <span class="card-icon">📚</span>
                <h3>Matérias</h3>
                <strong><?php echo $totalMaterias; ?></strong>
                <p>Disponíveis para acesso</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🏫</span>
                <h3>Mapas de sala</h3>
                <strong><?php echo $totalSalas; ?></strong>
                <p>Mapas cadastrados</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🪑</span>
                <h3>Minhas carteiras</h3>
                <strong><?php echo $minhasCarteiras ?? 0; ?></strong>
                <p>Salas onde você entrou</p>
            </div>

            <div class="dash-card">
                <span class="card-icon">🔔</span>
                <h3>Notificações</h3>
                <strong><?php echo ucfirst($preferencias['notificacoes'] ?? 'ativadas'); ?></strong>
                <p>Preferência atual</p>
            </div>

        <?php endif; ?>

    </section>

    <section class="dashboard-grid">

        <div class="dashboard-section">
            <div class="section-top">
                <h2>
                    <?php echo $tipoUsuario === 'professor' ? 'Minhas matérias' : 'Matérias recentes'; ?>
                </h2>

                <a href="materias.php">Ver todas</a>
            </div>

            <?php if (empty($materias)): ?>

                <p class="empty-message">Nenhuma matéria encontrada.</p>

            <?php else: ?>

                <div class="mini-list">
                    <?php foreach ($materias as $materia): ?>
                        <a 
                            href="materiaDetalhes.php?id=<?php echo $materia['idMateria']; ?>" 
                            class="mini-item"
                        >
                            <div>
                                <strong><?php echo htmlspecialchars($materia['nomeMateria']); ?></strong>

                                <span>
                                    <?php echo htmlspecialchars($materia['codigoMateria'] ?? 'Sem código'); ?>
                                </span>
                            </div>

                            <small>
                                <?php echo htmlspecialchars($materia['stts'] ?? 'ativa'); ?>
                            </small>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>

        <div class="dashboard-section">
            <div class="section-top">
                <h2>
                    <?php echo $tipoUsuario === 'aluno' ? 'Minhas salas' : 'Salas recentes'; ?>
                </h2>

                <a href="salas.php">Ver todas</a>
            </div>

            <?php if (empty($salas)): ?>

                <p class="empty-message">
                    <?php echo $tipoUsuario === 'aluno' ? 'Você ainda não entrou em nenhuma sala.' : 'Nenhuma sala encontrada.'; ?>
                </p>

            <?php else: ?>

                <div class="mini-list">
                    <?php foreach ($salas as $sala): ?>
                        <a 
                            href="salaDetalhe.php?id=<?php echo $sala['idSala']; ?>" 
                            class="mini-item"
                        >
                            <div>
                                <strong><?php echo htmlspecialchars($sala['nomeSala']); ?></strong>

                                <span>
                                    <?php echo htmlspecialchars($sala['nomeMateria'] ?? 'Sem matéria'); ?>

                                    <?php if (isset($sala['numeroCarteira'])): ?>
                                        • Carteira <?php echo htmlspecialchars($sala['numeroCarteira']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>

                            <small>
                                <?php echo htmlspecialchars($sala['stts'] ?? 'Livre'); ?>
                            </small>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>

    </section>

    <?php if (in_array($tipoUsuario, ['admin', 'coordenacao'])): ?>

        <section class="dashboard-section full">
            <div class="section-top">
                <h2>Usuários recentes</h2>
                <a href="configuracoesDev.php">Gerenciar</a>
            </div>

            <?php if (empty($usuariosRecentes)): ?>

                <p class="empty-message">Nenhum usuário encontrado.</p>

            <?php else: ?>

                <div class="mini-list">
                    <?php foreach ($usuariosRecentes as $usuario): ?>
                        <div class="mini-item">
                            <div>
                                <strong><?php echo htmlspecialchars($usuario['nomeUsuario']); ?></strong>

                                <span>
                                    Identificador: <?php echo htmlspecialchars($usuario['identificador']); ?>
                                </span>
                            </div>

                            <small>
                                <?php echo htmlspecialchars($usuario['tipoUsuario']); ?>
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </section>

    <?php endif; ?>

</main>

</body>
</html>