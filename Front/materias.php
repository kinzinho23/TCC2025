<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include '../Back/conexao.php';
include '../Back/preferencias.php';

$sql = "
SELECT 
    m.idMateria,
    m.stts,
    m.nomeMateria,
    m.codigoMateria,
    m.detalhesMateria,
    m.tipo,
    m.cargaHoraria,
    u.nomeUsuario AS professor
FROM materias m
LEFT JOIN usuario u ON m.idUsuario = u.idUsuario
ORDER BY m.nomeMateria ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$materias = [];

while ($row = $result->fetch_assoc()) {
    $materias[] = $row;
}

$totalMaterias = count($materias);

$totalAtivas = 0;
$totalSemProfessor = 0;

foreach ($materias as $materia) {
    if (($materia['stts'] ?? '') === 'ativa') {
        $totalAtivas++;
    }

    if (empty($materia['professor'])) {
        $totalSemProfessor++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyClass - Matérias</title>
    <link rel="stylesheet" href="../css/materias.css">
    <link rel="shortcut icon" href="../img/favicon.ico"type="image/x-icon">
</head>

<body class="<?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="materias-container">

    <section class="materias-header">

        <div>
            <span class="page-badge">📚 Área de estudos</span>

            <h1>Matérias</h1>

            <p>
                Consulte as disciplinas cadastradas, veja o professor responsável
                e acesse os detalhes de cada matéria.
            </p>
        </div>

    </section>

    <section class="materias-resumo">

        <div class="resumo-card">
            <span>📘</span>
            <div>
                <strong><?php echo $totalMaterias; ?></strong>
                <p>Matérias cadastradas</p>
            </div>
        </div>

        <div class="resumo-card">
            <span>✅</span>
            <div>
                <strong><?php echo $totalAtivas; ?></strong>
                <p>Matérias ativas</p>
            </div>
        </div>

        <div class="resumo-card">
            <span>👨‍🏫</span>
            <div>
                <strong><?php echo $totalSemProfessor; ?></strong>
                <p>Sem professor</p>
            </div>
        </div>

    </section>

    <section class="materias-toolbar">

        <div>
            <h2>Lista de matérias</h2>
            <p>Clique em uma matéria para visualizar mais informações.</p>
        </div>

        <input 
            type="text" 
            id="buscarMateria" 
            placeholder="Buscar matéria..."
        >

    </section>

    <section class="materias-list" id="materiasList">

        <?php if (empty($materias)): ?>

            <div class="materias-empty">
                <span>📚</span>
                <h2>Nenhuma matéria cadastrada</h2>
                <p>Quando uma matéria for cadastrada, ela aparecerá aqui.</p>
            </div>

        <?php else: ?>

            <?php foreach ($materias as $materia): ?>

                <?php
                    $status = $materia['stts'] ?? 'ativa';
                    $statusClass = strtolower($status);

                    $professor = !empty($materia['professor']) 
                        ? $materia['professor'] 
                        : 'Sem professor';

                    $descricao = !empty($materia['detalhesMateria'])
                        ? $materia['detalhesMateria']
                        : 'Nenhuma descrição cadastrada para esta matéria.';
                ?>

                <article 
                    class="materia-item"
                    data-nome="<?php echo htmlspecialchars(strtolower($materia['nomeMateria'])); ?>"
                    onclick="window.location.href='materiaDetalhes.php?id=<?php echo $materia['idMateria']; ?>'"
                >

                    <div class="materia-card-top">

                        <div class="materia-icon">
                            📘
                        </div>

                        <span class="materia-status <?php echo htmlspecialchars($statusClass); ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </span>

                    </div>

                    <h2 class="materia-name">
                        <?php echo htmlspecialchars($materia['nomeMateria']); ?>
                    </h2>

                    <p class="materia-info">
                        <?php echo htmlspecialchars(mb_strimwidth($descricao, 0, 95, "...")); ?>
                    </p>

                    <div class="materia-tags">

                        <span>
                            <?php echo htmlspecialchars($materia['codigoMateria'] ?? 'Sem código'); ?>
                        </span>

                        <span>
                            <?php echo htmlspecialchars($materia['tipo'] ?? 'Não definido'); ?>
                        </span>

                        <span>
                            <?php echo htmlspecialchars($materia['cargaHoraria'] ?? 0); ?>h
                        </span>

                    </div>

                    <div class="materia-footer">

                        <div class="materia-professor">
                            <span>👨‍🏫</span>

                            <strong>
                                <?php echo htmlspecialchars($professor); ?>
                            </strong>
                        </div>

                        <span class="abrir-materia">
                            Abrir →
                        </span>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </section>

</main>

<script>
const inputBusca = document.getElementById("buscarMateria");
const cards = document.querySelectorAll(".materia-item");

if (inputBusca) {
    inputBusca.addEventListener("input", function() {
        const busca = this.value.toLowerCase();

        cards.forEach(function(card) {
            const nome = card.dataset.nome || "";

            if (nome.includes(busca)) {
                card.style.display = "flex";
            } else {
                card.style.display = "none";
            }
        });
    });
}
</script>

</body>
</html>