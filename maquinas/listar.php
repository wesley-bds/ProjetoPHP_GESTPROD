<?php
// maquinas/listar.php
session_start();
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/header.php';

$sql = "SELECT * FROM maquinas ORDER BY id DESC";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h2>Lista de Máquinas</h2>
    <a href="cadastrar.php" class="btn btn-primary mb-3">Nova Máquina</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="detalhes.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">Detalhes</a>
                    <a href="editar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
$conn->close();
require_once __DIR__ . '/../includes/footer.php';