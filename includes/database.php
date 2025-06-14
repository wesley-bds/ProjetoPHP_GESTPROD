<?php
// onexão com o banco de dados
$servername = "localhost";
$username = "root"; 
$password = "";    
$dbname = "gestao_producao_db"; 

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Define o charset para evitar problemas com acentuação
$conn->set_charset("utf8mb4");
?>