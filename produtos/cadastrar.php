<?php
// produtos/cadastrar.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redireciona para a página de login
    exit();
}

require_once '../includes/database.php'; // Conexão com o banco
require_once '../includes/header.php';   // Header da página

$message = '';
$nome = '';
$descricao = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    if (empty($nome)) {
        $message = '<div class="alert alert-warning" role="alert">O nome do produto é obrigatório!</div>';
    } else {
        // Verificar se o produto já existe
        $check_stmt = $conn->prepare("SELECT id FROM produtos WHERE nome = ?");
        if ($check_stmt === false) {
            $message = '<div class="alert alert-danger" role="alert">Erro na consulta de verificação: ' . $conn->error . '</div>';
        } else {
            $check_stmt->bind_param("s", $nome);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $message = '<div class="alert alert-danger" role="alert">Já existe um produto com este nome.</div>';
            } else {
                // Inserir novo produto
                $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao) VALUES (?, ?)");
                if ($stmt === false) {
                    $message = '<div class="alert alert-danger" role="alert">Erro ao preparar o cadastro: ' . $conn->error . '</div>';
                } else {
                    $stmt->bind_param("ss", $nome, $descricao);
                    if ($stmt->execute()) {
                        $message = '<div class="alert alert-success" role="alert">Produto "' . htmlspecialchars($nome) . '" cadastrado com sucesso!</div>';
                        // Limpa os campos
                        $nome = '';
                        $descricao = '';
                    } else {
                        $message = '<div class="alert alert-danger" role="alert">Erro ao cadastrar produto: ' . $stmt->error . '</div>';
                    }
                    $stmt->close();
                }
            }
            $check_stmt->close();
        }
    }
}
$conn->close();
?>

<div class="container mt-5">
<div class="card shadow-sm p-4">
    <h2 class="mb-4">Cadastrar Novo Produto</h2>
    <?php echo $message; ?>
    <form action="cadastrar.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição:</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($descricao); ?></textarea>
    </div>
    <div class="d-flex justify-content-between mt-4">
        <button type="submit" class="btn btn-success btn-lg">
            <i class="fas fa-save me-2"></i> Salvar Produto
        </button>
        <a href="listar.php" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left me-2"></i> Voltar
        </a>
        </div>
    </form>
</div>
</div>

<?php require_once '../includes/footer.php'; ?>
