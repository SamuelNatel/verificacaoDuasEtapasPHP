<?php
    session_start();
    ob_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    date_default_timezone_set("America/Sao_Paulo");
    include_once("conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //echo password_hash(123456, PASSWORD_DEFAULT);
        if(!empty($dados["enviarLogin"])){
            $query = "SELECT id, nome, usuario, senha
                FROM users 
                WHERE usuario =:usuario
                LIMIT 1";

            $result_usuario = $conn->prepare($query);
            $result_usuario->bindParam(":usuario", $dados["usuario"]);
            $result_usuario->execute();
            if(($result_usuario) and ($result_usuario->rowCount() != 0)){
                $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                if(password_verify($dados["senha"], $row_usuario["senha"])){
                    $_SESSION["id"] = $row_usuario["id"];
                    $_SESSION["usuario"] = $row_usuario["usuario"];

                    $data = date("Y-m-d H:i:s");
                    $codigo_autenticacao = mt_rand(100000, 999999);
                
                    $query_up_user = "UPDATE users SET
                        codigo_auth =:codigo_autenticacao,
                        data_codigo_auth =:data_codigo_autenticacao
                        WHERE id =:id
                        LIMIT 1";

                    $result_up_usuario = $conn->prepare($query_up_user);
                    $result_up_usuario->bindParam(":codigo_autenticacao", $codigo_autenticacao);
                    $result_up_usuario->bindParam(":data_codigo_autenticacao", $data);
                    $result_up_usuario->bindParam(":id", $row_usuario["id"]);

                    $result_up_usuario->execute();

                    require "./lib/vendor/autoload.php";
                    $mail = new PHPMailer(true);

                    try {
                        $mail->CharSet = "UTF-8";
                        $mail->isSMTP();
                        $mail->Host = "sandbox.smtp.mailtrap.io";
                        $mail->SMTPAuth = true;
                        $mail->Username = "00bd17b11da4bf";
                        $mail->Password = "3971d5ed2fa7c3";
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 465;

                        $mail->setFrom("samuel.natel20@gmail.com", "Samuel Natel");
                        $mail->addAddress($row_usuario["usuario"], $row_usuario["nome"]);

                        $mail->isHTML(true);
                        $mail->Subject = "Teste assunto";
                        $mail->Body = "Olá ".$row_usuario["nome"].", Autenticação multifator. <br><br>Seu
                        código de verificação de 6 dígitos é $codigo_autenticacao<br><br> Esse código foi enviado
                        para verificar o seu login.<br><br>";
                        $mail->AltBody = "Olá ".$row_usuario["nome"].", Autenticação multifator. \n\nSeu
                        código de verificação de 6 dígitos é $codigo_autenticacao\n\n Esse código foi enviado
                        para verificar o seu login.\n\n";

                        $mail->send();

                        header("Location: validar_codigo.php");

                    } catch (Exception $e){
                        $_SESSION["msg"] = "<p style='color: #f00;'>Erro: Ocorreu um erro ao enviar o e-mail!</p>";
                    }
                } else {
                    $_SESSION["msg"] = "<p style='color: #f00;'>Erro: Usuário ou senha inválida!</p>";
                };
            } else {
                $_SESSION["msg"] = "<p style='color: #f00;'>Erro: Usuário ou senha inválida!</p>";
            }
        }

        if(isset($_SESSION["msg"])){
            echo $_SESSION["msg"];
            unset($_SESSION["msg"]);
        }
    ?>

    <form method="POST"> 
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" placeholder="Digite o usuário">
        <br><br>
        <label for="senha">Senha:</label>
        <input type="password" name="senha" placeholder="Digite a senha">
        <br><br>
        <input type="submit" name="enviarLogin" value="Acessar">
        <br><br>
    </form>

    Usuário: lixeiraparaapps@gmail.com <br>
    Senha: 123456
</body>
</html>