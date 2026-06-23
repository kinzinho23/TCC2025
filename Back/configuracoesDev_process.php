<?php

require_once 'conexao.php';


// criar usuario

if (isset($_POST['action']) && $_POST['action'] === 'create_user') {


    $nomeUsuario = trim($_POST['nomeUsuario']);
    $identificador = intval($_POST['identificador']);
    $senhaUsuario = $_POST['senhaUsuario'];
    $tipoUsuario = $_POST['tipoUsuario'];


    if (empty($nomeUsuario) || empty($identificador) || empty($senhaUsuario) || empty($tipoUsuario)) {

        header('Location: ../Front/configuracoesDev.php?error=empty_fields');
        exit();

    }



    // Verifica se o identificador já existe

    $sqlCheck = "SELECT idUsuario FROM usuario WHERE identificador = ?";

    $stmtCheck = $conn->prepare($sqlCheck);

    $stmtCheck->bind_param("i", $identificador);

    $stmtCheck->execute();

    $resultCheck = $stmtCheck->get_result();



    if ($resultCheck->num_rows > 0) {

        header('Location: ../Front/configuracoesDev.php?error=Identificador já existe');
        exit();

    }



    // Hash da senha

    $hashedPassword = password_hash($senhaUsuario, PASSWORD_DEFAULT);



    // Cria usuario

    $sqlInsert = "
    INSERT INTO usuario 
    (nomeUsuario, identificador, senhaUsuario, tipoUsuario)
    VALUES (?, ?, ?, ?)
    ";


    $stmtInsert = $conn->prepare($sqlInsert);



    $stmtInsert->bind_param(
        "siss",
        $nomeUsuario,
        $identificador,
        $hashedPassword,
        $tipoUsuario
    );



    if ($stmtInsert->execute()) {


        header('Location: ../Front/configuracoesDev.php?success=Usuario criado com sucesso');

        exit();


    } else {


        header('Location: ../Front/configuracoesDev.php?error=Usuário não pôde ser criado');

        exit();

    }


}



// excluir usuario

if (isset($_GET['action']) && $_GET['action'] === 'delete_user') {


    $idUsuario = isset($_GET['idUsuario']) ? intval($_GET['idUsuario']) : 0;


    if ($idUsuario <= 0) {

        header('Location: ../Front/configuracoesDev.php?error=ID de usuário inválido');
        exit();

    }



    // Antes de excluir, remove/desvincula relações desse usuário

    // Se o usuário for professor de alguma matéria, deixa a matéria sem professor
    $sqlMaterias = "
    UPDATE materias
    SET idUsuario = NULL
    WHERE idUsuario = ?
    ";

    $stmtMaterias = $conn->prepare($sqlMaterias);
    $stmtMaterias->bind_param("i", $idUsuario);
    $stmtMaterias->execute();



    // Se o usuário estiver em alguma carteira, remove ele da sala
    $sqlCarteiras = "
    DELETE FROM sala_carteiras
    WHERE idUsuario = ?
    ";

    $stmtCarteiras = $conn->prepare($sqlCarteiras);
    $stmtCarteiras->bind_param("i", $idUsuario);
    $stmtCarteiras->execute();



    // Agora exclui o usuário

    $sqlDelete = "
    DELETE FROM usuario
    WHERE idUsuario = ?
    ";

    $stmtDelete = $conn->prepare($sqlDelete);

    $stmtDelete->bind_param("i", $idUsuario);



    if ($stmtDelete->execute()) {

        header('Location: ../Front/configuracoesDev.php?success=Usuário excluído com sucesso');
        exit();

    } else {

        header('Location: ../Front/configuracoesDev.php?error=Usuário não pôde ser excluído');
        exit();

    }


}



header('Location: ../Front/configuracoesDev.php?error=Ação inválida');
exit();

?>