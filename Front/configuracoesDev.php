<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/configuracoes.css">
    <title>MyClass - Configurações de Desenvolvedor</title>
</head>
<body class="config-page">
    <?php include 'sidebar.php'; ?>

    <main class="container">
        <div class="header-row">
            <div>
                <h2>Configurações de Desenvolvedor</h2>
                <div class="subtitle">Gerencie configurações aplicáveis apenas para desenvolvedores</div>
            </div>
            <div>
                <button id="refreshBtn" class="btn btn-ghost">Atualizar</button>
                <span class="badge">Dev</span>
            </div>
        </div>

        <div class="config-grid">
            <section class="card">
                <h3>Lista de Configurações</h3>
                <div style="overflow:auto;">
                    <table class="config-table" id="configTable">
                        <thead>
                            <tr><th>ID</th><th>Chave</th><th>Valor</th><th>Ações</th></tr>
                        </thead>
                        <tbody>
                            <!-- preenchido por JS -->
                        </tbody>
                    </table>
                    <div id="empty" class="empty-state" style="display:none;">Nenhuma configuração encontrada.</div>
                </div>
            </section>

            <aside class="side-panel card">
                <h3>Criar / Editar</h3>
                <div class="hint">Use o formulário para adicionar ou editar uma configuração.</div>
                <form id="configForm">
                    <input type="hidden" name="id" id="configId">
                    <div class="form-group">
                        <label for="config_key">Chave</label>
                        <input type="text" id="config_key" name="config_key" required>
                    </div>
                    <div class="form-group">
                        <label for="config_value">Valor</label>
                        <textarea id="config_value" name="config_value" rows="6"></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Salvar</button>
                        <button type="button" class="btn btn-ghost" id="clearBtn">Limpar</button>
                        <button type="button" class="btn btn-danger" id="deleteBtn" style="display:none;">Excluir</button>
                    </div>
                </form>

                <hr style="margin:14px 0">

                <h3>Criar Usuário</h3>
                <form id="userForm" action="../Back/configuracoesDev_process.php" method="POST">
                    <div class="form-group">
                        <label for="nomeUsuario">Usuário</label>
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
                            <option value="aluno">Aluno</option>
                            <option value="professor">Professor</option>
                            <option value="Direcao">Direção</option>
                            <option value="dev">Dev</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Criar usuário</button>
                    </div>
                </form>

            </aside>
        </div>
    </main>

    <script>
        const api = '../Back/configuracoesDev_process.php';
        const tableBody = document.querySelector('#configTable tbody');
        const emptyState = document.getElementById('empty');
        const form = document.getElementById('configForm');
        const saveBtn = document.getElementById('saveBtn');
        const clearBtn = document.getElementById('clearBtn');
        const deleteBtn = document.getElementById('deleteBtn');
        const refreshBtn = document.getElementById('refreshBtn');
        const userForm = document.getElementById('userForm');

        async function listConfigs(){
            try{
                const res = await fetch(api + '?action=list');
                const json = await res.json();
                if(!json.success) throw new Error(json.message || 'Erro ao listar');
                renderTable(json.data || []);
            }catch(err){
                console.error(err);
                alert('Erro ao carregar configurações');
            }
        }

        function renderTable(items){
            tableBody.innerHTML = '';
            if(!items.length){ emptyState.style.display='block'; return; }
            emptyState.style.display='none';
            items.forEach(it => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${it.id}</td><td>${escapeHtml(it.config_key)}</td><td>${escapeHtml(it.config_value)}</td><td class="config-actions">
                    <button class="btn btn-ghost" data-id="${it.id}" data-action="edit">Editar</button>
                    <button class="btn btn-danger" data-id="${it.id}" data-action="delete">Excluir</button>
                </td>`;
                tableBody.appendChild(tr);
            });
        }

        // Escape simples
        function escapeHtml(s){ return (s+'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

        // eventos delegados para editar/excluir
        tableBody.addEventListener('click', async function(e){
            const btn = e.target.closest('button');
            if(!btn) return;
            const id = btn.dataset.id;
            const action = btn.dataset.action;
            if(action === 'edit') return loadConfig(id);
            if(action === 'delete') return confirmDelete(id);
        });

        async function loadConfig(id){
            try{
                const res = await fetch(api + '?action=get&id=' + encodeURIComponent(id));
                const json = await res.json();
                if(!json.success) throw new Error(json.message || 'Erro ao obter');
                const d = json.data || {};
                document.getElementById('configId').value = d.id || '';
                document.getElementById('config_key').value = d.config_key || '';
                document.getElementById('config_value').value = d.config_value || '';
                deleteBtn.style.display = 'inline-block';
                saveBtn.textContent = 'Atualizar';
            }catch(err){ console.error(err); alert('Erro ao carregar configuração'); }
        }

        async function confirmDelete(id){
            if(!confirm('Deseja excluir esta configuração?')) return;
            try{
                const fd = new FormData();
                fd.append('action','delete');
                fd.append('id', id);
                const res = await fetch(api, { method:'POST', body: fd });
                const json = await res.json();
                if(!json.success) throw new Error(json.message || 'Erro ao excluir');
                await listConfigs();
                clearForm();
            }catch(err){ console.error(err); alert('Erro ao excluir'); }
        }

        form.addEventListener('submit', async function(e){
            e.preventDefault();
            const id = document.getElementById('configId').value;
            const key = document.getElementById('config_key').value.trim();
            const value = document.getElementById('config_value').value.trim();
            if(!key){ alert('Chave é obrigatória'); return; }
            const fd = new FormData();
            fd.append('config_key', key);
            fd.append('config_value', value);
            if(id){ fd.append('id', id); fd.append('action','update'); }
            else { fd.append('action','create'); }

            try{
                const res = await fetch(api, { method:'POST', body: fd });
                const json = await res.json();
                if(!json.success) throw new Error(json.message || 'Erro ao salvar');
                await listConfigs();
                clearForm();
            }catch(err){ console.error(err); alert('Erro ao salvar'); }
        });

        // adicionar criação de usuário
        userForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const username = document.getElementById('nomeUsuario').value.trim();
            const password = document.getElementById('senhaUsuario').value;
            const tipo = document.getElementById('tipoUsuario').value;
            if(!username || !password){ alert('Preencha todos os campos para criar usuário'); return; }
            try{
                const fd = new FormData();
                fd.append('action','create_user');
                fd.append('nomeUsuario', username);
                fd.append('senhaUsuario', password);
                fd.append('tipoUsuario', tipo);
                const res = await fetch(api, { method:'POST', body: fd });
                const json = await res.json();
                if(!json.success) throw new Error(json.message || 'Erro ao criar usuário');
                alert('Usuário criado com id: ' + json.id);
                userForm.reset();
            }catch(err){ console.error(err); alert('Erro ao criar usuário: ' + (err.message||'')); }
        });

        clearBtn.addEventListener('click', clearForm);
        refreshBtn.addEventListener('click', listConfigs);
        deleteBtn.addEventListener('click', function(){
            const id = document.getElementById('configId').value;
            if(id) confirmDelete(id);
        });

        function clearForm(){
            document.getElementById('configId').value = '';
            document.getElementById('config_key').value = '';
            document.getElementById('config_value').value = '';
            deleteBtn.style.display = 'none';
            saveBtn.textContent = 'Salvar';
        }

        // init
        listConfigs();
    </script>
</body>
</html>