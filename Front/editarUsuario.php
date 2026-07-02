<?php

session_start();

require_once '../Back/conexao.php';
include("../Back/preferencias.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("ID de usuário inválido. Use ?id=NUMERO na URL. Recebido: " . ($_GET['id'] ?? 'nenhum'));
}

$sql = "
SELECT 
    idUsuario, 
    nomeUsuario, 
    identificador, 
    tipoUsuario, 
    fotoUsuario 
FROM usuario 
WHERE idUsuario = ? 
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$editUser = $stmt->get_result()->fetch_assoc();

$stmt->close();

if (!$editUser) {
    die("Usuário não encontrado.");
}

if ((int) $editUser['idUsuario'] !== $id) {
    die("Erro de consistência: usuário carregado não corresponde ao id GET.");
}

$fotoSrc = !empty($editUser['fotoUsuario']) 
    ? '../' . ltrim($editUser['fotoUsuario'], '/') 
    : '../img/perfil/usuario.png';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/perfil.css">
    <link rel="shortcut icon" href="../img/favicon.ico"type="image/x-icon">
    <title>Editar Usuário</title>

</head>

<body class="<?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">

<header>

    <?php include 'sidebar.php'; ?>

</header>

<main class="perfil-container">

    <section class="card">

        <h2>Editar Usuário</h2>

        <form 
            method="POST"
            action="../Back/perfil_process.php"
            enctype="multipart/form-data"
        >

            <input 
                type="hidden" 
                name="idUsuario"
                value="<?php echo $editUser['idUsuario']; ?>"
            >

            <div class="perfil-foto">

                <img 
                    id="previewFoto"
                    src="<?php echo htmlspecialchars($fotoSrc); ?>"
                    class="foto-preview"
                    alt="Foto de perfil"
                    onerror="this.src='../img/perfil/usuario.png'"
                >

                <label for="fotoPerfil" class="btn btn-primary">
                    Alterar foto
                </label>

                <input 
                    type="file" 
                    id="fotoPerfil"
                    name="fotoUsuario"
                    accept="image/*"
                    hidden
                >

            </div>

            <div class="linha">

                <label>Nome</label>

                <input 
                    type="text"
                    name="nomeUsuario"
                    value="<?php echo htmlspecialchars($editUser['nomeUsuario']); ?>"
                    required
                >

            </div>

            <div class="linha">

                <label>Identificador</label>

                <input 
                    type="text"
                    name="identificador"
                    value="<?php echo htmlspecialchars($editUser['identificador']); ?>"
                    required
                >

            </div>

            <div class="linha">

                <label>Tipo</label>

                <select name="tipoUsuario">

                    <option value="aluno" <?php echo $editUser['tipoUsuario'] === 'aluno' ? 'selected' : ''; ?>>
                        Aluno
                    </option>

                    <option value="professor" <?php echo $editUser['tipoUsuario'] === 'professor' ? 'selected' : ''; ?>>
                        Professor
                    </option>

                    <option value="coordenacao" <?php echo $editUser['tipoUsuario'] === 'coordenacao' ? 'selected' : ''; ?>>
                        Coordenação
                    </option>

                    <option value="admin" <?php echo $editUser['tipoUsuario'] === 'admin' ? 'selected' : ''; ?>>
                        Admin
                    </option>

                </select>

            </div>

            <div class="linha">

                <label>Nova senha</label>

                <input 
                    type="password"
                    name="senhaUsuario"
                    placeholder="Deixe vazio para manter"
                >

            </div>

            <div class="form-actions">

                <button type="submit" class="btnForm btnSalvar">
                    Salvar
                </button>

                <button 
                    type="button" 
                    class="btnForm btnVoltar" 
                    onclick="window.location.href='configuracoesDev.php'"
                >
                    Voltar
                </button>

            </div>

        </form>

    </section>

</main>

<script>

const inputFoto = document.getElementById("fotoPerfil");
const preview = document.getElementById("previewFoto");

inputFoto.addEventListener("change", function() {

    const arquivo = this.files[0];

    if (arquivo) {

        const leitor = new FileReader();

        leitor.onload = function(e) {

            preview.src = e.target.result;

        }

        leitor.readAsDataURL(arquivo);

    }

});

</script>

</body>

</html>