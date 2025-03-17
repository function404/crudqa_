<?php
session_start();
include('../include/conexao.php');
include('../include/functions.php');


/**
 * Verifica se a sessão já foi iniciada; se não, inicia uma nova sessão.
 */
if (isset($_SESSION["id"])) {
   header("Location: painel.php");
   exit();
}

/**
 * Redireciona para a página indicada, passando o tipo de mensagem, a mensagem propriamente dita 
 * e os dados do formulário para repovoamento.
 */
function notify($type, $message, $redirectUrl, $params = []) {
   $params[$type.'_'] = 1;
   $params['message'] = $message;

   /**
    * Se o tipo for 'success', exibe a mensagem e redireciona após 3 segundos.
    * Caso contrário, redireciona imediatamente.
   */
   if ($type === 'success') {
      /**
       * Exibe uma página com a mensagem e redireciona após 3 segundos
       */
      echo "
      <!DOCTYPE html>
      <html lang='pt-br'>
      <head>
         <meta charset='UTF-8'>
         <title>Sucesso</title>
         <meta http-equiv='refresh' content='3;url=" . $redirectUrl . ".php'>
         <style>
            body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
            .message { color: green; font-size: 1.2em; }
         </style>
      </head>
      <body>
         <p class='message'>" . htmlspecialchars($message) . "</p>
         <p>Você será redirecionado em 3 segundos. Se não for, <a href='" . $redirectUrl . ".php'>clique aqui</a>.</p>
      </body>
      </html>";
      exit();
   } else {
      header("Location: " . $redirectUrl . ".php?" . http_build_query($params));
      exit();
   }
}

/**
 * Verifica se o formulário foi submetido.
 * Se sim, inicia o processo de cadastro.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   /**
    * Recebe e sanitiza os dados do formulário
    * @var string $nome     Nome do usuário
      * @var string $email    Email do usuário
      * @var string $telefone Telefone do usuário
      * @var string $password Senha do usuário
      * @var int    $administrador    Indica se o usuário será administrador (1) ou não (0)
      * @var string $key      Chave de administrador (opcional)
   */
   $nome     = test_input($_POST['nome']);
   $email    = test_input($_POST['email']);
   $telefone = test_input($_POST['telefone']);
   $password = $_POST['password'];
   $administrador    = isset($_POST['administrador']) ? 1 : 0;
   $key      = test_input($_POST['key']);
   
   /**
    * Vetor com os dados que serão repassados para repovoar os campos (exceto a senha)
   */
   $formValues = [
      'nome'     => $nome,
      'email'    => $email,
      'telefone' => $telefone,
      'administrador'    => $administrador
   ];
   
   /**
    * Validação dos campos obrigatórios.
    * Se algum campo não for preenchido, exibe mensagem de erro e redireciona para a página de cadastro
    */ 
   if (empty($nome) || empty($email) || empty($password)) {
      notify('error', 'Preencha todos os campos obrigatórios!', 'register', $formValues);
   }
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      notify('error', 'Formato de email inválido!', 'register', $formValues);
   }
   if (strlen($password) < 6) {
      notify('error', 'A senha deve ter pelo menos 6 caracteres!', 'register', $formValues);
   }
   
   /*
    * Verifica se o email já está cadastrado
    */ 
   $stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = :email");
   $stmt->execute(['email' => $email]);
   if ($stmt->rowCount() > 0) {
      notify('error', 'Email já cadastrado!', 'register', $formValues);
   }

   /**
    * Se o telefone foi informado, verifica se já existe
   */
   if (!empty($telefone)) {
      $stmt = $pdo->prepare("SELECT id FROM usuario WHERE telefone = :telefone");
      $stmt->execute(['telefone' => $telefone]);
      if ($stmt->rowCount() > 0) {
         notify('error', 'Telefone já cadastrado!', 'register', $formValues);
      }
   }
   
   /**
    * Se o usuário optar por ser administrador, valida a chave de administrador.
    * Se a chave for inválida, exibe mensagem de erro e redireciona para a página de cadastro.
    */
   if ($administrador == 1) {
      if (empty($key)) {
         notify('error', 'Informe a chave de administrador!', 'register', $formValues);
      }
      if (strlen($key) < 6) {
         notify('error', 'A chave deve ter pelo menos 6 caracteres!', 'register', $formValues);
      }
      /**
       * Verifica se a chave existe na tabela "keys"
       * Se não existir, exibe mensagem de erro e redireciona para a página de cadastro.
       */ 
      $stmt = $pdo->prepare("SELECT * FROM `keys` WHERE `key_value` = :key_value");
      $stmt->execute(['key_value' => $key]);
      if ($stmt->rowCount() == 0) {
         notify('error', 'Chave de administrador inválida!', 'register', $formValues);
      }
   }
   
   /**
    * Tudo validado: gera hash da senha.
    */
   $senhaHash = password_hash($password, PASSWORD_DEFAULT);
      
   /**
    * Insere o novo usuário na tabela.
    */ 
   $stmt = $pdo->prepare("INSERT INTO usuario (id, nome, email, telefone, senha, administrador) VALUES (:id, :nome, :email, :telefone, :senha, :administrador)");
   $stmt->execute([
      'id' => null,
      'nome' => $nome,
      'email' => $email,
      'telefone' => $telefone,
      'senha' => $senhaHash,
      'administrador' => $administrador
   ]);
   
   /**
    * Após o cadastro, redireciona para a página de login (ou painel).
   */
   notify('success', 'Usuário cadastrado com sucesso!', '../../index', []);
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
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
   <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="./public/boxIcon-white.png" id="favicon">
   <title>Cadastro</title>
</head>
<?php 
   $nome_val = isset($_GET['nome']) ? htmlspecialchars($_GET['nome']) : '';
   $email_val = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
   $telefone_val = isset($_GET['telefone']) ? htmlspecialchars($_GET['telefone']) : '';
   $admin_checked = (isset($_GET['administrador']) && $_GET['administrador'] == 1) ? 'checked' : '';
?>
<body>
   <main class="main-form"> 
      <section class="container-form">
         <section class="left-form">
            <div class="first-midfont-login">
               <p>Seja muito</p>
            </div>

            <div class="welcolme-login">
               <p>Bem-vindo</p>
            </div>

            <div class="separator"></div>

            <div class="last-midfont-login">
               <p>Sistema de gerenciamento de estoque</p>
            </div>
         </section>

         <section class="right-form">
            <div class="title-login">
               <p>Cadastro de Usuário</p>
            </div>

            <div class="form-login">
               <form action="register.php" method="POST">
                  <label for="nome">Nome:</label>
                  <input type="text" name="nome" id="nome" placeholder="Digite o seu nome" value="<?php echo $nome_val; ?>" required>

                  <label for="email">Email:</label>
                  <input type="email" name="email" id="email" placeholder="Digite o seu email" value="<?php echo $email_val; ?>" required>

                  <label for="telefone">Telefone:</label>
                  <input type="text" name="telefone" id="telefone" placeholder="Digite o seu telefone" value="<?php echo $telefone_val; ?>">

                  <label for="password">Senha:</label>
                  <input type="password" name="password" id="password" placeholder="Digite a sua senha" required>

                  <div class="keyadmin">
                     <input type="checkbox" name="administrador" id="administrador" <?php echo $admin_checked; ?>>
                     <label>Sou um administrador</label>
                  </div>
                  <div id="adminKeyDiv" style="display: none;">
                     <label for="key">Chave de Administrador:</label>
                     <input type="text" name="key" id="key" placeholder="Digite a chave de administrador">
                  </div>
                  
                  <?php
                     if (isset($_GET['error_'])) {
                        echo "<p style='color: red; margin-top:15px; font-size: 1vw;'>" . htmlspecialchars($_GET['message']) . "</p>";
                     }
                     if (isset($_GET['success_'])) {
                        echo "<p style='color: red; margin-top:15px; font-size: 1vw;'>" . htmlspecialchars($_GET['message']) . "</p>";
                     }
                  ?>

                  <button type="submit">Cadastrar</button>
               </form>
            </div>

            <div class="register-login">
               <p>
                  <a href="./login.php">Já tem uma conta? Entrar</a>
               </p>
            </div>
         </section>
      </section>
   </main>
   <script>
      document.getElementById('administrador').addEventListener('change', function() {
         var adminKeyDiv = document.getElementById('adminKeyDiv');
         adminKeyDiv.style.display = this.checked ? 'block' : 'none';
      });
   </script>
<body>
</html>