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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Administração | StockMaster</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="../public/boxIcon.png">
</head>
<body>
    <?php include('../components/header.php'); ?>

    <div class="container">
        <h1 style="font-size: 2vw; margin-bottom: 1rem;">Painel do Administrador</h1>
        
        <h2>Gerenciar Usuários</h2>
        <?php if ($usuarios): ?>
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
                            <a href="editarUsuario.php?idUsuario=<?= $usuario['idUsuario'] ?>"><i class="fa-solid fa-pen fa-lg" style="color:rgb(56, 182, 255);"></i></a> |
                            <a href="excluirUsuario.php?idUsuario=<?= $usuario['idUsuario'] ?>" onclick="return confirm('Tem certeza?')"><i class="fa-solid fa-trash fa-lg" style="color: #ff3838;"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div style="margin: 20px 0;">
                <span class="errors" style="text-align: left;">Nenhum produto disponível.</span>
            </div>
        <?php endif; ?>

        <?php
            if (isset($_GET['error_'])) {
                echo "<div style='margin: 20px 0;'>";
                echo "<span class='errors'>" . htmlspecialchars($_GET['message']) . "</span>";
                echo "</div>";
            }
        ?>

        <h2>Gerenciar Produtos</h2>
        <?php if ($produtos): ?>
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
                            <?php else: ?>
                            <div style="margin: 5px 0;">
                                <span class="errors">Sem Imagem</span>
                            </div>
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
            <?php else: ?>
            <div style="margin: 20px 0;">
                <span class="errors" style="text-align: left;">Nenhum produto disponível.</span>
            </div>
        <?php endif ?>

        <div class="links-table-prod">
            <p><a href="cadastrarProduto.php">Adicionar Novo Produto</a></p>
        </div>
        
        <h2>Gerenciar Chaves</h2>
        <?php if ($keys): ?>
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
            <?php else: ?>
            <div style="margin: 20px 0;">
                <span class="errors" style="text-align: left;">Nenhuma chave disponível.</span>
            </div>
        <?php endif; ?>

        <div class="links-table-key">
            <p><a href="cadastrarChaveAdmin.php">Adicionar nova chave</a></p>
        </div>

        <div class="voltar">
            <p><a href="painel.php">Voltar</a></p>
        </div>
    </div>
<?php include('../components/footer.php'); ?>
