<?php
    session_start();
    ob_start();
    date_default_timezone_set("America/Sao_Paulo");
    include_once "./conexao.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação</title>
</head>
<body>
    <?php
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(!empty($dados["ValCodigo"])){
            $query_usuario = "SELECT id, nome, usuario, senha
                                FROM users
                                WHERE id =:id
                                AND usuario =:usuario
                                AND codigo_auth =:codigo_autenticacao;";
            
            $result_usuario = $conn->prepare($query_usuario);
            $result_usuario->bindParam(":id", $_SESSION["id"]);
            $result_usuario->bindParam(":usuario", $_SESSION["usuario"]);
            $result_usuario->bindParam(":codigo_autenticacao", $dados["codigo_autenticacao"]);

            $result_usuario->execute();
            
            if(($result_usuario) and ($result_usuario->rowCount() != 0)){
                $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                $query_up_usuario = "UPDATE users SET
                                        codigo_auth = NULL,
                                        data_codigo_auth = NULL
                                        WHERE id =:id
                                        LIMIT 1";
                
                $result_up_usuario = $conn->prepare($query_up_usuario);
                $result_up_usuario->bindParam(":id", $_SESSION["id"]);
                $result_up_usuario->execute();

                $_SESSION["nome"] = $row_usuario["nome"];
                $_SESSION["codigo_autenticacao"] = true;

                header("Location: dashboard.php");
            }else {
                $_SESSION["msg"] = "<p style='color: #f00; '>Erro: Código inválido!</p>";
                header("Location: index.php");
                exit();
            }
        }
    ?>
    <form method="POST" action="">
        <label for="codigo_autenticacao">Código:</label>
        <input type="text" name="codigo_autenticacao" id="codigo_autenticacao" placeholder="Digite o código">
        <br><br>
        <input type="submit" value="ValCodigo" name="ValCodigo">
        <br><br>
    </form>
</body>
</html>