<?php
include('../include/conexao.php');
include('../include/functions.php');

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
    * @var string $name     Nome do usuário
      * @var string $email    Email do usuário
      * @var string $telefone Telefone do usuário
      * @var string $password Senha do usuário
      * @var int    $admin    Indica se o usuário será administrador (1) ou não (0)
      * @var string $key      Chave de administrador (opcional)
   */
   $name     = test_input($_POST['name']);
   $email    = test_input($_POST['email']);
   $telefone = test_input($_POST['telefone']);
   $password = $_POST['password'];
   $admin    = isset($_POST['admin']) ? 1 : 0;
   $key      = test_input($_POST['key']);
   
   /**
    * Vetor com os dados que serão repassados para repovoar os campos (exceto a senha)
   */
   $formValues = [
      'name'     => $name,
      'email'    => $email,
      'telefone' => $telefone,
      'admin'    => $admin
   ];
   
   /**
    * Validação dos campos obrigatórios.
    * Se algum campo não for preenchido, exibe mensagem de erro e redireciona para a página de cadastro
    */ 
   if (empty($name) || empty($email) || empty($password)) {
      notify('error', 'Preencha todos os campos obrigatórios!', 'register', $formValues);
   }
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      notify('error', 'Formato de email inválido!', 'register', $formValues);
   }
   if (strlen($password) < 6) {
      notify('error', 'A senha deve ter pelo menos 6 caracteres!', 'register', $formValues);
   }
   
   /*
    * Verifica se o email já está cadastrado.
    * Se estiver, exibe mensagem de erro e redireciona para a página de cadastro
    * Caso contrário, prossegue com o cadastro.
    */ 
   $stmt = $pdo->prepare("SELECT codigo FROM usuario WHERE email = :email");
   $stmt->execute(['email' => $email]);
   if ($stmt->rowCount() > 0) {
      notify('error', 'Email já cadastrado!', 'register', $formValues);
   }
   
   /**
    * Se o usuário optar por ser administrador, valida a chave de administrador.
    * Se a chave for inválida, exibe mensagem de erro e redireciona para a página de cadastro.
    */
   if ($admin == 1) {
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
      $stmt = $pdo->prepare("SELECT * FROM `keys` WHERE `key` = :key");
      $stmt->execute(['key' => $key]);
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
   $stmt = $pdo->prepare("INSERT INTO usuario (codigo, nome, email, telefone, senha, administrador) VALUES (:codigo, :nome, :email, :telefone, :senha, :admin)");
   $stmt->execute([
      'codigo' => null,
      'nome' => $name,
      'email' => $email,
      'telefone' => $telefone,
      'senha' => $senhaHash,
      'admin'    => $admin
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
   <title>Cadastro</title>
</head>
<body>
   <h2>Cadastro de Usuário</h2>
   <?php
      /**
       * Exibe mensagem de erro ou sucesso, se houver 
      */ 
      if (isset($_GET['error_'])) {
         echo "<p class='message' style='color: red;'>" . htmlspecialchars($_GET['message']) . "</p>";
      }
      if (isset($_GET['success_'])) {
         echo "<p class='message' style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
      }
      
      /**
       * Recupera os valores previamente informados, se existirem 
      */ 
      $name_val     = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
      $email_val    = isset($_GET['email']) ? htmlspecialchars($_GET['email']) : '';
      $telefone_val = isset($_GET['telefone']) ? htmlspecialchars($_GET['telefone']) : '';
      $admin_checked = (isset($_GET['admin']) && $_GET['admin'] == 1) ? 'checked' : '';
   ?>
   <form action="register.php" method="POST">
      <label for="name">*Nome:</label>
      <input type="text" name="name" id="name" value="<?php echo $name_val; ?>" required>
      <br><br>

      <label for="email">*Email:</label>
      <input type="email" name="email" id="email" value="<?php echo $email_val; ?>" required>
      <br><br>

      <label for="telefone">Telefone:</label>
      <input type="text" name="telefone" id="telefone" value="<?php echo $telefone_val; ?>">
      <br><br>

      <label for="password">*Senha:</label>
      <input type="password" name="password" id="password" required>
      <br><br>

      <label>
         <input type="checkbox" name="admin" id="admin" <?php echo $admin_checked; ?>> Sou administrador
      </label>
      <br><br>

      <!-- Campo para chave de administrador; exibido se o checkbox estiver marcado -->
      <div id="adminKeyDiv" style="display: <?php echo ($admin_checked ? 'block' : 'none'); ?>;">
         <label for="key">Chave de Administrador:</label>
         <input type="text" name="key" id="key">
      </div>
      <br><br>

      <button type="submit">Cadastrar</button>
   </form>
   <script>
      /**
      * JavaScript para mostrar/ocultar o campo de chave conforme o checkbox de administrador
      */ 
      document.getElementById('admin').addEventListener('change', function() {
         var adminKeyDiv = document.getElementById('adminKeyDiv');
         adminKeyDiv.style.display = this.checked ? 'block' : 'none';
      });
   </script>
</body>
</html>
