<?php
   // Verifica se a sessão já foi iniciada; se não, inicia uma nova sessão
   if (!isset($_SESSION)) {
      session_start();
   }

   // Verifica se a variável de sessão 'id' está definida (que indica o usuário logado)
   if (!isset($_SESSION['idUsuario'])) {
      // Se o usuário não estiver logado, interrompe a execução e exibe uma mensagem de acesso negado com link para login
      die("Você não pode acessar esta página porque não está logado. 
         <p>
            <a href=\"../../index.php\">
               Entrar
            </a>
         </p>"
      );
   }
?>
