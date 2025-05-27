<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gerenciador";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $lote = $conn->real_escape_string($_POST['lote']);
    $apontamento = $conn->real_escape_string($_POST['apontamento']);
    $dados = $conn->real_escape_string($_POST['dados']);
    $contador = intval($_POST['contador']);

    $sql = "INSERT INTO maquinas (lote, apontamento, dados, contador) VALUES ('$lote', '$apontamento', '$dados', $contador)";
    if ($conn->query($sql)) {
        header("Location: maquinas.php?msg=sucesso");
        exit();
    } else {
        $erro = "Erro ao salvar: " . $conn->error;
    }
}

// Busca as máquinas cadastradas
$result = $conn->query("SELECT * FROM maquinas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Máquinas - Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  .card-add {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 5rem;
    color: #0d6efd;
    cursor: pointer;
    border: 2px dashed #0d6efd;
    height: 200px;
    transition: background-color 0.2s ease;
  }
  .card-add:hover {
    background-color: #e7f1ff;
  }
</style>
</head>
<body class="container mt-4">

<h2>Máquinas</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso'): ?>
  <div class="alert alert-success">Máquina cadastrada com sucesso!</div>
<?php endif; ?>

<?php if (!empty($erro)): ?>
  <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<div class="row g-4">

  <!-- Botão para abrir modal de adicionar máquina -->
  <div class="col-sm-6 col-md-4 col-lg-3">
    <div class="card card-add" data-bs-toggle="modal" data-bs-target="#modalAddMaquina">
      +
    </div>
  </div>

  <!-- Cards para cada máquina cadastrada -->
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Lote: <?= htmlspecialchars($row['lote']) ?></h5>
          <p><strong>Apontamento:</strong> <?= nl2br(htmlspecialchars($row['apontamento'])) ?></p>
          <p><strong>Dados:</strong> <?= nl2br(htmlspecialchars($row['dados'])) ?></p>
          <p><strong>Contador:</strong> <?= (int)$row['contador'] ?> peças</p>
        </div>
      </div>
    </div>
  <?php endwhile; ?>

</div>

<!-- Modal para adicionar máquina -->
<div class="modal fade" id="modalAddMaquina" tabindex="-1" aria-labelledby="modalAddMaquinaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="acao" value="adicionar" />
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddMaquinaLabel">Adicionar Máquina</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="lote" class="form-label">Lote (material produzido)</label>
          <input type="text" class="form-control" id="lote" name="lote" required>
        </div>
        <div class="mb-3">
          <label for="apontamento" class="form-label">Apontamento</label>
          <textarea class="form-control" id="apontamento" name="apontamento" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="dados" class="form-label">Dados</label>
          <textarea class="form-control" id="dados" name="dados" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label for="contador" class="form-label">Contador (nº de peças feitas)</label>
          <input type="number" class="form-control" id="contador" name="contador" min="0" value="0" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar Máquina</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
