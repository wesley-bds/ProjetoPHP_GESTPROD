<?php
// maquinas/relatorio.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../includes/database.php';
require_once '../includes/header.php';

$maquina_id = $_GET['id'] ?? 0;
$maquina = null;
$historico = [];
$message = '';

if ($maquina_id > 0) {
    // Busca os detalhes da máquina
    $stmt = $conn->prepare("SELECT m.nome, p.nome AS produto_nome FROM maquinas m LEFT JOIN produtos p ON m.produto_principal_id = p.id WHERE m.id = ?");
    $stmt->bind_param("i", $maquina_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $maquina = $result->fetch_assoc();
    } else {
        $message = '<div class="message error">Máquina não encontrada.</div>';
    }
    $stmt->close();

    // Busca o histórico de produção da máquina
    $stmt_hist = $conn->prepare("SELECT hp.*, p.nome AS produto_historico_nome
                                FROM historico_producao hp
                                JOIN produtos p ON hp.produto_id = p.id
                                WHERE hp.maquina_id = ?
                                ORDER BY hp.data_inicio DESC");
    $stmt_hist->bind_param("i", $maquina_id);
    $stmt_hist->execute();
    $result_hist = $stmt_hist->get_result();
    if ($result_hist && $result_hist->num_rows > 0) {
        while ($row = $result_hist->fetch_assoc()) {
            $historico[] = $row;
        }
    }
    $stmt_hist->close();

} else {
    $message = '<div class="message error">ID da máquina inválido.</div>';
}
$conn->close();
?>

<h1>Relatório de Produção: <?php echo htmlspecialchars($maquina['nome'] ?? 'N/A'); ?></h1>
<p><strong>Produto Principal Associado:</strong> <?php echo htmlspecialchars($maquina['produto_nome'] ?? 'N/A'); ?></p>

<?php echo $message; ?>

<?php if ($maquina): ?>
    <div style="margin-bottom: 20px;">
        <a href="detalhes.php?id=<?php echo $maquina_id; ?>" class="btn btn-secondary">Voltar para Detalhes</a>
        </div>

<?php if (empty($historico)): ?>
    <p>Não há histórico de produção para esta máquina ainda.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
        <th>Sessão ID</th>
        <th>Produto</th>
        <th>Início</th>
        <th>Fim</th>
        <th>Duração</th>
        <th>Quantidade Produzida</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($historico as $registro): ?>
    <tr>
        <td><?php echo htmlspecialchars($registro['id']); ?></td>
        <td><?php echo htmlspecialchars($registro['produto_historico_nome']); ?></td>
        <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($registro['data_inicio']))); ?></td>
        <td><?php echo htmlspecialchars($registro['data_fim'] ? date('d/m/Y H:i:s', strtotime($registro['data_fim'])) : 'Em Andamento'); ?></td>
        <td><?php echo htmlspecialchars($registro['tempo_duracao'] ? gmdate("H:i:s", $registro['tempo_duracao']) : 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($registro['quantidade_produzida']); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>