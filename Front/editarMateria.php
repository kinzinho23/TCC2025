<?php
session_start();
require_once '../Back/conexao.php';
include("../Back/preferencias.php");

$materia = null;
$error = null;
$professores = [];


if(empty($_GET['id'])){
    $error = "Matéria não informada.";
}else{

    $id = (int)$_GET['id'];

    $sql = "
        SELECT 
            idMateria,
            nomeMateria,
            codigoMateria,
            tipo,
            cargaHoraria,
            detalhesMateria,
            idUsuario,
            stts
        FROM materias
        WHERE idMateria = ?
    ";


    if($stmt = $conn->prepare($sql)){

        $stmt->bind_param("i",$id);
        $stmt->execute();

        $res = $stmt->get_result();

        $materia = $res->fetch_assoc();

        $stmt->close();


        if(!$materia){
            $error = "Matéria não encontrada.";
        }


    }else{
        $error = "Erro ao buscar matéria.";
    }

}



// Busca professores para o select

$sqlProf = "
    SELECT 
        idUsuario, 
        nomeUsuario 
    FROM usuario 
    WHERE tipoUsuario = 'professor'
    ORDER BY nomeUsuario ASC
";

if($stmtProf = $conn->prepare($sqlProf)){

    $stmtProf->execute();

    $resProf = $stmtProf->get_result();

    while($row = $resProf->fetch_assoc()){
        $professores[] = $row;
    }

    $stmtProf->close();
}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../css/configuracoesDev.css">

<title>Editar Matéria</title>

</head>


<body class="config-page <?php echo ($preferencias['temaSite'] ?? 'claro') === 'escuro' ? 'tema-escuro' : ''; ?>">


<header>

<?php include 'sidebar.php'; ?>

</header>



<main class="container">


<?php if($error): ?>


<div class="alert alert-error">

<?php echo htmlspecialchars($error); ?>

</div>


<?php else: ?>



<section class="card">


<h2>
Editar Matéria
</h2>



<form method="POST" action="../Back/materias_process.php">



<input 
type="hidden"
name="action"
value="update">



<input 
type="hidden"
name="idMateria"
value="<?php echo $materia['idMateria']; ?>">



<div class="form-group">

<label>
Nome da matéria
</label>


<input
type="text"
name="nomeMateria"
value="<?php echo htmlspecialchars($materia['nomeMateria']); ?>"
required>

</div>




<div class="form-group">

<label>
Código
</label>


<input
type="text"
name="codigoMateria"
value="<?php echo htmlspecialchars($materia['codigoMateria']); ?>"
required>

</div>





<div class="form-group">


<label>
Tipo
</label>


<select name="tipo">


<option 
value="obrigatoria"
<?php if($materia['tipo']=="obrigatoria") echo "selected"; ?>
>
Obrigatória
</option>


<option 
value="optativa"
<?php if($materia['tipo']=="optativa") echo "selected"; ?>
>
Optativa
</option>


<option 
value="eletiva"
<?php if($materia['tipo']=="eletiva") echo "selected"; ?>
>
Eletiva
</option>


</select>


</div>




<div class="form-group">

<label>
Professor responsável
</label>


<select name="idUsuario">

<option value="">
Nenhum professor
</option>


<?php foreach($professores as $professor): ?>

<option 
value="<?php echo htmlspecialchars($professor['idUsuario']); ?>"
<?php if((int)$materia['idUsuario'] === (int)$professor['idUsuario']) echo "selected"; ?>
>
<?php echo htmlspecialchars($professor['nomeUsuario']); ?>
</option>

<?php endforeach; ?>


</select>

</div>






<div class="form-group">

<label>
Carga Horária
</label>


<input
type="number"
name="cargaHoraria"
value="<?php echo $materia['cargaHoraria']; ?>"
required>


</div>






<div class="form-group">


<label>
Detalhes
</label>


<textarea name="detalhesMateria"><?php echo htmlspecialchars($materia['detalhesMateria']); ?></textarea>


</div>






<div class="form-group">


<label>
Status
</label>


<select name="stts">


<option value="ativa"
<?php if($materia['stts']=="ativa") echo "selected"; ?>
>
Ativa
</option>


<option value="inativa"
<?php if($materia['stts']=="inativa") echo "selected"; ?>
>
Inativa
</option>


</select>


</div>






<div class="form-actions">


<button class="btn btn-primary">

Salvar alterações

</button>


<a href="configuracoesDev.php" class="btn btn-ghost">

Cancelar

</a>


</div>




</form>


</section>



<?php endif; ?>


</main>


</body>

</html>