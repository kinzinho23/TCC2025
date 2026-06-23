<?php

session_start();

require_once '../Back/conexao.php';
include("../Back/preferencias.php");


if(!isset($_SESSION['idUsuario'])){
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
$stmt->bind_param("i",$idUsuario);
$stmt->execute();


$user = $stmt->get_result()->fetch_assoc();



?>


<!DOCTYPE html>

<html lang="pt-br">

<head>

<meta charset="UTF-8">

<link rel="stylesheet" href="../css/perfil.css">

<title>Editar perfil</title>


</head>


<body>


<header>

<?php include 'sidebar.php'; ?>

</header>



<main class="perfil-container">



<section class="card">


<h2>Editar perfil</h2>



<form 
method="POST"
action="../Back/perfil_process.php"
enctype="multipart/form-data"
>


<input type="hidden" 
name="idUsuario"
value="<?php echo $user['idUsuario']; ?>"
>

<div class="perfil-foto">

    <img 
        id="previewFoto"
        src="<?php echo htmlspecialchars(getFotoSrc($user['fotoUsuario'])); ?>"
        class="foto-preview"
        alt="Foto de perfil"
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

</div>

<div class="linha">

<label>Nome</label>

<input 
type="text"
name="nomeUsuario"
value="<?php echo htmlspecialchars($user['nomeUsuario']); ?>"
>

</div>

<div class="linha">


<label>Identificador</label>


<input 
type="text"
name="identificador"
value="<?php echo $user['identificador']; ?>"
>


</div>


<?php if(
$user['tipoUsuario']=='admin' ||
$user['tipoUsuario']=='coordenacao'
): ?>


<div class="linha">


<label>Tipo</label>


<select name="tipoUsuario">


<option value="aluno">
Aluno
</option>


<option value="professor">
Professor
</option>


<option value="coordenacao">
Coordenação
</option>


<option value="admin">
Admin
</option>


</select>


</div>


<?php endif; ?>



<div class="linha">


<label>Nova senha</label>


<input 
type="password"
name="senhaUsuario"
placeholder="Deixe vazio para manter"
>


</div>



<button class="btn">

Salvar

</button>


</form>

</section>


</main>

<script>

const inputFoto = document.getElementById("fotoPerfil");
const preview = document.getElementById("previewFoto");


inputFoto.addEventListener("change", function(){

    const arquivo = this.files[0];

    if(arquivo){

        const leitor = new FileReader();

        leitor.onload = function(e){

            preview.src = e.target.result;

        }

        leitor.readAsDataURL(arquivo);
    }

});

</script>
</body>

</html>