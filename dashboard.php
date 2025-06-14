<?php
// Inclui o cabeçalho global
include_once __DIR__ . '/includes/header.php';
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
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total de Máquinas</h5>
                    <p class="card-text">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Em Produção</h5>
                    <p class="card-text">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ociosas</h5>
                    <p class="card-text">0</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Paradas</h5>
                    <p class="card-text">0</p>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info text-center mt-4" role="alert">
        Nenhuma máquina cadastrada ainda. <a href="#">Cadastre a primeira!</a>
    </div>
</main>

<?php
// Inclui o rodapé global
include_once __DIR__ . '/includes/footer.php';
?>
