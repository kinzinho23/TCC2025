<?php

session_start();

require_once 'conexao.php';


$id = $_POST['idUsuario'];

$nome = $_POST['nomeUsuario'];

$identificador = $_POST['identificador'];



// Atualiza dados básicos

$sql = "
UPDATE usuario SET
nomeUsuario=?,
identificador=?
WHERE idUsuario=?
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssi",
    $nome,
    $identificador,
    $id
);


$stmt->execute();




// Atualiza senha caso tenha sido preenchida

if(!empty($_POST['senhaUsuario'])){


    $senha = password_hash(
        $_POST['senhaUsuario'],
        PASSWORD_DEFAULT
    );


    $sql = "
    UPDATE usuario
    SET senhaUsuario=?
    WHERE idUsuario=?
    ";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "si",
        $senha,
        $id
    );


    $stmt->execute();

}




// Atualiza foto

if(isset($_FILES['fotoUsuario']) && $_FILES['fotoUsuario']['error'] == 0){


    $pasta = "../img/perfil/";


    if(!is_dir($pasta)){

        mkdir($pasta,0777,true);

    }



    $extensao = pathinfo(
        $_FILES['fotoUsuario']['name'],
        PATHINFO_EXTENSION
    );


    $nomeFoto = "perfil_".$id."_".time().".".$extensao;



    $caminhoBanco = "img/perfil/".$nomeFoto;



    $caminhoUpload = "../".$caminhoBanco;



    move_uploaded_file(
        $_FILES['fotoUsuario']['tmp_name'],
        $caminhoUpload
    );



    $sql = "
    UPDATE usuario
    SET fotoUsuario=?
    WHERE idUsuario=?
    ";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "si",
        $caminhoBanco,
        $id
    );


    $stmt->execute();

}





header(
    "Location: ../Front/perfil.php"
);


exit;

?>