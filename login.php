<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produção</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/style.css">

</head>
<body>

    <form class="form" action="logar.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title">Painel de Controle</h2>
                <p>Produção</p>
            </div>

            <div class="card-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Digite seu email" required> 
            </div>
            
            <div class="card-group">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Digite sua senha" required> 
            </div>
            
            <div class="card-group">
                <label><input type="checkbox" name="lembrar"> Lembre-me</label>
            </div>

            <?php
            session_start();
            if (isset($_SESSION['erro_login'])) {
            echo "<p style='color: red; text-align: center;'>" . $_SESSION['erro_login'] . "</p>";
            unset($_SESSION['erro_login']); // remove a mensagem depois de exibir
            }
            ?>

            <div class="card-group btn">
                <button type="submit">ACESSAR</button>
            </div> 
        </div>
    </form>
    
</body>
</html>
