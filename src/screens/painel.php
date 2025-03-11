<?php
   include('../include/protect.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Painel</title>
   </head>
   <body>
      Bem vindo ao Painel, 
      <?php 
         // mostra o nome do usuário logado.
         echo $_SESSION["nome"];

         // se for administrador, mostra a informação "(Administrador)".
         echo $_SESSION["administrador"] ? " (Administrador)" : "";
      ?>.
      <p>
         <a href="../include/logout.php">Sair</a>
      </p>
<?php
   include('../components/footer.php');
?>