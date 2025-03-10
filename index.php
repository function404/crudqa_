<?php
    include('src/include/conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h1>Login</h1>
        <form action="" method="POST">
            <br>
            <input type="text" name="usuario" placeholder="Usuário">
            <br><br>      
            <input type="password" name="senha" placeholder="Senha">
            <br><br>   
            <button type="submit">Entrar</button>
        </form>

        <p>
            Não tem uma conta? <a href="src/screens/register.php">Cadastre-se</a>
        </p>
    </div>
<?php
   include('src/components/footer.php');
?>