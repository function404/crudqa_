<?php
   // Verifica se a sessão já foi iniciada; se não, inicia uma sessão nova 
   if (!isset($_SESSION)) {
      session_start();
   }
   // Destroi todos os dados da sessão atual
   session_destroy();
   // Redireciona o usuário para a página do (index.php)
   header("Location: ../../index.php");
?>
