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
     * Recupera o ID do produto a ser editado através do parâmetro da URL
     */
    $id = $_GET['id'];


    $sql = $pdo->prepare("SELECT * FROM produto WHERE id = ?");
    $sql->execute([$id]);
    /**
     * Armazena os dados do usuário na variável $produto
     */
    $produto = $sql->fetch(PDO::FETCH_ASSOC);

    /**
     * Verifica se o formulário foi submetido
     */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];

        /**
         * Verifica se uma nova imagem foi enviada
         */ 
        if (!empty($_FILES['imagem']['tmp_name'])) {
            $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
            $sql = $pdo->prepare("UPDATE produto SET nome=?, descricao=?, valor=?, imagem=? WHERE id=?");
            $sql->execute([$nome, $descricao, $valor, $imagem, $id]);
        } else {
            /**
             * Mantém a imagem existente caso nenhuma nova seja enviada
             */ 
            $sql = $pdo->prepare("UPDATE produto SET nome=?, descricao=?, valor=? WHERE id=?");
            $sql->execute([$nome, $descricao, $valor, $id]);
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
    <title>Editar Produto</title>
</head>
<body>
    <h1>Editar Produto</h1>
    <form method="post" enctype="multipart/form-data">
        <label>*Nome:</label>
        <input type="text" name="nome" value="<?= $produto['nome'] ?>" required><br><br>

        <label>*Descrição:</label>
        <textarea name="descricao" required><?= $produto['descricao'] ?></textarea><br><br>

        <label>*Valor:</label>
        <input type="number" name="valor" step="0.01" value="<?= $produto['valor'] ?>" required><br><br>

        <label>Imagem Atual:</label><br>
        <?php if (!empty($produto['imagem'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" width="100"><br><br>
        <?php else: ?>
            <p>Sem imagem cadastrada</p>
        <?php endif; ?>

        <label>Nova Imagem:</label>
        <input type="file" name="imagem" accept="image/*"><br><br>

        <button type="submit">Salvar Alterações</button>
    </form>
    <p><a href="admin.php">Voltar</a></p>
<?php include('../components/footer.php'); ?>
