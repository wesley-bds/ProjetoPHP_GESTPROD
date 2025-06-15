<?php
// Inclui o cabeçalho global
include_once __DIR__ . '/includes/header.php';

// Conexão com o banco de dados
require_once __DIR__ . '/includes/database.php';

// Consulta o status das máquinas
$query = "SELECT status, COUNT(*) as total FROM maquinas GROUP BY status";
$result = $conn->query($query);

$status_counts = [
    'produção' => 0,
    'ociosa' => 0,
    'parada' => 0,
    'manutencao' => 0
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = strtolower($row['status']);
        $status_counts[$status] = $row['total'];
    }
}

// Total de máquinas
$total_maquinas = array_sum($status_counts);

// Fecha a conexão
$conn->close();
?>

<main class="container mt-4">
    <h1>Dashboard</h1>
    <p>Bem-vindo ao seu painel de controle. Aqui você pode ver um resumo da produção.</p>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    Resumo da Produção
                </div>
                <div class="card-body">
                    <p>Total de Produtos Produzidos: <strong>1234</strong></p>
                    <p>Produtos Pendentes: <strong>56</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    Próximas Ordens
                </div>
                <div class="card-body">
                    <ul>
                        <li>Ordem #001 - Produto A (20/06/2025)</li>
                        <li>Ordem #002 - Produto B (22/06/2025)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<h2 class="mt-5">Status das Máquinas</h2>
<div class="row">
    <div class="col-md-3">
        <a href="/ProjetoPHP_GESTPROD/maquinas/detalhes.php?status=all" class="text-decoration-none">
            <div class="card text-white bg-primary mb-3 hover-effect">
                <div class="card-body text-center">
                    <h5 class="card-title">Total de Máquinas</h5>
                    <p class="card-text display-5"><?= $total_maquinas ?></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/ProjetoPHP_GESTPROD/maquinas/detalhes.php?status=produção" class="text-decoration-none">
            <div class="card text-white bg-success mb-3 hover-effect">
                <div class="card-body text-center">
                    <h5 class="card-title">Em Produção</h5>
                    <p class="card-text display-5"><?= $status_counts['produção'] ?></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/ProjetoPHP_GESTPROD/maquinas/detalhes.php?status=ociosa" class="text-decoration-none">
            <div class="card text-white bg-warning mb-3 hover-effect">
                <div class="card-body text-center">
                    <h5 class="card-title">Ociosas</h5>
                    <p class="card-text display-5"><?= $status_counts['ociosa'] ?></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/ProjetoPHP_GESTPROD/maquinas/detalhes.php?status=parada" class="text-decoration-none">
            <div class="card text-white bg-danger mb-3 hover-effect">
                <div class="card-body text-center">
                    <h5 class="card-title">Paradas</h5>
                    <p class="card-text display-5"><?= $status_counts['parada'] ?></p>
                </div>
            </div>
        </a>
    </div>
</div>
    
    <div class="alert alert-info text-center mt-4" role="alert">
        <a href="/ProjetoPHP_GESTPROD/maquinas/cadastrar.php" class="btn btn-primary btn-lg">Cadastrar Maquina</a>
    </div>
    
</main>

<?php
// Inclui o rodapé global
include_once __DIR__ . '/includes/footer.php';
?>