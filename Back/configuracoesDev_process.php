<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexao.php';

// Apenas desenvolvedores podem usar essa rota
if (isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] == 'dev') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit();
}

// Apenas POST para criação de usuário
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit();
}

$action = $_POST['action'] ?? '';
if ($action !== 'create_user') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ação inválida']);
    exit();
}

// Ler campos
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$tipoUsuario = trim($_POST['tipoUsuario'] ?? 'user');

if ($username === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit();
}

// Validações simples
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'E-mail inválido']);
    exit();
}

try {
    // Checar duplicidade
    $chk = $conn->prepare('SELECT idUsuario FROM usuario WHERE identificador = ? OR email = ? LIMIT 1');
    $chk->bind_param('ss', $username, $email);
    $chk->execute();
    $resChk = $chk->get_result();
    if ($resChk && $resChk->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Usuário ou e-mail já cadastrado']);
        exit();
    }

    // Inserir usuário
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $conn->prepare('INSERT INTO usuario (identificador, email, senha, tipoUsuario, created_at) VALUES (?, ?, ?, ?, NOW())');
    $ins->bind_param('ssss', $username, $email, $hash, $tipoUsuario);
    $ins->execute();
    $newId = $ins->insert_id;

    echo json_encode(['success' => true, 'id' => $newId]);
    exit();

} catch (Exception $e) {
    error_log('configuracoesDev_process.php create_user error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor']);
    exit();
}

?>