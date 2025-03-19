<?php
   include('../components/header.php');
   include('../include/protect.php');
   include('../include/conexao.php');
   
   echo "<title>Home | StockMaster</title>";
   echo "<div class='container'>";
   
   echo "<div class='top-painel'>";
   echo "<h1 style='font-size: 3vw;'>";	
   echo "Bem-vindo, " .  $_SESSION["nomeUsuario"] . "</h1>";

   echo "<div class='sair'>";
      echo "<p>";
         echo "<a href='../include/logout.php'>Sair <i class='fa-solid fa-right-from-bracket'></i></a>";
      echo "</p>";
   echo "</div>";

   echo "</div>";

   echo $_SESSION["administrador"] ? " (Administrador)" : "";
   echo "<br><br>";

   if ($_SESSION["administrador"]) {
      echo "<div class='button-admin'>";
         echo "<div class='button-admin-content'>";
            echo "<a href='admin.php'>Ir painel de Administração</a>";
            echo "<i class='fa-solid fa-arrow-right'></i>";
         echo "</div>";
      echo "</div>";
   }

   /**
    * Consulta os produtos disponíveis no banco de dados para listar  produtos para usuários comuns
    */ 
   $sql = $pdo->query("SELECT nomeProduto, descricao, valor, quantidade, imagem FROM produto WHERE imagem IS NOT NULL");
   $produtos = $sql->fetchAll(PDO::FETCH_ASSOC);

   /**
    * Verifica se existem produtos cadastrados 
    */
   if ($produtos) {
      echo "<h2 style='text-align: center; font-size: 1.7vw; margin-bottom: 10px'>Produtos Disponíveis</h2>";
      echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; overflow: hidden;'>";
      foreach ($produtos as $produto) {
         /**
          * Exibe cada produto com nome, descrição, valor e imagem
          */
         echo "<div class='card-products'>";
            /**
             * Se o produto tiver imagem, exibe a imagem 
             */
            if (!empty($produto['imagem'])) {
               echo "<img src='data:image/jpeg;base64," . base64_encode($produto['imagem']) . "' width='100%'/>";
            }
            echo "<p style='font-size: 1.5rem; font-weight: 800; margin: 10px 0px 15px 0px'; text-transform: uppercase;'>{$produto['nomeProduto']}</p>";
            echo "<p style='font-size: 1.4rem; font-weight: 800; margin: 0px 0px 16px 0px'>R$ " . number_format($produto['valor'], 2, ',', '.') . "</p>";
            echo "<p class='card-description'>{$produto['descricao']}</p>";
            echo "<p style='font-size: 1rem; margin-top: 15px; margin-bottom: 15px; font-weight: 800;'>{$produto['quantidade']} Unidades</p>";
            
         echo "</div>";
      }
      echo "</div>";
   } else {
      /**
       * Exibe mensagem caso não haja produtos disponíveis
       */
      echo "<p>Nenhum produto disponível.</p>";
   }
   echo "</div>";
?>

<?php include('../components/footer.php'); ?>
