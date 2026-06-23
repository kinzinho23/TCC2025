<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
include("../Back/conexao.php");
include("../Back/preferencias.php");

$userRole = $_SESSION['tipoUsuario'] ?? null;

$podeEntrarNaCarteira = ($userRole === 'aluno');
$podeEditarSala = in_array($userRole, ['admin', 'coordenacao']);

$idSala = isset($_GET['id']) ? (int) $_GET['id'] : 1;

$nomeSala = "Turma " . $idSala;
$statusSala = "Em uso";
$nomeProfessor = "Vazio";
$nomeMateria = "Vazio";

$sqlSala = "
SELECT 
    s.nomeSala,
    s.stts,
    m.nomeMateria,
    u.nomeUsuario AS nomeProfessor
FROM salas s
LEFT JOIN materias m ON s.idMateria = m.idMateria
LEFT JOIN usuario u ON m.idUsuario = u.idUsuario
WHERE s.idSala = ?
LIMIT 1
";

$stmtSala = $conn->prepare($sqlSala);

if ($stmtSala) {
    $stmtSala->bind_param("i", $idSala);
    $stmtSala->execute();

    $resSala = $stmtSala->get_result();
    $sala = $resSala->fetch_assoc();

    if ($sala) {
        $nomeSala = $sala['nomeSala'];
        $statusSala = $sala['stts'];

        if (!empty($sala['nomeProfessor'])) {
            $nomeProfessor = $sala['nomeProfessor'];
        }

        if (!empty($sala['nomeMateria'])) {
            $nomeMateria = $sala['nomeMateria'];
        }
    }

    $stmtSala->close();
}

$materiasComProfessor = [];

if ($podeEditarSala) {
    $sqlMaterias = "
    SELECT 
        m.idMateria,
        m.nomeMateria,
        u.nomeUsuario AS nomeProfessor
    FROM materias m
    INNER JOIN usuario u ON m.idUsuario = u.idUsuario
    WHERE u.tipoUsuario = 'professor'
    ORDER BY m.nomeMateria ASC
    ";

    $resMaterias = $conn->query($sqlMaterias);

    if ($resMaterias) {
        while ($row = $resMaterias->fetch_assoc()) {
            $materiasComProfessor[] = $row;
        }
    }
}

$carteirasOcupadas = [];

$sqlCarteiras = "
SELECT 
    sc.numeroCarteira,
    u.nomeUsuario
FROM sala_carteiras sc
INNER JOIN usuario u ON sc.idUsuario = u.idUsuario
WHERE sc.idSala = ?
ORDER BY sc.numeroCarteira ASC
";

$stmt = $conn->prepare($sqlCarteiras);

if ($stmt) {
    $stmt->bind_param("i", $idSala);
    $stmt->execute();

    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $carteirasOcupadas[(int)$row['numeroCarteira']] = $row['nomeUsuario'];
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyClass - Sala de Aula</title>
    <link rel="stylesheet" href="../css/salaDetalhe.css">
</head>
<body>

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="sala-container">

    <section class="sala-header">

        <div>
            <h1><?php echo htmlspecialchars($nomeSala); ?></h1>
            <p>Gerenciamento da sala de aula</p>

            <div class="sala-info">
                <span>
                    <strong>Professor:</strong> 
                    <?php echo htmlspecialchars($nomeProfessor); ?>
                </span>

                <span>
                    <strong>Matéria:</strong> 
                    <?php echo htmlspecialchars($nomeMateria); ?>
                </span>
            </div>
        </div>

        <div class="header-actions">
            <span class="status-sala">
                <?php echo htmlspecialchars($statusSala); ?>
            </span>

        </div>

    </section>

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

    <?php if ($podeEditarSala): ?>

        <section class="professor-card" id="form-professor">
            <h2>Adicionar professor e matéria</h2>

            <form action="../Back/adicionarProfessorSala.php" method="POST">
                <input type="hidden" name="idSala" value="<?php echo $idSala; ?>">

                <label for="idMateria">Selecione a matéria</label>

                <select name="idMateria" id="idMateria" required>
                    <option value="">Selecione uma opção</option>

                    <?php foreach ($materiasComProfessor as $materia): ?>
                        <option value="<?php echo $materia['idMateria']; ?>">
                            <?php 
                                echo htmlspecialchars(
                                    $materia['nomeMateria'] . " - Prof. " . $materia['nomeProfessor']
                                ); 
                            ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">
                    Salvar na sala
                </button>
            </form>
        </section>

    <?php endif; ?>

    <section class="sala-layout">

        <aside class="alunos-card">
            <h2>Alunos</h2>

            <div class="alunos-lista">

                <?php if (empty($carteirasOcupadas)): ?>

                    <p>Nenhum aluno entrou na sala ainda.</p>

                <?php else: ?>

                    <?php foreach ($carteirasOcupadas as $numero => $nomeAluno): ?>

                        <div class="aluno-item">
                            <span class="avatar-mini">
                                <?php echo strtoupper(substr($nomeAluno, 0, 1)); ?>
                            </span>

                            <div>
                                <strong>
                                    <?php echo htmlspecialchars($nomeAluno); ?>
                                </strong>

                                <small>
                                    Carteira <?php echo htmlspecialchars($numero); ?>
                                </small>
                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </aside>

        <section class="mapa-card">

            <div class="quadro">
                Quadro
            </div>

            <div class="carteiras-grid">

                <?php for ($i = 1; $i <= 12; $i++): ?>

                    <?php if (isset($carteirasOcupadas[$i])): ?>

                        <div class="carteira ocupada">
                            <?php echo htmlspecialchars($carteirasOcupadas[$i]); ?>
                        </div>

                    <?php else: ?>

                        <?php if ($podeEntrarNaCarteira): ?>

                            <a 
                                href="../Back/entrarCarteira.php?idSala=<?php echo $idSala; ?>&carteira=<?php echo $i; ?>" 
                                class="carteira vazia clicavel"
                            >
                                Vazio
                            </a>

                        <?php else: ?>

                            <div class="carteira vazia bloqueada">
                                Vazio
                            </div>

                        <?php endif; ?>

                    <?php endif; ?>

                <?php endfor; ?>

            </div>

        </section>

    </section>

</main>

</body>
</html>