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
     * Recupera o idProduto do produto e verifica se ele foi passado corretamente
     */
    $idProduto = isset($_GET['idProduto']) ? $_GET['idProduto'] : null;
    if (!$idProduto) {
        notify('error', 'ID do produto não especificado.', 'admin');
    }

    /**
     * Prepara e executa a consulta para excluir o produto do banco de dados
     */ 
    $sql = $pdo->prepare("DELETE FROM produto WHERE idProduto = ?");
    $sql->execute([$idProduto]);

    /**
     * Após a exclusão, redireciona para a página de administração
     */ 
    header("Location: admin.php");
    exit();
?>
