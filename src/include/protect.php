<?php
   // Verifica se a sessão já foi iniciada; se não, inicia uma nova sessão
   if (!isset($_SESSION)) {
      session_start();
   }

   // Verifica se a variável de sessão 'id' está definida (que indica o usuário logado)
   if (!isset($_SESSION['idUsuario'])) {
      // Se o usuário não estiver logado, interrompe a execução e exibe uma mensagem de acesso negado com link para login
      die("
         <!DOCTYPE html>
         <html lang='pt-br'>
         <head>
            <meta charset='UTF-8'>
            <meta http-equiv='X-UA-Compatible' content='IE=edge'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='stylesheet' href='../css/style.css'>
            <link rel='preconnect' href='https://fonts.googleapis.com'>
            <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
            <title>Opss | Faça login</title>
            <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap' rel='stylesheet'>
            <link rel='shortcut icon' type='image/x-icon' sizes='32x32' href='../public/boxIcon.png'>
         </head>
         <body>
            <div style='text-align: center; margin-top: 50px;'>
               <p style='color: rgb(255, 56, 56); font-size: 1.2em;'>Opss! Faça o login para continuar</p>
               <p>Você não pode acessar esta página porque não está logado, <a href=\"../../index.php\">Entrar</a>.</p>
            <div>
         </body>
         </html>
      "
      );
   }
?>
