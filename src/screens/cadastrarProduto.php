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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nomeProduto = $_POST['nomeProduto'];
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $quantidade = $_POST['quantidade'];
        $imagem = null;

        /**
         * Validação da quantidade deve ser um número inteiro com no máximo 5 dígitos
         */ 
        if (!preg_match('/^\d+$/', $quantidade)) {
            notify('error', 'A quantidade deve ser um número inteiro.', 'cadastrarProduto');
        }

        if (strlen($quantidade) > 5) {
            notify('error', 'A quantidade deve ter no máximo 5 dígitos.', 'cadastrarProduto');
        }

        /**
         * Verifica se foi enviado um arquivo
         */ 
        if (!empty($_FILES['imagem']['tmp_name'])) {
            $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        }

        /**
         * Insere no banco de dados
         */ 
        $sql = $pdo->prepare("INSERT INTO produto (nomeProduto, descricao, valor, quantidade, imagem) VALUES (?, ?, ?, ?, ?)");
        $sql->execute([$nomeProduto, $descricao, $valor, $quantidade, $imagem]);

        /**
         * Redireciona após cadastro
         */ 
        header("Location: admin.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
</head>
<body>
    <h1>Cadastrar Produto</h1>
    <?php
        if (isset($_GET['error_'])) {
            echo "<p class='message' style='color: red;'>" . htmlspecialchars($_GET['message']) . "</p>";
        }

        $nomeProduto_val = isset($_GET['nomeProduto']) ? htmlspecialchars($_GET['nomeProduto']) : '';
        $descricao_val = isset($_GET['descricao']) ? htmlspecialchars($_GET['descricao']) : '';
        $valor_val = isset($_GET['valor']) ? htmlspecialchars($_GET['valor']) : '';
        $quantidade_val = isset($_GET['quantidade']) ? htmlspecialchars($_GET['quantidade']) : '';
    ?>
    <form method="POST" enctype="multipart/form-data">
        <label>*Nome:</label>
        <input type="text" name="nomeProduto"value="<?php echo $nomeProduto_val; ?>" required><br><br>

        <label>*Descrição:</label>
        <textarea name="descricao" value="<?php echo $descricao_val; ?>" required></textarea><br><br>

        <label>*Valor:</label>
        <input type="number" name="valor" step="0.01" value="<?php echo $valor_val; ?>" required><br><br>

        <label>*Quantidade:</label>
        <input type="number" name="quantidade" min="1" max="99999" step="1" value="<?php echo $quantidade_val; ?>" required><br><br>

        <label>Imagem:</label>
        <input type="file" name="imagem" accept="image/*"><br><br>

        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="admin.php">Voltar</a></p>
<?php include('../components/footer.php'); ?>
