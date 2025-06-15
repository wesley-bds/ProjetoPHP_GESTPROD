<?php
// maquinas/detalhes.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../includes/database.php';
require_once '../includes/header.php';

// Verifica se o ID foi passado e é numérico
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'ID da máquina não foi especificado ou é inválido!'
    ];
    header("Location: listar.php");  // Corrigido: redireciona para listar.php em vez de detalhes.php
    exit();
}

$maquina_id = intval($_GET['id']);
$maquina = null;
$message = '';

// Função para carregar dados da máquina
function carregarMaquina($conn, $maquina_id) {
    $stmt = $conn->prepare("SELECT m.*, p.nome AS produto_nome 
                          FROM maquinas m 
                          LEFT JOIN produtos p ON m.produto_principal_id = p.id 
                          WHERE m.id = ?");
    if ($stmt === false) {
        die('Erro ao preparar a consulta: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $maquina_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = $result->num_rows === 1 ? $result->fetch_assoc() : null;
    $stmt->close();
    return $dados;
}

// Carrega os dados da máquina
$maquina = carregarMaquina($conn, $maquina_id);

if (!$maquina) {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'Máquina não encontrada!'
    ];
    header("Location: listar.php");  // Corrigido: redireciona para listar.php
    exit();
}
?>

<!-- Restante do seu HTML permanece igual -->

<div class="container mt-4">
    <h1>Detalhes da Máquina: <?php echo htmlspecialchars($maquina['nome']); ?></h1>
    
    <?php 
    // Exibe mensagens de sessão se existirem
    if (isset($_SESSION['mensagem'])) {
        echo '<div class="alert alert-'.$_SESSION['mensagem']['tipo'].'">';
        echo $_SESSION['mensagem']['texto'];
        echo '</div>';
        unset($_SESSION['mensagem']);
    }
    ?>
    
    <div class="card p-4 mb-4">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> <?php echo $maquina['id']; ?></p>
                <p><strong>Produto Principal:</strong> <?php echo htmlspecialchars($maquina['produto_nome'] ?? 'Não definido'); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?php 
                        echo ($maquina['status'] == 'Em Producao') ? 'success' : 
                             (($maquina['status'] == 'Parada') ? 'danger' : 
                             (($maquina['status'] == 'Ociosa') ? 'warning' : 'secondary')); 
                    ?>">
                        <?php echo htmlspecialchars($maquina['status']); ?>
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Meta de Produção:</strong> <?php echo $maquina['quantidade_meta'] ?? '0'; ?></p>
                <p><strong>Produção Atual:</strong> <?php echo $maquina['producao_atual'] ?? '0'; ?></p>
                <p><strong>Tempo Total:</strong> 
                    <?php echo isset($maquina['tempo_producao_acumulado']) ? 
                          gmdate("H:i:s", $maquina['tempo_producao_acumulado']) : '00:00:00'; ?>
                </p>
            </div>
        </div>

        <div class="mt-4">
            <!-- Seus botões de ação aqui -->
            <a href="editar.php?id=<?php echo $maquina_id; ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar Máquina
            </a>
            <a href="listar.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<?php 
$conn->close();
require_once '../includes/footer.php'; 
?>