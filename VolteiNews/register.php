<?php 
require_once __DIR__ . "/classes/Usuario.php";

$fotoPath = 'images/defaultpfp.png';

if (isset($_POST['botao'])) {
    if ($_POST['botao'] === "Cadastrar") {

        $u = new Usuario(
            $_POST['nome'],
            $_POST['email'],
            $_POST['senha'],
            $fotoPath
        );

        if ($u->exists()) {
            $erro = "Esse email já está em uso!";
        } else {
            if ($u->save()) {
                $sucesso = "Usuário cadastrado com sucesso!";
                header('Location: login.php');
                exit;
            } else {
                $erro = "Erro ao cadastrar usuário.";
            }
        }
    }

    if ($_POST['botao'] === "Voltar") {
        header('Location: login.php');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles/acess.css">
</head>
<body>
    <main>
        <div class='container'>
        <div class="center-header">
            <img src="images/logo.png" alt="logo" class="logo">
            <h1>News</h1>
        </div>
    <article>


        <?php if(isset($erro)) echo "<p>$erro</p>"; ?>
    <?php if(isset($sucesso)) echo "<p>$sucesso</p>"; ?>
        <h2 class="entre">Crie sua conta</h2>
        <form method='POST' action=''>
        <div class='campo'>
            <input class='aba' type="text" name='nome' id='nome' placeholder="Digite seu nome de usuário">
        </div>

        <div class='campo'>
            <input class='aba' type="text" name='email' id='email' placeholder="Digite seu email">
        </div>
        
        <div class='campo'>
            <input class='aba' type="password" id='senha' name='senha' placeholder="Digite sua senha">
        </div>

        
        <div class='buttons'>
            <input type="submit" name='botao' value='Cadastrar' class='btncadastrar'>
        </div>

        <div class='lines'>
            <div class='line1'></div>Ou <div class='line2'></div>
        </div>
        <div class='button2'>
            <input type="submit" name='botao' value='Voltar' class='btncadastrar'>
        </div>
    </form>
    </article>
        </div>

    </main>
    
    <div class='bottomline'></div>
    <footer>
        <p>&copy; 2025 Luft. (quase) todos os direitos reservados.</p>
    </footer>


    
    
</body>
</html>