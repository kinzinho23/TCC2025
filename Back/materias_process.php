<?php
include_once 'conexao.php';


$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'create') {
	$nome = isset($_POST['materia_name']) ? trim($_POST['materia_name']) : '';
	$codigo = isset($_POST['materia_code']) ? trim($_POST['materia_code']) : '';
	$tipo = isset($_POST['materia_tipo']) ? trim($_POST['materia_tipo']) : '';

	if ($nome === '' || $codigo === '' || $tipo === '') {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Preencha todos os campos'));
		exit;
	}
    
	$sql = 'INSERT INTO materias (nomeMateria, codigoMateria, tipo) VALUES (?, ?, ?)';
	$stmt = $conn->prepare($sql);
	if (!$stmt) {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Erro no preparo da query'));
		exit;
	}

	$stmt->bind_param('sss', $nome, $codigo, $tipo);
	if ($stmt->execute()) {
		header('Location: ../Front/configuracoesDev.php?success=' . urlencode('Matéria criada com sucesso'));
		exit;
	} else {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Falha ao criar matéria'));
		exit;
	}

} else if ($action === 'delete') {
	$id = isset($_POST['materia_id']) ? intval($_POST['materia_id']) : 0;

	if ($id <= 0) {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('ID de matéria inválido'));
		exit;
	}

    $sql = 'DELETE FROM materias WHERE idMateria = ?';
	$stmt = $conn->prepare($sql);
	if (!$stmt) {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Erro no preparo da query'));
		exit;
	}

	$stmt->bind_param('i', $id);
	if ($stmt->execute()) {
		header('Location: ../Front/configuracoesDev.php?success=' . urlencode('Matéria deletada com sucesso'));
		exit;
	} else {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Falha ao deletar matéria'));
		exit;
	}
} else {
	header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Ação inválida'));
	exit;
}

?>