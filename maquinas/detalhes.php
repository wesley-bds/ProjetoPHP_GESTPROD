<?php
// maquinas/detalhes.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../includes/database.php';
require_once '../includes/header.php';

$maquina_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$maquina = null;
$message = '';

function carregarMaquina($conn, $maquina_id) {
    $stmt = $conn->prepare("SELECT m.*, p.nome AS produto_nome FROM maquinas m LEFT JOIN produtos p ON m.produto_principal_id = p.id WHERE m.id = ?");
    $stmt->bind_param("i", $maquina_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = $result->num_rows === 1 ? $result->fetch_assoc() : null;
    $stmt->close();
    return $dados;
}

if ($maquina_id > 0) {
    $maquina = carregarMaquina($conn, $maquina_id);
    if (!$maquina) {
        $message = '<div class="alert alert-danger">Máquina não encontrada.</div>';
    }
} else {
    $message = '<div class="alert alert-danger">ID da máquina inválido.</div>';
}

// Processamento de Ações
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $maquina) {
    $action = $_POST['action'] ?? '';

    if ($action == 'iniciar_producao') {
        if (in_array($maquina['status'], ['Ociosa', 'Parada'])) {
            $stmt_hist = $conn->prepare("INSERT INTO historico_producao (maquina_id, produto_id, data_inicio, quantidade_produzida) VALUES (?, ?, NOW(), 0)");
            $stmt_hist->bind_param("ii", $maquina_id, $maquina['produto_principal_id']);
            if ($stmt_hist->execute()) {
                $historico_id = $conn->insert_id;
                $stmt_upd = $conn->prepare("UPDATE maquinas SET status = 'Em Producao', producao_atual = 0, tempo_producao_acumulado = 0, historico_ativo_id = ? WHERE id = ?");
                $stmt_upd->bind_param("ii", $historico_id, $maquina_id);
                $stmt_upd->execute();
                $stmt_upd->close();
                header("Location: detalhes.php?id=" . $maquina_id);
                exit();
            } else {
                $message = '<div class="alert alert-danger">Erro ao iniciar produção: ' . $stmt_hist->error . '</div>';
            }
            $stmt_hist->close();
        } else {
            $message = '<div class="alert alert-warning">A máquina já está em produção.</div>';
        }
    }

    if ($action == 'parar_producao') {
        if ($maquina['status'] == 'Em Producao') {
            $quantidade_produzida = intval($_POST['quantidade_produzida'] ?? 0);
            $historico_id_ativo = intval($maquina['historico_ativo_id'] ?? 0);

            if ($historico_id_ativo) {
                $stmt_hist_upd = $conn->prepare("UPDATE historico_producao SET data_fim = NOW(), quantidade_produzida = ?, tempo_duracao = TIMESTAMPDIFF(SECOND, data_inicio, NOW()) WHERE id = ?");
                $stmt_hist_upd->bind_param("ii", $quantidade_produzida, $historico_id_ativo);
                if ($stmt_hist_upd->execute()) {
                    $stmt_upd = $conn->prepare("UPDATE maquinas SET status = 'Parada', producao_atual = ?, tempo_producao_acumulado = (SELECT SUM(tempo_duracao) FROM historico_producao WHERE maquina_id = ?) WHERE id = ?");
                    $stmt_upd->bind_param("iii", $quantidade_produzida, $maquina_id, $maquina_id);
                    $stmt_upd->execute();
                    $stmt_upd->close();
                    header("Location: detalhes.php?id=" . $maquina_id);
                    exit();
                } else {
                    $message = '<div class="alert alert-danger">Erro ao parar produção: ' . $stmt_hist_upd->error . '</div>';
                }
                $stmt_hist_upd->close();
            } else {
                $message = '<div class="alert alert-warning">Nenhuma sessão de produção ativa para parar.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">A máquina não está em produção.</div>';
        }
    }

    // Recarregar detalhes atualizados
    $maquina = carregarMaquina($conn, $maquina_id);
}

$conn->close();
?>

<div class="container mt-4">
    <h1>Detalhes da Máquina: <?php echo htmlspecialchars($maquina['nome'] ?? 'N/A'); ?></h1>
    <?php echo $message; ?>

    <?php if ($maquina): ?>
        <div class="card p-4 mb-4">
            <p><strong>Produto Principal:</strong> <?php echo htmlspecialchars($maquina['produto_nome'] ?? 'Não Definido'); ?></p>
            <p><strong>Meta de Produção:</strong> <?php echo htmlspecialchars($maquina['quantidade_meta']); ?></p>
            <p><strong>Produção Atual (Sessão):</strong> <?php echo htmlspecialchars($maquina['producao_atual']); ?></p>
            <p><strong>Tempo Total Produção:</strong> <?php echo gmdate("H:i:s", $maquina['tempo_producao_acumulado']); ?></p>
            <p><strong>Status:</strong> <span class="badge bg-<?php echo ($maquina['status'] == 'Em Producao') ? 'success' : (($maquina['status'] == 'Parada') ? 'warning' : 'secondary'); ?>">
                <?php echo htmlspecialchars($maquina['status']); ?>
            </span></p>

            <div class="mt-3">
                <?php if (in_array($maquina['status'], ['Ociosa', 'Parada'])): ?>
                    <form action="detalhes.php?id=<?php echo $maquina_id; ?>" method="POST" class="d-inline">
                        <input type="hidden" name="action" value="iniciar_producao">
                        <button type="submit" class="btn btn-success">Iniciar Produção</button>
                    </form>
                <?php elseif ($maquina['status'] == 'Em Producao'): ?>
                    <form action="detalhes.php?id=<?php echo $maquina_id; ?>" method="POST" class="d-inline">
                        <input type="hidden" name="action" value="parar_producao">
                        <div class="input-group mb-2" style="width:200px;">
                            <input type="number" name="quantidade_produzida" class="form-control" min="0" placeholder="Qtd Produzida" required>
                            <button type="submit" class="btn btn-danger">Parar Produção</button>
                        </div>
                    </form>
                <?php endif; ?>

                <a href="relatorio.php?id=<?php echo $maquina_id; ?>" class="btn btn-primary">Gerar Relatório</a>
                <a href="../dashboard.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
