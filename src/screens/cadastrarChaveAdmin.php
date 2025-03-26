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
        $key = $_POST['key_value'];
    
        /**
         * Validação simples: a chave deve conter exatamente 6 dígitos numéricos
         */ 
        if (!ctype_digit($key) || strlen($key) != 6) {
            notify('error', 'A chave deve conter exatamente 6 dígitos numéricos.', 'cadastrarChaveAdmin');
        }
    
        try {
            /**
             * Insere a chave no banco de dados
             */ 
            $sql = $pdo->prepare("INSERT INTO `keys` (key_value) VALUES (?)");
            $sql->execute([$key]);
    
            notify('success', 'Chave cadastrada com sucesso!', 'admin');
        } catch (PDOException $e) {
            /**
             * Código 1062 indica duplicidade (chave já existente)
             */ 
            if ($e->errorInfo[1] == 1062) {
                notify('error', 'Essa chave já existe. Por favor, insira uma nova chave.', 'cadastrarChaveAdmin');
            } else {
                notify('error', 'Erro ao cadastrar chave. Tente novamente.', 'cadastrarChaveAdmin');
            }
        }

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
    <title>Cadastrar Chave Admin | StockMaster</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="../public/boxIcon.png">
</head>
<body>
    <div class="container-cadProd">
        <main class="main-form"> 
            <section class="container-form">
                <section class="left-form">
                    <div class="welcolme-prod">
                        <p>Cadastrar Chave</p>
                    </div>
                    <div class="separator"></div>
                    <div class="last-midfont-login">
                        <p>Cadastre uma chave no sistema</p>
                    </div>
                </section> 
                <section class="right-form">
                    <div class="form-login">
                        <form method="POST">
                            <label>*Chave de Administrador:</label>
                            <input type="text" name="key_value" required><br>

                            <?php
                                if (isset($_GET['error_'])) {
                                    echo "<div style='margin: 20px 0;'>";
                                    echo "<span class='errors'>" . htmlspecialchars($_GET['message']) . "</span>";
                                    echo "</div>";
                                }
                            ?>

                            <button type="submit" style="margin-top: 10px">Cadastrar</button>
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