<?php
    $host = "localhost";
    $user = "root";
    $password = "****";
    $dbname = "****";

    try {
        $conn = new PDO("mysql:host=$host; dbname=".$dbname, $user, $password);
        //echo "Conexão com o banco de dados realizada com sucesso!";
    } catch (PDOException $err){
        echo "Erro: Conexão com o banco de dados não realizada com sucesso.";
        echo "Erro gerado: ".$err->getMessage();
    }
?>
