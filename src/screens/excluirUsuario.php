<?php
    include('../include/protect.php');
    include('../include/conexao.php');

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
     * Verifica se o usuário é administrador.
     * Se não for, redireciona para o painel do usuário.
     */
    if (!$_SESSION["administrador"]) {
        header("Location: painel.php");
        exit();
    }

    /**
     * Recupera o idUsuario do produto e verifica se ele foi passado corretamente
     */
    $idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : null;
    if (!$idUsuario) {
        notify('error', 'ID do produto não especificado.', 'admin');
    }

    /**
     * Verifica se o administrador está tentando deletar sua própria conta.
     */
    if ($idUsuario == $_SESSION["idUsuario"]) {
        notify('error', 'Você não pode deletar sua própria conta.', 'admin');
    }

    /**
     * Prepara e executa a consulta para excluir o usuário do banco de dados.
     */
    $sql = $pdo->prepare("DELETE FROM usuario WHERE idUsuario = ?");
    $sql->execute([$idUsuario]);

    /**
     * Após a exclusão, redireciona para a página de administração.
     */
    header("Location: admin.php");
    exit();
?>
