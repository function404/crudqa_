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
 * Função para notificar e redirecionar com mensagem e, opcionalmente, parâmetros para repovoar o formulário.
 */
function notify($type, $message, $redirectUrl, $params = []) {
   $params[$type.'_'] = 1;
   $params['message'] = $message;
   header("Location: " . $redirectUrl . ".php?" . http_build_query($params));
   exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email    = test_input($_POST["email"]);
   $password = $_POST["password"]; // não aplica sanitização para a senha
   $formValues = [ "email" => $email ];

   // Validação dos campos obrigatórios
   if (empty($email) || empty($password)) {
      notify("error", "Preencha todos os campos!", "login", $formValues);
   }
   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      notify("error", "Formato de email inválido!", "login", $formValues);
   }

   // Busca o usuário pelo email
   $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
   $stmt->execute(["email" => $email]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);

   if (!$user || !password_verify($password, $user["senha"])) {
      notify("error", "Email ou senha incorretos!", "login", $formValues);
   }

   // Login realizado com sucesso, inicializa as variáveis de sessão
   $_SESSION["id"] = $user["id"];
   $_SESSION["nome"] = $user["nome"];
   $_SESSION["email"] = $user["email"];
   $_SESSION["administrador"] = $user["administrador"];

   notify("success", "Login realizado com sucesso!", "painel", []);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
</head>
<body>
   <h1>Login</h1>
   <?php
      if (isset($_GET["error_"])) {
         echo "<p class='message' style='color: red;'>" . htmlspecialchars($_GET["message"]) . "</p>";
      }
      if (isset($_GET["success_"])) {
         echo "<p class='message' style='color: green;'>" . htmlspecialchars($_GET["message"]) . "</p>";
      }
      $email_val = isset($_GET["email"]) ? htmlspecialchars($_GET["email"]) : "";
   ?>
   <form action="login.php" method="POST">
      <label for="email">*Email:</label>
      <input type="email" name="email" id="email" value="<?php echo $email_val; ?>" required>
      <br><br>
      
      <label for="password">*Senha:</label>
      <input type="password" name="password" id="password" required>
      <br><br>

      <button type="submit">Entrar</button>
   </form>

   <p>
      Não tem uma conta? <a href="./register.php">Cadastre-se</a>
   </p>
<?php include('../components/footer.php'); ?>