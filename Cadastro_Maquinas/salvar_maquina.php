<?php
$conn = new mysqli("localhost", "root", "", "gerenciador");

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$lote = $_POST['lote'];
$apontamento = $_POST['apontamento'];
$dados = $_POST['dados'];
$contador = 0;

$sql = "INSERT INTO maquinas (lote, apontamento, dados, contador)
        VALUES ('$lote', '$apontamento', '$dados', $contador)";

if ($conn->query($sql) === TRUE) {
    header("Location: sucesso.php");
    exit();
} else {
    echo "Erro ao salvar: " . $conn->error;
}

$conn->close();
?>
