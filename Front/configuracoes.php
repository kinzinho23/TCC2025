<?php
session_start();
require_once '../Back/conexao.php';

$userRole = $_SESSION['tipoUsuario'] ?? null;
$allowed = ['dev', 'coordenacao'];

if (!in_array($userRole, $allowed)) {
    // Usuário não autorizado — mostrar mensagem simples com sidebar
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Configurações</title>
        <link rel="stylesheet" href="../css/materias.css">
    </head>
    <body>
        <header><?php include 'sidebar.php'; ?></header>
        <main style="max-width:900px;margin:28px auto;padding:18px;">
            <h1>Configurações</h1>
            <div style="padding:12px;background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                <p style="color:#c23;">Acesso restrito. Esta área está disponível apenas para a coordenação e desenvolvedores.</p>
            </div>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// Usuário autorizado: exibir forms de criação e listagem de matérias
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Configurações - Matérias</title>
    <link rel="stylesheet" href="../css/materias.css">
    <style>.config-grid{display:grid;grid-template-columns:1fr 360px;gap:18px}.card{background:#fff;padding:14px;border-radius:10px;box-shadow:0 1px 4px rgba(2,6,23,.06)}</style>
</head>
<body>
    <header><?php include 'sidebar.php'; ?></header>
    <main style="max-width:1100px;margin:24px auto;padding:18px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <h1>Configurações</h1>
            <div style="color:#374151;font-size:14px">Área restrita: coordenação & devs</div>
        </div>

        <div class="config-grid">
            <section class="card">
                <h2>Lista de Matérias</h2>
                <div id="lista" style="margin-top:12px"></div>
            </section>

            <aside class="card">
                <h2>Adicionar Matéria</h2>
                <div class="form-group" style="margin-bottom:10px">
                    <label>Nome</label>
                    <input id="nome" type="text">
                </div>
                <div class="form-group" style="margin-bottom:10px">
                    <label>Professor</label>
                    <input id="professor" type="text">
                </div>
                <div class="form-group" style="margin-bottom:12px">
                    <label>Conteúdo</label>
                    <textarea id="conteudo" rows="4"></textarea>
                </div>
                <div>
                    <button id="btnAdd">Adicionar</button>
                    <div id="msg" style="margin-top:8px"></div>
                </div>
            </aside>
        </div>

    </main>

    <script>
    async function fetchList(){
      const res = await fetch('../Back/list_materias.php');
      if(!res.ok) return;
      const data = await res.json();
      const el = document.getElementById('lista');
      el.innerHTML = '';
      if(!data.length){ el.innerHTML = '<p>Nenhuma matéria cadastrada.</p>'; return; }
      data.forEach(m=>{
        const d = document.createElement('div'); d.className='materia-item';
        d.innerHTML = `<h3 class="materia-name">${escapeHtml(m.nome)}</h3>
                       <p class="materia-info">${escapeHtml(m.conteudo || '')}</p>
                       <p class="materia-professor"><strong>Professor:</strong> ${escapeHtml(m.professor || '')}</p>`;
        el.appendChild(d);
      })
    }
    function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    document.getElementById('btnAdd').addEventListener('click', async ()=>{
      const nome = document.getElementById('nome').value.trim();
      const professor = document.getElementById('professor').value.trim();
      const conteudo = document.getElementById('conteudo').value.trim();
      const msg = document.getElementById('msg'); msg.style.color='green'; msg.textContent='';
      if(!nome){ msg.style.color='red'; msg.textContent='Preencha o nome.'; return; }
      try{
        const res = await fetch('../Back/add_materia.php', {
          method:'POST', headers: {'Content-Type':'application/json'},
          body: JSON.stringify({nome, professor, conteudo})
        });
        const j = await res.json();
        if(j.success){ msg.style.color='green'; msg.textContent='Matéria adicionada.'; document.getElementById('nome').value=''; document.getElementById('professor').value=''; document.getElementById('conteudo').value=''; fetchList(); }
        else { msg.style.color='red'; msg.textContent = j.message || 'Erro'; }
      }catch(e){ msg.style.color='red'; msg.textContent='Erro ao conectar.' }
    })

    fetchList();
    </script>
</body>
</html>
