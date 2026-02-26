<?php
include("conexao.php");

$sql = 'INSERT INTO salas (nomeSala, capacidade, tipoSala, stts ) VALUES (?, ?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('siss', $nomeSala, $capacidade, $tipoSala, $stts);
$stmt->execute();
$stmt = $conn->prepare('');
?>