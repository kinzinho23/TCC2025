<?php
session_start();

require_once __DIR__ . '/conexao.php';

// garante que a conexão exista
if (!isset($conn) || !$conn) {
    error_log('login_process.php: conexão $conn não encontrada');
    header('Location: ../Front/login.php?error=1');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Front/login.php');
    exit();
}

// aceita 'username' (nome do formulário) ou 'identificador'
$identificador = '';
if (isset($_POST['username'])) {
    $identificador = trim($_POST['username']);
} elseif (isset($_POST['identificador'])) {
    $identificador = trim($_POST['identificador']);
}
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($identificador === '' || $password === '') {
    header('Location: ../Front/login.php?error=1');
    exit();
}

// Relatar erros do mysqli como exceções para capturá-los
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $stmt = $conn->prepare('SELECT * FROM usuario WHERE identificador = ? LIMIT 1');
    $stmt->bind_param('s', $identificador);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // suporta colunas 'password' ou 'senha'
        $hash = $user['password'] ?? $user['senha'] ?? null;

        if ($hash && password_verify($password, $hash)) {
            // autenticação bem-sucedida
            session_regenerate_id(true);
            $_SESSION['usuario'] = $identificador;
            $stmt->close();
            header('Location: ../Front/');
            exit();
        }
    }

    if (isset($stmt) && $stmt instanceof mysqli_stmt) $stmt->close();

} catch (Exception $e) {
    error_log('login_process.php error: ' . $e->getMessage());
    // não expor detalhes ao usuário
    header('Location: ../Front/login.php?error=1');
    exit();
}

// falha no login
header('Location: ../Front/login.php');
exit();