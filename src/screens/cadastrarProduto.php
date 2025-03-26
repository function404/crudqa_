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
            /**
             * Define o tamanha da imagem em 2 MB
             */
            $maxSize = 2097152;
            if ($_FILES['imagem']['size'] > $maxSize) {
                notify('error', 'A imagem deve ter no máximo 2 MB.', 'cadastrarProduto');
            }
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Cadastrar Produto | StockMaster</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="../public/boxIcon.png">
</head>
<?php
    $nomeProduto_val = isset($_GET['nomeProduto']) ? htmlspecialchars($_GET['nomeProduto']) : '';
    $descricao_val = isset($_GET['descricao']) ? htmlspecialchars($_GET['descricao']) : '';
    $valor_val = isset($_GET['valor']) ? htmlspecialchars($_GET['valor']) : '';
    $quantidade_val = isset($_GET['quantidade']) ? htmlspecialchars($_GET['quantidade']) : '';
?>
<body>
    <div class="container-cadProd">
        <main class="main-form"> 
            <section class="container-form">
                <section class="left-form">
                    <div class="welcolme-prod">
                        <p>Cadastrar Produto</p>
                    </div>
                    <div class="separator"></div>
                    <div class="last-midfont-login">
                        <p>Cadastre um produto no estoque</p>
                    </div>
                </section>
                <section class="right-form">
                    <div class="form-login">
                        <form method="POST" enctype="multipart/form-data">
                            <label>*Nome:</label>
                            <input type="text" placeholder="Digite o nome" name="nomeProduto"value="<?php echo $nomeProduto_val; ?>" required><br><br>

                            <label>*Descrição:</label>
                            <textarea name="descricao" placeholder="Digite a descrição" value="<?php echo $descricao_val; ?>" required></textarea><br><br>

                            <label>*Valor:</label>
                            <input type="number" name="valor" placeholder="Digite o valor" step="0.01" value="<?php echo $valor_val; ?>" required><br><br>

                            <label>*Quantidade:</label>
                            <input type="number" name="quantidade" placeholder="Digite a quantidade" min="1" max="99999" step="1" value="<?php echo $quantidade_val; ?>" required><br><br>

                            <label>Imagem:</label>
                            <input type="file" name="imagem" accept="image/*"><br><br>

                            <?php
                                if (isset($_GET['error_'])) {
                                    echo "<div style='margin-bottom: 20px;'>";
                                    echo "<span class='errors'>" . htmlspecialchars($_GET['message']) . "</span>";
                                    echo "</div>";
                                }
                            ?>

                            <button type="submit">Cadastrar</button>

                            <div class="voltar">
                                <p><a href="admin.php">Voltar</a></p>
                            </div>
                        </form>
                    </div>
                </section>
            </section>
        </main>
    </div>
<?php include('../components/footer.php'); ?>