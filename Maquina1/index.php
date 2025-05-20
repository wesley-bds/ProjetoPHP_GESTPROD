<?php
// Inicia a sessão (se necessário para armazenar dados do usuário ou do sistema)
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Máquina 1</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
        }
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 20px 40px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
        .header {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Máquina 1</h1>
        </div>

        <div>
            <!-- Botões de Navegação -->
            <a href="lote.php" class="button">Lote</a>
            <a href="apontamento.php" class="button">Apontamento</a>
            <a href="dados.php" class="button">Dados</a>
        </div>
    </div>
</body>
</html>
