<?php

session_start();
require_once '../Back/conexao.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$idUsuario = $_SESSION['idUsuario'];

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
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Usuário não encontrado");
}

function getFotoSrc($path) {
    if (empty($path)) {
        return '../img/perfil/usuario.png';
    }

    $path = trim($path);
    if (preg_match('#^(https?://|//|/|[A-Za-z]:\\\\)#', $path)) {
        return $path;
    }

    return '../' . ltrim($path, '/.');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/perfil.css">

<title>MyClass - Perfil</title>

</head>


<body>


<header>

<?php include 'sidebar.php'; ?>

</header>


<main class="perfil-container">


<div class="perfil-grid">


<section class="perfil-header">


<img 
src="<?php echo htmlspecialchars(getFotoSrc($user['fotoUsuario'])); ?>"
class="perfil-img"
onerror="this.src='../img/perfil/usuario.png'"
>


<div>

<h1>
<?php echo htmlspecialchars($user['nomeUsuario']); ?>
</h1>


<span class="cargo">
<?php echo htmlspecialchars($user['tipoUsuario']); ?>
</span>


</div>


</section>



<section class="card">

<h2>Informações pessoais</h2>


<div class="linha">
<span class="label">ID</span>
<?php echo $user['idUsuario']; ?>
</div>


<div class="linha">
<span class="label">Nome</span>
<?php echo $user['nomeUsuario']; ?>
</div>


<div class="linha">
<span class="label">Identificador</span>
<?php echo $user['identificador']; ?>
</div>


<div class="linha">
<span class="label">Tipo</span>
<?php echo $user['tipoUsuario']; ?>
</div>


</section>




<section class="card">

<h2>Ações</h2>


<p>
Configurações disponíveis para seu usuário.
</p>


<?php if($user['tipoUsuario']=='admin' || $user['tipoUsuario']=='coordenacao'): ?>


<a class="btn" href="editarPerfil.php?id=<?php echo $user['idUsuario']; ?>">
Editar perfil
</a>


<?php endif; ?>


</section>


</div>


</main>


</body>

</html>