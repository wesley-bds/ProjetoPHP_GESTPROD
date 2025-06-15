<?php
// produtos/editar.php
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
$produto_id = '';

// Verifica se foi passado um ID pela URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location:/ProjetoPHP_GESTPROD/produtos");
    exit();
}

$produto_id = intval($_GET['id']);

// Busca os dados atuais do produto
$stmt = $conn->prepare("SELECT nome, descricao FROM produtos WHERE id = ?");
if ($stmt === false) {
    die('Erro ao preparar a consulta: ' . $conn->error);
}

$stmt->bind_param("i", $produto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: listar.php");
    exit();
}

$produto = $result->fetch_assoc();
$nome = $produto['nome'];
$descricao = $produto['descricao'];
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    if (empty($nome)) {
        $message = '<div class="alert alert-warning" role="alert">O nome do produto é obrigatório!</div>';
    } else {
        // Verificar se outro produto já existe com este nome (excluindo o atual)
        $check_stmt = $conn->prepare("SELECT id FROM produtos WHERE nome = ? AND id != ?");
        if ($check_stmt === false) {
            $message = '<div class="alert alert-danger" role="alert">Erro na consulta de verificação: ' . $conn->error . '</div>';
        } else {
            $check_stmt->bind_param("si", $nome, $produto_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $message = '<div class="alert alert-danger" role="alert">Já existe outro produto com este nome.</div>';
            } else {
                // Atualizar o produto
                $stmt = $conn->prepare("UPDATE produtos SET nome = ?, descricao = ? WHERE id = ?");
                if ($stmt === false) {
                    $message = '<div class="alert alert-danger" role="alert">Erro ao preparar a atualização: ' . $conn->error . '</div>';
                } else {
                    $stmt->bind_param("ssi", $nome, $descricao, $produto_id);
                    if ($stmt->execute()) {
                        $message = '<div class="alert alert-success" role="alert">Produto "' . htmlspecialchars($nome) . '" atualizado com sucesso!</div>';
                    } else {
                        $message = '<div class="alert alert-danger" role="alert">Erro ao atualizar produto: ' . $stmt->error . '</div>';
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
    <h2 class="mb-4">Editar Produto</h2>
    <?php echo $message; ?>
    <form action="editar.php?id=<?php echo $produto_id; ?>" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($descricao); ?></textarea>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i> Atualizar Produto
            </button>
            <a href="listar.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i> Voltar
            </a>
        </div>
    </form>
</div>
</div>

<?php require_once '../includes/footer.php'; ?>