<?php
// produtos/excluir.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /ProjetoPHP_GESTPROD/index.php");
    exit();
}

require_once __DIR__ . '/../includes/database.php';

// Verifica se o ID foi passado e é válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'ID do produto inválido!'
    ];
    header("Location: listar.php");
    exit();
}

$produto_id = intval($_GET['id']);

// Verifica se o produto existe antes de tentar excluir
$check_stmt = $conn->prepare("SELECT id FROM produtos WHERE id = ?");
$check_stmt->bind_param("i", $produto_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'Produto não encontrado!'
    ];
    header("Location: listar.php");
    exit();
}

// Executa a exclusão
$delete_stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
$delete_stmt->bind_param("i", $produto_id);

if ($delete_stmt->execute()) {
    $_SESSION['mensagem'] = [
        'tipo' => 'success',
        'texto' => 'Produto excluído com sucesso!'
    ];
} else {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'Erro ao excluir produto: ' . $conn->error
    ];
}

$delete_stmt->close();
$conn->close();

header("Location: listar.php");
exit();
?>