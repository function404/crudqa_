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
     * Recupera o idUsuario do produto e verifica se ele foi passado corretamente
     */
    $idUsuario = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : null;
    if (!$idUsuario) {
        notify('error', 'ID do produto não especificado.', 'admin');
    }

    /**
     * Recupera os dados do usuário no banco
     */ 
    $sql = $pdo->prepare("SELECT * FROM usuario WHERE idUsuario = ?");
    $sql->execute([$idUsuario]);
    /**
     * Armazena os dados do usuário na variável $usuario
     */
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    try {
        /**
         * Verifica se o formulário foi submetido
         */
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nomeUsuario = $_POST['nomeUsuario'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
    
            /**
             * Atualiza os dados do usuário no banco
             */
            $sql = $pdo->prepare("UPDATE usuario SET nomeUsuario=?, email=?, telefone=? WHERE idUsuario=?");
            $sql->execute([$nomeUsuario, $email, $telefone, $idUsuario]);
    
            /**
             * Após a atualização, redireciona para a página de administração
             */
            header("Location: admin.php");
            exit();
        }
    } catch (PDOException $e) {
        /**
         * Verifica se o erro é de duplicidade do email ou telefone único
         */
        if ($e->errorInfo[1] == 1062) {
            /**
             * Extrai a informação do campo que causou a duplicidade
             */
            $duplicateField = '';
            if (strpos($e->getMessage(), 'email') !== false) {
                $duplicateField = 'Email';
            } elseif (strpos($e->getMessage(), 'telefone') !== false) {
                $duplicateField = 'Telefone';
            }
            $errorMessage = "Esse $duplicateField já existe. Por favor, escolha outro.";
        } else {
            /**
             * Mensagem genérica para outros erros
             */
            $errorMessage = "Ocorreu um erro ao atualizar o usuário. Por favor, tente novamente.";
        }
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
    <title>Editar Usuário | StockMaster</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="../public/boxIcon.png">
</head>
<body>
    <div class="container-cadProd">
        <main class="main-form">
            <section class="container-form">
                <section class="left-form">
                    <div class="welcolme-prod">
                        <p>Editar Usuário</p>
                    </div>
                    <div class="separator"></div>
                    <div class="last-midfont-login">
                        <p>Edite o usuário cadastrado</p>
                    </div>
                </section>
                <section class="right-form">
                    <div class="form-login">
                        <form method="POST">
                            <label>*Nome:</label>
                            <input type="text" name="nomeUsuario" value="<?= $usuario['nomeUsuario'] ?>" required><br><br>

                            <label>*Email: </label>
                            <input type="email" name="email" value="<?= $usuario['email'] ?>" required><br><br>

                            <label>Telefone: </label>
                            <input type="text" name="telefone" value="<?= $usuario['telefone'] ?>"><br><br>
                            
                            <?php
                                /**
                                 * Exibe a mensagem de erro, se existir
                                 */
                                if (isset($errorMessage)) {
                                    echo "<div style='margin: 20px 0;'>";
                                    echo "<span class='errors'>$errorMessage</span>";
                                    echo "</div>";
                                }
                            ?>

                            <button type="submit">Salvar</button>

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
