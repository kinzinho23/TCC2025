<?php

session_start();

require_once 'conexao.php';


$id = isset($_POST['idUsuario']) ? (int) $_POST['idUsuario'] : 0;


if ($id <= 0) {

    echo "
    <script>
        alert('ID de usuário inválido.');
        window.location.href = '../Front/configuracoesDev.php';
    </script>
    ";

    exit;

}



$nome = trim($_POST['nomeUsuario'] ?? '');
$identificador = trim($_POST['identificador'] ?? '');
$tipoUsuario = $_POST['tipoUsuario'] ?? null;



if ($nome === '' || $identificador === '') {

    echo "
    <script>
        alert('Preencha todos os campos obrigatórios.');
        window.location.href = '../Front/editarUsuario.php?id={$id}';
    </script>
    ";

    exit;

}



// Atualiza dados básicos

if (!empty($tipoUsuario)) {

    $sql = "
    UPDATE usuario SET
        nomeUsuario = ?,
        identificador = ?,
        tipoUsuario = ?
    WHERE idUsuario = ?
    ";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "sssi",
        $nome,
        $identificador,
        $tipoUsuario,
        $id
    );


} else {

    $sql = "
    UPDATE usuario SET
        nomeUsuario = ?,
        identificador = ?
    WHERE idUsuario = ?
    ";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "ssi",
        $nome,
        $identificador,
        $id
    );

}


$stmt->execute();



// Atualiza senha caso tenha sido preenchida

if (!empty($_POST['senhaUsuario'])) {


    $senha = password_hash(
        $_POST['senhaUsuario'],
        PASSWORD_DEFAULT
    );


    $sql = "
    UPDATE usuario
    SET senhaUsuario = ?
    WHERE idUsuario = ?
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

if (isset($_FILES['fotoUsuario']) && $_FILES['fotoUsuario']['error'] == 0) {


    $pasta = "../img/perfil/";


    if (!is_dir($pasta)) {

        mkdir($pasta, 0777, true);

    }



    $extensao = pathinfo(
        $_FILES['fotoUsuario']['name'],
        PATHINFO_EXTENSION
    );


    $nomeFoto = "perfil_" . $id . "_" . time() . "." . $extensao;


    $caminhoBanco = "img/perfil/" . $nomeFoto;


    $caminhoUpload = "../" . $caminhoBanco;


    move_uploaded_file(
        $_FILES['fotoUsuario']['tmp_name'],
        $caminhoUpload
    );



    $sql = "
    UPDATE usuario
    SET fotoUsuario = ?
    WHERE idUsuario = ?
    ";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "si",
        $caminhoBanco,
        $id
    );


    $stmt->execute();

}



// Redirecionamento com pop-up

if (isset($_SESSION['idUsuario']) && $id !== (int) $_SESSION['idUsuario']) {

    echo "
    <script>
        alert('Usuário atualizado com sucesso!');
        window.location.href = '../Front/configuracoesDev.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Perfil atualizado com sucesso!');
        window.location.href = '../Front/perfil.php';
    </script>
    ";

}


exit;

?>