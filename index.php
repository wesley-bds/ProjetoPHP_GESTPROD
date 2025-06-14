<?php
// Página de Login
session_start();
require_once 'includes/database.php'; 

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $username = $_POST['username'] ?? ''; 
    $password = $_POST['password'] ?? ''; 

    if (empty($username) || empty($password)) {
        
        $message = '<div class="alert alert-warning" role="alert">Por favor, preencha todos os campos.</div>';
    } else {
       
        $stmt = $conn->prepare("SELECT id, username, password FROM usuarios WHERE username = ?");
        if ($stmt === false) {
            $message = '<div class="alert alert-danger" role="alert">Erro na preparação da consulta: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
            
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: dashboard.php"); 
                    exit();
                } else {
                    $message = '<div class="alert alert-danger" role="alert">Usuário ou senha incorretos.</div>';
                }
            } else {
                $message = '<div class="alert alert-danger" role="alert">Usuário ou senha incorretos.</div>';
            }
            $stmt->close();
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestão de Produção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    </head>
<body class="login-page">

    <div class="login-container">
        <h1 class="mb-4">Painel de Controle</h1>
        <p class="text-muted">Produção</p> <?php echo $message;?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Usuário:</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-check mb-3 text-start"> <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe">Lembre-me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3"> ACESSAR
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigF/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>