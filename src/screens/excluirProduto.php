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
     * Recupera o ID do produto a ser excluído através do parâmetro da URL
     */ 
    $id = $_GET['id'];

    /**
     * Prepara e executa a consulta para excluir o produto do banco de dados
     */ 
    $sql = $pdo->prepare("DELETE FROM produto WHERE id = ?");
    $sql->execute([$id]);

    /**
     * Após a exclusão, redireciona para a página de administração
     */ 
    header("Location: admin.php");
    exit();
?>
