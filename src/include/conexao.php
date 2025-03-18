<?php
   /**
    * Define as constantes de configuração do banco de dados
    */ 
   define('HOST', 'localhost');    // Endereço do servidor MySQL
   define('DB', 'crudqa');         // Nome do banco de dados
   define('USER', 'root');         // Usuário do banco de dados
   define('PASS', '');             // Senha do banco de dados

   try {
      /**
       * Cria uma nova conexão PDO com o MySQL, especificando o host, porta e nome do banco
       * O parâmetro PDO::MYSQL_ATTR_INIT_COMMAND define o comando inicial, aqui configurando o charset para utf8
       */
      $pdo = new PDO(
         'mysql:host=' . HOST . ';port=3306;dbname=' . DB,
         USER,
         PASS,
         array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
      );
      
      /**
       * Configura o PDO para lançar exceções em caso de erro
       */ 
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                

   } catch (Exception $e) {
      // Exibe uma tela amigável em caso de erro na conexão
      echo "<!DOCTYPE html>
            <html lang='pt-br'>
            <head>
               <meta charset='UTF-8'>
               <title>Erro de Conexão</title>
               <style>
                  body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
                  .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9; }
               </style>
            </head>
            <body>
               <div class='container'>
                  <h1>Erro ao conectar ao banco de dados</h1>
                  <p>Estamos offline... Tente novamente mais tarde.</p>
               </div>
            </body>
            </html>";
            
         exit();
   }
?>