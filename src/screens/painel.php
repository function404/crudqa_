<?php
   include('../include/protect.php');
   include('../include/conexao.php');
         
   $nomeUsuario = $_SESSION["nomeUsuario"];
   $administrador = $_SESSION["administrador"];

   /**
    * Consulta os produtos disponíveis no banco de dados para listar  produtos para usuários comuns
    */ 
   $sql = $pdo->query("SELECT nomeProduto, descricao, valor, quantidade, imagem FROM produto WHERE imagem IS NOT NULL");
   $produtos = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../css/style.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <title>Início | StockMaster</title>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
   <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="../public/boxIcon.png">
</head>
<body>
   <?php include('../components/header.php'); ?>

   <div class="container">
      <div class="top-painel">
         <h1 style="font-size: 2vw;">Bem-vindo, <?= $nomeUsuario ?> <?= $administrador ? "<p style='font-size: 1vw;'>(Administrador)</p>" : "" ?></h1>
      
         <div class="logout">
            <a class="logout-content" href="../include/logout.php">
               <p>Sair</p>
               <i class="fa-solid fa-right-from-bracket"></i>
            </a>
         </div>
      </div>

      <?php if ($administrador): ?>
         <div class="links">
            <a href="admin.php" class="links-content">
               <p>Ir para o Painel de Administração</p>
               <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
      <?php endif; ?>
            
      <!-- Verifica se existem produtos cadastrados -->
      <?php if ($produtos): ?>
         <h2 style='text-align: center; font-size: 1.7vw; margin-bottom: 10px'>Produtos Disponíveis</h2>
         <div style='display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; overflow: hidden;'>
            <?php foreach ($produtos as $produto): ?>
               <!-- * Exibe cada produto com nome, descrição, valor e imagem -->
               <div class='card-products'>
                  <!-- Se o produto tiver imagem, exibe a imagem -->
                  <?php if (!empty($produto['imagem'])): ?>
                     <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" width="100%" />
                  <?php endif; ?>
                  <p style="font-size: 1.5rem; font-weight: 800; margin: 10px 0px 15px 0px; text-transform: uppercase;"><?= htmlspecialchars($produto['nomeProduto']) ?></p>
                  <p style="font-size: 1.4rem; font-weight: 800; margin: 0px 0px 16px 0px">R$ <?= number_format($produto['valor'], 2, ',', '.') ?></p>
                  <p class="card-description"><?= htmlspecialchars($produto['descricao']) ?></p>
                  <p style="font-size: 1rem; margin-top: 15px; margin-bottom: 15px; font-weight: 800;"><?= $produto['quantidade'] ?> Unidades</p>
              </div>
            <?php endforeach; ?>
         </div>
      <?php else: ?>
         <p style="text-align: center;">Nenhum produto disponível.</p>
      <?php endif; ?>   
   </div>  
<?php include('../components/footer.php'); ?>
