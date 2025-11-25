<?php
// criar usuario

if (isset($_POST['action']) && $_POST['action'] === 'create_user') {
    require_once 'conexao.php';

    // Captura e sanitiza os dados do formulário
    $nomeUsuario = trim($_POST['nomeUsuario']);
    $identificador = trim($_POST['identificador']);
    $senhaUsuario = $_POST['senhaUsuario'];
    $tipoUsuario = $_POST['tipoUsuario'];

    // Validação básica
    if (empty($nomeUsuario) || empty($identificador) || empty($senhaUsuario) || empty($tipoUsuario)) {
        header('Location: ../Front/configuracoesDev.php?error=empty_fields');
        exit();
    }

    // Verifica se o identificador já existe
    $sqlCheck = "SELECT idUsuario FROM usuario WHERE identificador = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $identificador);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
 
    // Hash da senha
    $hashedPassword = password_hash($senhaUsuario, PASSWORD_DEFAULT);

    // Insere o novo usuário no banco de dados
    $sqlInsert = "INSERT INTO usuario (nomeUsuario, identificador, senhaUsuario, tipoUsuario) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssss", $nomeUsuario, $identificador, $hashedPassword, $tipoUsuario);

    if ($stmtInsert->execute()) {
        header('Location: ../Front/configuracoesDev.php?success=Usuario criado com sucesso');
        exit();
    } else {
        header('Location: ../Front/configuracoesDev.php?error=Usuário não pôde ser criado');
        exit();
    }
}


?>