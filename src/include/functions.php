<?php 
   /**
    * Sanitiza os dados de entrada
    *
    * @param string $data Dado a ser sanitizado
    * @return string Dado limpo e seguro
   */
   function test_input($data) {
      // Remove espaços em branco no início e no fim da string
      $data = trim($data);
      
      // Remove barras invertidas (\) da string
      $data = stripcslashes($data);

      // Converte caracteres especiais para entidades HTML
      $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
      return $data;
   }
?>
