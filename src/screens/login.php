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
   <link rel="stylesheet" href="../css/style.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;800&display=swap" rel="stylesheet">
   <link rel="shortcut icon" type="image/x-icon" sizes="32x32" href="./public/boxIcon-white.png" id="favicon">
    <script>
        function updateFavicon() {
            const favicon = document.getElementById("favicon");
            const darkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
            favicon.href = darkMode ? "boxIcon-black.png" : "boxIcon-white.png";
        }
        updateFavicon();
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", updateFavicon);
    </script>
   <title>Login</title>
</head>
<?php
$email_val = isset($_GET["email"]) ? htmlspecialchars($_GET["email"]) : "";
?>

<body>
   <main class="main-form"> 
      <section class="container-form">
         <section class="left-form">
            <div class="first-midfont-login">
               <p>Que bom ver você de volta!</p>
            </div>

            <div class="welcolme-login">
               <p>bem-vindo</p>
            </div>
               
            <div class="separator"></div>

            <div class="last-midfont-login">
               <p>Sistema de gerenciamento de estoque</p>
            </div>
         </section>

         <section class="right-form">
            <div class="title-login">
               <p>Faça o seu login</p>
            </div>

            <div class="form-login">
               <form action="login.php" method="POST">
                  <label for="email">Email:</label>
                  <input type="email" placeholder="Digite o seu email" name="email" id="email" value="<?php echo $email_val; ?>" required>
                  
                  <label for="password">Senha:</label>
                  <input type="password" placeholder="Digite a sua senha" name="password" id="password" required>
      
                  <button type="submit">Entrar</button>
               </form>
            </div>
   
            <?php
            if (isset($_GET["error_"])) {
               echo "<p class='message' style='color: red; margin-top:15px; font-size: 1vw;'>" . htmlspecialchars($_GET["message"]) . "</p>";
            }
            if (isset($_GET["success_"])) {
               echo "<p class='message' style='color: green; margin-top:15px; font-size: 1vw;'>" . htmlspecialchars($_GET["message"]) . "</p>";
            }
            ?>

            <div class="register-login">
               <p>
                  <a href="./register.php">Não possui uma conta?</a>
               </p>
            </div>

         </section>
      </section>
   </main>
</body>
</html>