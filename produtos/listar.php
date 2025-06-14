<?php
// produtos/listar.php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Ajuste o caminho se a sua página de login não for diretamente em /projeto2/index.php
    header("Location: /projeto2/index.php");
    exit();
}

require_once __DIR__ . '/../includes/database.php'; // Conexão com o banco
require_once __DIR__ . '/../includes/header.php';   // Header da página

// Consulta para buscar produtos
// Ordena por 'id' em ordem decrescente para mostrar os mais novos primeiro
$sql = "SELECT id, nome, descricao, estoque FROM produtos ORDER BY id DESC";
$result = $conn->query($sql);

$produtos = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
$conn->close();
?>

<main class="container mt-4">
    <h1 class="mb-4">Gestão de Produtos</h1> <?php if (empty($produtos)): ?>
        <div class="alert alert-info text-center" role="alert">
            Nenhum produto cadastrado ainda. <a href="/projeto2/produtos/cadastrar.php" class="alert-link">Cadastre o primeiro!</a>
    </div>
<?php else: ?>
    <div class="card shadow-sm p-4">
        <div class="table-responsive">
    <table class="table table-hover table-striped align-middle">
        <thead>
            <tr>
            <th scope="col">#ID</th>
            <th scope="col">Nome do Produto</th>
            <th scope="col">Descrição</th>
            <th scope="col">Estoque</th>
            <th scope="col" class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
        <tr>
        <td><?php echo htmlspecialchars($produto['id']); ?></td>
        <td><?php echo htmlspecialchars($produto['nome']); ?></td>
        <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
        <td><?php echo htmlspecialchars($produto['estoque'] ?? 'N/A'); ?></td>
        <td class="text-center">
            <a href="/projeto2/produtos/editar.php?id=<?php echo htmlspecialchars($produto['id']); ?>" class="btn btn-sm btn-info me-2" title="Editar Produto">
                <i class="fas fa-edit"></i>
            </a>
            <a href="/projeto2/produtos/excluir.php?id=<?php echo htmlspecialchars($produto['id']); ?>" class="btn btn-sm btn-danger" title="Excluir Produto" onclick="return confirm('Tem certeza que deseja excluir este produto?');">
                <i class="fas fa-trash-alt"></i>
            </a>
        </td>
        </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4"> <a href="/projeto2/produtos/cadastrar.php" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle me-2"></i>Cadastrar Novo Produto
        </a>
    </div>

    <?php endif; ?>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>