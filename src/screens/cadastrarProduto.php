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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $imagem = null;

        /**
         * Verifica se foi enviado um arquivo
         */ 
        if (!empty($_FILES['imagem']['tmp_name'])) {
            $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        }

        /**
         * Insere no banco de dados
         */ 
        $sql = $pdo->prepare("INSERT INTO produto (nome, descricao, valor, imagem) VALUES (?, ?, ?, ?)");
        $sql->execute([$nome, $descricao, $valor, $imagem]);

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
    <form method="POST" enctype="multipart/form-data">
        <label>*Nome:</label>
        <input type="text" name="nome" required><br>

        <label>*Descrição:</label>
        <textarea name="descricao" required></textarea><br>

        <label>*Valor:</label>
        <input type="number" name="valor" step="0.01" required><br>

        <label>Imagem:</label>
        <input type="file" name="imagem" accept="image/*"><br>

        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="admin.php">Voltar</a></p>
<?php include('../components/footer.php'); ?>
