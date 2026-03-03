<?php
// Página pública de matérias redirecionando para a área de Configurações para criação/listagem restrita
session_start();
$userRole = $_SESSION['tipoUsuario'] ?? null;
if (in_array($userRole, ['dev','coordenacao','Direcao'])) {
    header('Location: configuracoes.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/materias.css">
    <title>Matérias</title>
</head>
<body>
     <header>
        <?php include 'sidebar.php'; ?>
    </header>
    <main style="max-width:900px;margin:28px auto;padding:18px;">
       <h1 id="title">Matérias</h1>
       <div style="padding:12px;background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
           <p>A criação e listagem de matérias agora ficam em <a href="configuracoes.php">Configurações</a> (área restrita).</p>
       </div>
    </main>
</body>
</html>