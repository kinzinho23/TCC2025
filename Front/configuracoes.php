<?php 
if (session_status() === PHP_SESSION_NONE) session_start();

include("../Back/conexao.php");
include("../Back/preferencias.php");

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

$sql = "
SELECT 
    nomeUsuario,
    tipoUsuario,
    temaSite,
    notificacoes,
    mostrarFoto,
    telaInicial
FROM usuario
WHERE idUsuario = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    header("Location: login.php");
    exit;
}

$userRole = $user['tipoUsuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>MyClass - Configurações</title>
    <link rel="stylesheet" href="../css/configuracoes.css">
</head>
<body class="<?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">

<header>
    <?php include 'sidebar.php'; ?>
</header>

<main class="config-container">

    <section class="config-header">
        <div>
            <h1>Configurações</h1>
            <p>Personalize sua experiência no MyClass.</p>
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

    <section class="config-card">

        <h2>Preferências gerais</h2>

        <form action="../Back/configuracoes_process.php" method="POST">

            <div class="config-group">
                <label for="temaSite">Tema do site</label>

                <select name="temaSite" id="temaSite">
                    <option value="claro" <?php if ($user['temaSite'] === 'claro') echo 'selected'; ?>>
                        Claro
                    </option>

                    <option value="escuro" <?php if ($user['temaSite'] === 'escuro') echo 'selected'; ?>>
                        Escuro
                    </option>
                </select>
            </div>

            <div class="config-group">
                <label for="notificacoes">Notificações</label>

                <select name="notificacoes" id="notificacoes">
                    <option value="ativadas" <?php if ($user['notificacoes'] === 'ativadas') echo 'selected'; ?>>
                        Ativadas
                    </option>

                    <option value="desativadas" <?php if ($user['notificacoes'] === 'desativadas') echo 'selected'; ?>>
                        Desativadas
                    </option>
                </select>
            </div>

            <div class="config-group">
                <label for="mostrarFoto">Foto de perfil</label>

                <select name="mostrarFoto" id="mostrarFoto">
                    <option value="sim" <?php if ($user['mostrarFoto'] === 'sim') echo 'selected'; ?>>
                        Mostrar foto
                    </option>

                    <option value="nao" <?php if ($user['mostrarFoto'] === 'nao') echo 'selected'; ?>>
                        Ocultar foto
                    </option>
                </select>
            </div>

            <div class="config-group">
                <label for="telaInicial">Tela inicial preferida</label>

                <select name="telaInicial" id="telaInicial">
                    <option value="dashboard" <?php if ($user['telaInicial'] === 'dashboard') echo 'selected'; ?>>
                        Dashboard
                    </option>

                    <option value="materias" <?php if ($user['telaInicial'] === 'materias') echo 'selected'; ?>>
                        Matérias
                    </option>

                    <option value="salas" <?php if ($user['telaInicial'] === 'salas') echo 'selected'; ?>>
                        Salas de aula
                    </option>

                    <?php if (in_array($userRole, ['admin', 'coordenacao'])): ?>
                        <option value="configuracoesDev" <?php if ($user['telaInicial'] === 'configuracoesDev') echo 'selected'; ?>>
                            Configurações Dev
                        </option>
                    <?php endif; ?>
                </select>
            </div>

            <?php if (in_array($userRole, ['admin', 'coordenacao'])): ?>

                <div class="admin-box">
                    <h3>Área administrativa</h3>
                    <p>
                        Você possui permissões avançadas para gerenciar salas, usuários e matérias.
                    </p>
                </div>

            <?php else: ?>

                <div class="limited-box">
                    <h3>Acesso limitado</h3>
                    <p>
                        Algumas configurações administrativas não estão disponíveis para seu tipo de usuário.
                    </p>
                </div>

            <?php endif; ?>

            <button type="submit" class="btn-save">
                Salvar configurações
            </button>

        </form>

    </section>

</main>

</body>
</html>