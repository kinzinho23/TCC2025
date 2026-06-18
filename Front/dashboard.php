<?php session_start();
 include_once 'conexao.php';
 

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dashboard Aluno</title>

  <link rel="stylesheet" href="../css/dashboard.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <!-- SIDEBAR -->
  <?php include 'sidebar.php'; ?>

  <div class="container">

    <!-- HEADER -->
    <header class="header">

      <h1>
        Bom dia, <?php echo isset($_SESSION['nome']) ? $_SESSION['nome'] : 'Usuário'; ?>👋
      </h1>

    </header>

    <!-- CARDS -->
    <section class="top-cards">

      <!-- CARD -->
      <div class="card blue">

        <h3>Matérias Matriculadas</h3>

        <div class="number">
          6
        </div>

        <p>
          Matérias no total
        </p>

      </div>

      <!-- CARD -->
      <div class="card purple">

        <h3>Próxima Aula</h3>

        <div class="number">
          14:00
        </div>

        <p>
          Sala LAB-02
        </p>

      </div>

      <!-- CARD -->
      <div class="card green">

        <h3>Carga Horária</h3>

        <div class="number">
          120h
        </div>

        <p>
          60% concluída
        </p>

      </div>

      <!-- CARD -->
      <div class="card orange">

        <h3>Aulas Hoje</h3>

        <div class="number">
          3
        </div>

        <p>
          1 laboratório
        </p>

      </div>

    </section>

    <!-- HORÁRIO -->
    <section class="section">

      <h2 class="section-title">
        Meu Horário Semanal
      </h2>

      <table>

        <thead>

          <tr>
            <th>Horário</th>
            <th>Segunda</th>
            <th>Terça</th>
            <th>Quarta</th>
            <th>Quinta</th>
            <th>Sexta</th>
          </tr>

        </thead>

        <tbody>

          <tr>

            <td>08:00</td>

            <td>
              <div class="subject blue">
                Banco de Dados
              </div>
            </td>

            <td>
              <div class="subject purple">
                Desenvolvimento Web
              </div>
            </td>

            <td>
              <div class="subject green">
                Redes
              </div>
            </td>

            <td>
              <div class="subject orange">
                Engenharia
              </div>
            </td>

            <td>
              <div class="subject blue">
                Banco de Dados
              </div>
            </td>

          </tr>

        </tbody>

      </table>

    </section>

    <!-- MATÉRIAS -->
    <section class="section">

      <h2 class="section-title">
        Minhas Matérias
      </h2>

      <div class="materias-grid">

        <!-- CARD MATÉRIA -->
        <div class="materia-card">

          <h3>
            Banco de Dados
          </h3>

          <div class="codigo">
            BDD101
          </div>

          <div class="tipo">
            Teórica
          </div>

          <div class="progress">

            <div class="progress-bar">

            </div>

          </div>

          <p>
            75% concluído
          </p>

          <div class="footer-info">

            <span>60h</span>

            <span>LAB-01</span>

          </div>

        </div>

        <!-- CARD MATÉRIA -->
        <div class="materia-card">

          <h3>
            Desenvolvimento Web
          </h3>

          <div class="codigo">
            WEB201
          </div>

          <div class="tipo">
            Laboratório
          </div>

          <div class="progress">

            <div class="progress-bar progress-60">

            </div>

          </div>

          <p>
            60% concluído
          </p>

          <div class="footer-info">

            <span>40h</span>

            <span>LAB-02</span>

          </div>

        </div>

      </div>

    </section>

  </div>

</body>
</html>