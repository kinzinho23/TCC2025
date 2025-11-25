<?php
session_start();
require_once '../Back/conexao.php';

// Carrega lista de usuários para exibição
$users = [];
try {
    $stmt = $conn->prepare('SELECT idUsuario, nomeUsuario, identificador, tipoUsuario FROM usuario ORDER BY idUsuario DESC');
    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $users[] = $row;
        $stmt->close();
    }
} catch (Exception $e) {
    error_log('configuracoesDev.php users select: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/configuracoes.css">
    <title>MyClass - Configurações de Desenvolvedor</title>
</head>
<body class="config-page">
    <header>
    <?php include 'sidebar.php'; ?>
    </header>
    <main class="container">
        <div class="header-row">
            <div>
                <h2>Configurações de Desenvolvedor</h2>
                <div class="subtitle">Gerencie configurações aplicáveis apenas para desenvolvedores</div>
            </div>
            <div>
                <a href="#" class="btn btn-ghost">Atualizar</a>
                <span class="badge">Dev</span>
            </div>
        </div>

        <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>

        <div class="config-grid">
            <section class="card">
                <h3>Lista de Configurações</h3>
                <div style="overflow:auto;">
                    <table class="config-table">
                        <thead>
                            <tr><th>ID</th><th>Chave</th><th>Valor</th><th>Ações</th></tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4" class="empty-state">Nenhuma configuração carregada (sem PHP)</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="side-panel card">
                <h3>Criar Configuração</h3>
                <div class="hint">Use o formulário para adicionar uma configuração (POST para backend).</div>
                <form method="post" action="../Back/configuracoesDev_process.php">
                    <input type="hidden" name="action" value="create">
                    <div class="form-group">
                        <label for="config_key">Chave</label>
                        <input type="text" id="config_key" name="config_key" required>
                    </div>
                    <div class="form-group">
                        <label for="config_value">Valor</label>
                        <textarea id="config_value" name="config_value" rows="6"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="reset" class="btn btn-ghost">Limpar</button>
                    </div>
                </form>

                <hr style="margin:14px 0">

                <h3>Criar Usuário</h3>
                <form method="post" action="../Back/configuracoesDev_process.php">
                    <input type="hidden" name="action" value="create_user">
                    <div class="form-group">
                        <label for="nomeUsuario">Nome</label>
                        <input type="text" id="nomeUsuario" name="nomeUsuario" required>
                    </div>
                    <div class="form-group">
                        <label for="identificador">Identificador</label>
                        <input type="text" id="identificador" name="identificador" required>
                    </div>
                    <div class="form-group">
                        <label for="senhaUsuario">Senha</label>
                        <input type="password" id="senhaUsuario" name="senhaUsuario" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoUsuario">Tipo</label>
                        <select id="tipoUsuario" name="tipoUsuario">
                            <option value="aluno">aluno</option>
                            <option value="professor">professor</option>
                            <option value="Direcao">direção</option>
                            <option value="dev">dev</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Criar usuário</button>
                    </div>
                </form>

            </aside>
        </div>

        <!-- Lista de usuários -->
        <section class="card" style="margin-top:18px;">
            <h3>Lista de Usuários</h3>
            <div style="overflow:auto;">
                <table class="config-table">
                    <thead>
                        <tr><th>ID</th><th>Nome</th><th>Identificador</th><th>Tipo</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="5" class="empty-state">Nenhum usuário encontrado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u['idUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($u['nomeUsuario']); ?></td>
                                    <td><?php echo htmlspecialchars($u['identificador']); ?></td>
                                    <td><?php echo htmlspecialchars($u['tipoUsuario']); ?></td>
                                    <td>
                                        <form method="post" action="../Back/configuracoesDev_process.php" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="idUsuario" value="<?php echo htmlspecialchars($u['idUsuario']); ?>">
                                            
                                        </form>
                                        <button type="submit" class="btn btn-ghost" onclick="return confirm('Editar usuário?')"><a style="text-decoration:none; color: inherit;" href="../Front/editarUsuario.php?id=<?php echo htmlspecialchars($u['idUsuario']); ?>">Editar</a></button>
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Excluir usuário?')"><a style="text-decoration:none; color: inherit;" href="../Back/configuracoesDev_process.php?action=delete_user&idUsuario=<?php echo htmlspecialchars($u['idUsuario']); ?>">Excluir</a></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>