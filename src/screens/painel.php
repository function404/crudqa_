<?php
   include('../components/header.php');
   include('../include/protect.php');
   include('../include/conexao.php');
   

   echo "<div class='container-painel'>";
   echo "<h1 style='font-size: 3vw;'>";	
   echo "Bem-vindo, " .  $_SESSION["nome"] . "</h1>";
   echo $_SESSION["administrador"] ? " (Administrador)" : "";
   echo "<br><br>";

   if ($_SESSION["administrador"]) {
      echo "<div class='button-admin'>";
         echo "<a href='admin.php'>Painel de Administração</a>";
      echo "</div>";
   }

   /**
    * Consulta os produtos disponíveis no banco de dados para listar  produtos para usuários comuns
    */ 
   $sql = $pdo->query("SELECT nome, descricao, valor, imagem FROM produto WHERE imagem IS NOT NULL");
   $produtos = $sql->fetchAll(PDO::FETCH_ASSOC);

   /**
    * Verifica se existem produtos cadastrados 
    */
   if ($produtos) {
      echo "<h2 style='text-align: center; font-size: 2rem; margin-bottom: 10px'>Produtos Disponíveis</h2>";
      echo "<div style='display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;'>";
      foreach ($produtos as $produto) {
         /**
          * Exibe cada produto com nome, descrição, valor e imagem
          */
         echo "<div class='card-products'>";
            echo "<h3 style='font-size: 2rem; font-weight: 800; margin-bottom: 5px;'>{$produto['nome']}</h3>";
            echo "<p style='font-size: 1.3rem;'>Descrição: {$produto['descricao']}</p>";
            echo "<p style='font-size: 1.3rem; margin-bottom: 10px'>Valor: R$ " . number_format($produto['valor'], 2, ',', '.') . "</p>";
            /**
             * Se o produto tiver imagem, exibe a imagem 
             */
            if (!empty($produto['imagem'])) {
               echo "<img src='data:image/jpeg;base64," . base64_encode($produto['imagem']) . "' width='100%'/>";
            }
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

<div class='sair'>
   <p>
      <a href="../include/logout.php">Sair</a>
   </p>
</div>

<?php include('../components/footer.php'); ?>
