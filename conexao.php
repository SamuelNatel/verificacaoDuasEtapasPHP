<?php
    $host = "localhost";
    $user = "root";
    $password = "20122003";
    $dbname = "duasVerificacoes";

    try {
        $conn = new PDO("mysql:host=$host; dbname=".$dbname, $user, $password);
        //echo "Conexão com o banco de dados realizada com sucesso!";
    } catch (PDOException $err){
        echo "Erro: Conexão com o banco de dados não realizada com sucesso.";
        echo "Erro gerado: ".$err->getMessage();
    }
?>