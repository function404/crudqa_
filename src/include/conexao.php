<?php
   // // Define as constantes de configuração do banco de dados
   // define('HOST', 'localhost');    // Endereço do servidor MySQL
   // define('DB', 'crudqa');         // Nome do banco de dados
   // define('USER', 'root');         // Usuário do banco de dados
   // define('PASS', '');             // Senha do banco de dados

   // try {
   //    // Cria uma nova conexão PDO com o MySQL, especificando o host, porta e nome do banco
   //    // O parâmetro PDO::MYSQL_ATTR_INIT_COMMAND define o comando inicial, aqui configurando o charset para utf8
   //    $pdo = new PDO(
   //       'mysql:host=' . HOST . ';port=3306;dbname=' . DB,
   //       USER,
   //       PASS,
   //       array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
   //    );
      
   //    // Configura o PDO para lançar exceções em caso de erro
   //    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                

   // } catch (Exception $e) {
   //    // Em caso de falha na conexão, exibe a mensagem de erro
   //    echo 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
   // }


   $usuario = 'root';
   $senha = '';
   $database = 'sistema';
   $host = 'localhost';

   $mysqli = new mysqli($host, $usuario, $senha, $database);

   if($mysqli->error) {
      die("Falha ao conectar ao banco de dados: " . $mysqli->error);
   }
?>