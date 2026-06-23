<?php
include_once 'conexao.php';

$action = '';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action === 'create') {
	$nome = isset($_POST['materia_name']) ? trim($_POST['materia_name']) : '';
	$codigo = isset($_POST['materia_code']) ? trim($_POST['materia_code']) : '';
	$tipo = isset($_POST['materia_tipo']) ? trim($_POST['materia_tipo']) : '';

	if ($nome === '' || $codigo === '' || $tipo === '') {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Preencha todos os campos'));
		exit;
	}
    
	$sql = 'INSERT INTO materias 
(nomeMateria, codigoMateria, tipo, cargaHoraria, detalhesMateria, stts) 
VALUES (?, ?, ?, ?, ?, ?)';

	$stmt = $conn->prepare($sql);

	if (!$stmt) {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Erro no preparo da query'));
		exit;
	}

	$cargaHoraria = 0;
	$detalhesMateria = '';
	$stts = 'ativa';

	$stmt->bind_param(
		'sssiss',
		$nome,
		$codigo,
		$tipo,
		$cargaHoraria,
		$detalhesMateria,
		$stts
	);

	if ($stmt->execute()) {
		header('Location: ../Front/configuracoesDev.php?success=' . urlencode('Matéria criada com sucesso'));
		exit;
	} else {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Falha ao criar matéria'));
		exit;
	}

} else if ($action === 'delete') {
	$id = 0;
	if (isset($_POST['materia_id'])) {
		$id = intval($_POST['materia_id']);
	} elseif (isset($_GET['idMateria'])) {
		$id = intval($_GET['idMateria']);
	}

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

} else if ($action === 'update') {

	$id = isset($_POST['idMateria']) ? intval($_POST['idMateria']) : 0;

	$nome = isset($_POST['nomeMateria']) ? trim($_POST['nomeMateria']) : '';
	$codigo = isset($_POST['codigoMateria']) ? trim($_POST['codigoMateria']) : '';
	$tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : '';
	$carga = isset($_POST['cargaHoraria']) ? intval($_POST['cargaHoraria']) : 0;
	$detalhes = isset($_POST['detalhesMateria']) ? trim($_POST['detalhesMateria']) : '';
	$stts = isset($_POST['stts']) ? trim($_POST['stts']) : '';

	$idUsuario = !empty($_POST['idUsuario']) ? intval($_POST['idUsuario']) : null;

	if ($id <= 0 || $nome === '' || $codigo === '' || $tipo === '') {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Dados inválidos'));
		exit;
	}

	$sql = 'UPDATE materias SET 
			nomeMateria=?,
			codigoMateria=?,
			tipo=?,
			cargaHoraria=?,
			detalhesMateria=?,
			stts=?,
			idUsuario=?
			WHERE idMateria=?';

	$stmt = $conn->prepare($sql);

	if (!$stmt) {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Erro no preparo da atualização'));
		exit;
	}

	$stmt->bind_param(
		'sssissii',
		$nome,
		$codigo,
		$tipo,
		$carga,
		$detalhes,
		$stts,
		$idUsuario,
		$id
	);

	if ($stmt->execute()) {
		header('Location: ../Front/configuracoesDev.php?success=' . urlencode('Matéria atualizada com sucesso'));
		exit;
	} else {
		header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Falha ao atualizar matéria'));
		exit;
	}

} else {
	header('Location: ../Front/configuracoesDev.php?error=' . urlencode('Ação inválida'));
	exit;
}

?>