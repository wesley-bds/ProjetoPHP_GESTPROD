<?php

class Usuario {

    public function login($email, $senha) {
        global $pdo;

        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
        $sql = $pdo->prepare($sql);
        $sql->bindValue("email", $email);
        $sql->bindValue("senha", md5($senha));
        $sql->execute();

        if($sql->rowCount() > 0) {
            $dado = $sql->fetch();
            session_start();
            $_SESSION['idUser'] = $dado['idusuario'];
            return true;
        } else {
            return false;
        }
    }
}
?>
