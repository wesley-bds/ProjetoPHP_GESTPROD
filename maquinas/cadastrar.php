<?php
// maquinas/cadastrar.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit();
}

require_once '../includes/database.php';
require_once '../includes/header.php';

$message = '';
$nome = '';
$produto_principal_id = null;
$quantidade_meta = '';

$produtos_disponiveis = [];

// Buscar os produtos disponíveis
$sql_produtos = "SELECT id, nome FROM produtos ORDER BY nome ASC";
$result_produtos = $conn->query($sql_produtos);
if ($result_produtos && $result_produtos->num_rows > 0) {
    while ($row = $result_produtos->fetch_assoc()) {
        $produtos_disponiveis[] = $row;
    }
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $produto_principal_id = filter_var($_POST['produto_principal_id'] ?? '', FILTER_VALIDATE_INT);
    $quantidade_meta = $_POST['quantidade_meta'] ?? '';

    if ($produto_principal_id === false || empty($_POST['produto_principal_id'])) {
        $produto_principal_id = null;
    }

    // Validação
    if (empty($nome) || empty($quantidade_meta) || !is_numeric($quantidade_meta) || $quantidade_meta <= 0) {
        $message = '<div class="alert alert-warning" role="alert">Por favor, preencha todos os campos obrigatórios corretamente (Nome da Máquina e Meta de Produção).</div>';
    } else {
        // Verificar duplicidade
        $check_stmt = $conn->prepare("SELECT id FROM maquinas WHERE nome = ?");
        if ($check_stmt === false) {
            $message = '<div class="alert alert-danger" role="alert">Erro ao preparar consulta de verificação: ' . $conn->error . '</div>';
        } else {
            $check_stmt->bind_param("s", $nome);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $message = '<div class="alert alert-danger" role="alert">Já existe uma máquina com este nome.</div>';
            } else {
                // Inserção da nova máquina
                $stmt = $conn->prepare("INSERT INTO maquinas (nome, produto_principal_id, quantidade_meta, status, producao_atual, tempo_producao_acumulado) VALUES (?, ?, ?, 'Ociosa', 0, 0)");
                if ($stmt === false) {
                    $message = '<div class="alert alert-danger" role="alert">Erro ao preparar consulta de inserção: ' . $conn->error . '</div>';
                } else {
                    // Ajustar para permitir NULL no produto_principal_id
                    if ($produto_principal_id === null) {
                        $stmt->bind_param("sii", $nome, $produto_principal_id, $quantidade_meta);
                        $null = null;
                        $stmt->bind_param("sii", $nome, $null, $quantidade_meta);
                    } else {
                        $stmt->bind_param("sii", $nome, $produto_principal_id, $quantidade_meta);
                    }

                    if ($stmt->execute()) {
                        $message = '<div class="alert alert-success" role="alert">Máquina "' . htmlspecialchars($nome) . '" cadastrada com sucesso!</div>';
                        $nome = '';
                        $produto_principal_id = null;
                        $quantidade_meta = '';
                    } else {
                        $message = '<div class="alert alert-danger" role="alert">Erro ao cadastrar máquina: ' . $stmt->error . '</div>';
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

<div class="card shadow-sm p-4">
    <h2 class="mb-4">Cadastrar Nova Máquina</h2>
    <?php echo $message; ?>
    <form action="cadastrar.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome da Máquina:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="mb-3">
            <label for="produto_principal_id" class="form-label">Produto Principal Associado:</label>
            <select class="form-select" id="produto_principal_id" name="produto_principal_id">
                <option value="">-- Selecione um Produto (Opcional) --</option>
                <?php foreach ($produtos_disponiveis as $produto): ?>
                    <option value="<?php echo $produto['id']; ?>" <?php echo ($produto_principal_id == $produto['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($produto['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantidade_meta" class="form-label">Meta de Produção:</label>
            <input type="number" class="form-control" id="quantidade_meta" name="quantidade_meta" value="<?php echo htmlspecialchars($quantidade_meta); ?>" required min="1">
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save me-2"></i>Salvar Máquina
            </button>
            <a href="../dashboard.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Voltar para o Dashboard
            </a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
