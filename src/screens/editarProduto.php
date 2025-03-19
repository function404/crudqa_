<?php
    include('../include/protect.php');
    include('../include/conexao.php');

    /**
     * Verifica se o usuário é administrador
     * Se não for, redireciona para o painel do usuário
     */ 
    if (!$_SESSION["administrador"]) {
        header("Location: painel.php");
        exit();
    }

    /**
     * Função para notificar e redirecionar
     */
    function notify($type, $message, $redirectUrl, $params = []) {
        $params[$type.'_'] = 1;
        $params['message'] = $message;
        header("Location: " . $redirectUrl . ".php?" . http_build_query($params));
        exit();
    }

    /**
     * Recupera o idProduto do produto e verifica se ele foi passado corretamente
     */
    $idProduto = isset($_GET['idProduto']) ? $_GET['idProduto'] : null;
    if (!$idProduto) {
        notify('error', 'ID do produto não especificado.', 'admin');
    }
    
    $sql = $pdo->prepare("SELECT * FROM produto WHERE idProduto = ?");
    $sql->execute([$idProduto]);
    /**
     * Armazena os dados do usuário na variável $produto
     */
    $produto = $sql->fetch(PDO::FETCH_ASSOC);

    /**
     * Verifica se o formulário foi submetido
     */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nomeProduto = $_POST['nomeProduto'];
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $quantidade = $_POST['quantidade'];

        /**
         * Validação: A quantidade deve ser um número inteiro e ter no máximo 5 dígitos
         */ 
        if (!preg_match('/^\d+$/', $quantidade)) {
            notify('error', 'A quantidade deve ser um número inteiro.', 'editarProduto', ['idProduto' => $idProduto]);
        }
        
        if (strlen($quantidade) > 5) {
            notify('error', 'A quantidade deve ter no máximo 5 dígitos.', 'editarProduto', ['idProduto' => $idProduto]);
        }

        /**
         * Verifica se uma nova imagem foi enviada
         */ 
        if (!empty($_FILES['imagem']['tmp_name'])) {
            $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
            $sql = $pdo->prepare("UPDATE produto SET nomeProduto=?, descricao=?, valor=?, quantidade=?, imagem=? WHERE idProduto=?");
            $sql->execute([$nomeProduto, $descricao, $valor, $quantidade, $imagem, $idProduto]);
        } else {
            /**
             * Mantém a imagem existente caso nenhuma nova seja enviada
             */ 
            $sql = $pdo->prepare("UPDATE produto SET nomeProduto=?, descricao=?, valor=?, quantidade=? WHERE idProduto=?");
            $sql->execute([$nomeProduto, $descricao, $valor, $quantidade, $idProduto]);
        }

        /**
         * Após a atualização, redireciona para a página de administração
         */
        header("Location: admin.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto | StockMaster</title>
</head>
<body>
    <h1>Editar Produto</h1>
    <form method="post" enctype="multipart/form-data">
        <label>*Nome:</label>
        <input type="text" name="nomeProduto" value="<?= $produto['nomeProduto'] ?>" required><br><br>

        <label>*Descrição:</label>
        <textarea name="descricao" required><?= $produto['descricao'] ?></textarea><br><br>

        <label>*Valor:</label>
        <input type="number" name="valor" step="0.01" value="<?= $produto['valor'] ?>" required><br><br>

        <label>*Quantidade:</label>
        <input type="number" name="quantidade" step="1"  min="1" max="99999" value="<?= $produto['quantidade'] ?>" required><br><br>

        <label>Imagem Atual:</label><br>
        <?php if (!empty($produto['imagem'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" width="100"><br><br>
        <?php else: ?>
            <p>Sem imagem cadastrada</p>
        <?php endif; ?>

        <label>Nova Imagem:</label>
        <input type="file" name="imagem" accept="image/*"><br><br>

        <?php
            if (isset($_GET['error_'])) {
                echo "<p style='color:red;'>" . htmlspecialchars($_GET['message']) . "</p>";
            }
        ?>

        <button type="submit">Salvar Alterações</button>
    </form>
    <p><a href="admin.php">Voltar</a></p>
<?php include('../components/footer.php'); ?>
