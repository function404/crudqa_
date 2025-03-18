<?php
   include('../include/protect.php');
   include('../include/conexao.php');

   echo "Bem-vindo, " . $_SESSION["nome"];
   echo $_SESSION["administrador"] ? " (Administrador)" : "";
   echo "<br><br>";

   if ($_SESSION["administrador"]) {
      echo "<p><a href='admin.php'>Painel de Administração</a></p>";
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
      echo "<h2>Produtos Disponíveis</h2>";
      echo "<div style='display: flex; flex-wrap: wrap; gap: 20px;'>";
      foreach ($produtos as $produto) {
         /**
          * Exibe cada produto com nome, descrição, valor e imagem
          */
         echo "<div style='border: 1px solid #000; padding: 10px; width: 250px;'>";
            echo "<h3>{$produto['nome']}</h3>";
            echo "<p><strong>Descrição:</strong> {$produto['descricao']}</p>";
            echo "<p><strong>Valor:</strong> R$ " . number_format($produto['valor'], 2, ',', '.') . "</p>";
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
?>

<p>
    <a href="../include/logout.php">Sair</a>
</p>

<?php include('../components/footer.php'); ?>
