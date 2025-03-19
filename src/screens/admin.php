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
     * Consulta todos os usuários cadastrados no banco de dados 
     */
    $sql_usuarios = $pdo->query("SELECT * FROM usuario");
    $usuarios = $sql_usuarios->fetchAll(PDO::FETCH_ASSOC);

    /**
     * Consulta todos os produtos cadastrados no banco de dados 
     */
    $sql_produtos = $pdo->query("SELECT * FROM produto");
    $produtos = $sql_produtos->fetchAll(PDO::FETCH_ASSOC);

    /**
     * Consulta todos as chaves cadastradas no banco de dados 
     */
    $sql_keys = $pdo->query("SELECT * FROM `keys`");
    $keys = $sql_keys->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração | StockMaster</title>
</head>
<body>
    <?php include('../components/header.php'); ?>

    <div class="container">
    <h1 style="font-size: 2vw; margin-bottom: 1rem;">Painel do Administrador</h1>
    
    <h2>Gerenciar Usuários</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Administrador</th>
            <th>Criado</th>
            <th>Atualizado</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['idUsuario'] ?></td>
                <td><?= $usuario['nomeUsuario'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td><?= $usuario['telefone'] ?></td>
                <td><?= $usuario['administrador']?></td>
                <td><?= $usuario['criado_as']?></td>
                <td><?= $usuario['atualizado_as']?></td>
                <td>
                    <a href="editarUsuario.php?idUsuario=<?= $usuario['idUsuario'] ?>">Editar</a> |
                    <a href="excluirUsuario.php?idUsuario=<?= $usuario['idUsuario'] ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
        if (isset($_GET['error_'])) {
            echo "<p style='color:red;'>" . htmlspecialchars($_GET['message']) . "</p>";
        }
    ?>

    <h2>Gerenciar Produtos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Quantidade</th>
            <th>Imagem</th>
            <th>Criado</th>
            <th>Atualizado</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= $produto['idProduto'] ?></td>
                <td><?= $produto['nomeProduto'] ?></td>
                <td><?= $produto['descricao'] ?></td>
                <td>R$ <?= number_format($produto['valor'], 2, ',', '.') ?></td>
                <td><?= $produto['quantidade'] ?></td>
                <td>
                    <?php if ($produto['imagem']): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($produto['imagem']) ?>" width="50">
                    <?php endif; ?>
                </td>
                <td><?= $produto['criado_as']?></td>
                <td><?= $produto['atualizado_as']?></td>
                <td>
                    <a href="editarProduto.php?idProduto=<?= $produto['idProduto'] ?>"><i class="fa-solid fa-pen fa-lg" style="color:rgb(56, 182, 255);"></i></a> |
                    <a href="excluirProduto.php?idProduto=<?= $produto['idProduto'] ?>" onclick="return confirm('Tem certeza?')"><i class="fa-solid fa-trash fa-lg" style="color: #ff3838;"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="cadastrarProduto.php">Adicionar Novo Produto</a></p>
    
    <h2>Gerenciar Chaves</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Chave</th>
        </tr>
        <?php foreach ($keys as $key): ?>
        <tr>
            <td><?= $key['idKey']?></td>
            <td><?= $key['key_value']?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="cadastrarChaveAdmin.php">Adicionar Chave para Administrador</a></p>
    
    <p><a href="painel.php">Voltar</a></p>
    </div>
<?php include('../components/footer.php'); ?>
