<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <title>MyClass - Login</title>
</head>
<body>
    <main>
        <h1>Login</h1>
        <form action="../Back/login_process.php" method="POST">
            <label for="identificador">Identificação</label>
            <br>
            <input type="text" id="identificador" name="identificador" placeholder="Digite sua identificação" required>
            <br>
            <label for="password">Senha</label>
            <br>
            <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            <span><a href="#">esqueci a senha</a></span>
            <br>
            <button type="submit">Entrar</button>
            <?php if (isset($_GET['error'])): ?>
                <p style="color: red; position: relative; top: -20px;">Identificação ou senha inválida.</p>
            <?php endif; ?>
        </form>
    </main> 
</body>
</html>