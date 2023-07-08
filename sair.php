<?php
    session_start();
    ob_start();

    unset($_SESSION["id"],$_SESSION["nome"],$_SESSION["usuario"],$_SESSION["codigo_autenticacao"]);

    if((!isset($_SESSION["id"])) and (!isset($_SESSION["usuario"])) and (!isset($_SESSION["codigo_autenticacao"]))){
        $_SESSION["msg"] = "<p style='color: green;'>Deslogado com sucesso!</p>";

        header("Location: index.php");
        exit();
    }
?>