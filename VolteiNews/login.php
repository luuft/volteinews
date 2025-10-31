<?php 
session_start();

require_once __DIR__ . "/bd/Banco.php";

require_once __DIR__ . "/classes/Usuario.php";

if(isset($_POST['botao'])){
    $u = new Usuario('', $_POST['email'], $_POST['senha']);

    if($_POST['botao'] === "Entrar"){
        if($u->authenticate()){
            header("Location: menu.php");
            exit;
        } else {
            $erro = "Email ou senha inválidos.";
        }
    } 
    if($_POST['botao'] === "Cadastrar") {
        header("Location: register.php");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <main>
        <div class='left-content'>
            <header>
            <div class="center-header">
            <img src="images/logo.png" alt="logo" class="logo">

        </div>
        </header>
        <h2 class='msg'>Fique por dentro das <span class='quase'>(quase)</span> novidades!</h2>
        </div>
        <div class='container'>


    <?php if(isset($erro)) echo "<p>$erro</p>"; ?>

    <form method='POST' action=''>
        <h2 class="entre">Entre no Voltei</h2>
        <div class='campo'> 
            <input class='aba' type="text" name='email' id='email' placeholder="Digite seu email">
        </div>
        
        <div class='campo'>
            <input class='aba' type="password" id='senha' name='senha' placeholder='Digite sua senha'>
        </div>
        
        <div class='button1'>
            <input type="submit" name='botao' value='Entrar' class='btnlogar'>
        </div>
        <div class='lines'>
            <div class='line1'></div>Ou<div class='line2'></div>
        </div>
        <div class='button2'>
            <h3>Não possui uma conta? <a class='registrar' href="register.php">Cadastre-se</a></h3>
        </div>
    </form>
    </article>
</div>
    </main>
    <footer>
    <div class="footer-links">
        <a href="#">Português (BR)</a>
    </div>
    <div class='bottomline'></div>
    <div class="footer-links">
        <a href="#">joaovluftp@gmail.com | 51995628660 | IFRS Campus Feliz | 3º T.I | Odim Vassouras | Showbar | EZA Contabilidade | Grêmio FBPA | Meta</a>
    </div>
    <p>&copy; 2025 VolteiNews - (quase) Todos os direitos reservados</p>
</footer>
</body>
</html>