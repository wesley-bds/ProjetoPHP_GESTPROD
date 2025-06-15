<?php
// Inicia a sessão se ainda não estiver iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestão de Produção</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Seu CSS customizado -->
    <link rel="stylesheet" href="/ProjetoPHP_GESTPROD/CSS/style.css" />
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/ProjetoPHP_GESTPROD/dashboard.php">
            <i class="fas fa-industry"></i> Gestão de Produção
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/ProjetoPHP_GESTPROD/dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/ProjetoPHP_GESTPROD/produtos/listar.php">
                        <i class="fas fa-box"></i> Produtos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white-50" href="#">
                        Olá, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Usuário'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-danger ms-2" href="/ProjetoPHP_GESTPROD/index.php">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
</header>
<main class="container mt-4">
    <!-- Conteúdo principal da página -->
</main>

<!-- Bootstrap 5 JS Bundle (inclui Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+0mLWKK9S2yE2nQ3Kf1dAH/t7J7Sk"
    crossorigin="anonymous"></script>
</body>
</html>
