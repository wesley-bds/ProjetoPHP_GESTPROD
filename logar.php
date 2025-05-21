<?php
session_start();

if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) {

    require 'conexao2.php';
    require 'usuario.php';

    $u = new Usuario();

    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);

    if($u->login($email, $senha)) {
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['erro_login'] = "Usuário ou senha incorretos!";
        header("Location: login.php");
        exit;
    }

} else {
    header("Location: login.php");
    exit;
}
