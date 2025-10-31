<?php 
require_once "classes/Usuario.php";

session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = Usuario::find($_SESSION['idUsuario']);

if (isset($_POST['salvar'])) {
    $usuario->setNome($_POST['nome']);
    $usuario->aboutMe($_POST['about']);

    if (isset($_FILES['pfp']) && $_FILES['pfp']['error'] === 0) {
        $uploadDir = 'images/pfps/';
        $novoArquivo = $uploadDir . basename($_FILES['pfp']['name']);
        move_uploaded_file($_FILES['pfp']['tmp_name'], $novoArquivo);
        $usuario->newPfp($novoArquivo);
    }

    $usuario->save();
    header("Location: myprofile.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>
    <header>
        <div class="center-header">
            <img src="images/logo.png" alt="logo" class="logo">
            <h1>News</h1>
        </div>
        <label for="toggle-aside" class="btn-toggle">☰</label>
    </header>
    <div class="line"></div>

    <input type="checkbox" id="toggle-aside" hidden>
    <div class='container'>
        <aside>
            <h3>Menu</h3>
            <nav>
                <ul>
                    <li class='side'><img class='pagesicon' src="images/home_icon.png" alt=""><a href="menu.php" class='pages'>Página inicial</a></li>
                    <li class='side'><img class='pagesicon' src="images/news_icon.png" alt=""><a href="news.php" class='pages'>Notícias do dia</a></li>
                    <li class='side'><img class='pagesicon' src="<?php echo htmlspecialchars($pfp); ?>" alt=""><a href="myprofile.php" class='pages'>Perfil</a></li>
            <?php if ($usuario && $usuario->isAdmin()) : ?>
                    <li class='side'><img class='pagesicon' src="images/secret_icon.png" alt=""><a href="restrictpost.php" class='pages'>Adicionar notícia</a></li>
            <?php endif; ?>
                    <li class='side'><img class='pagesicon' src="images/logout_icon.png" alt=""><a href="logout.php" class='pages'>Sair</a></li>

                </ul>
            </nav>
        </aside>

        <main>

            <form action="editprofile.php" method="POST" enctype="multipart/form-data">

            <div class='campo'>
                <input type="text" id="nome" name="nome" class='aba' placeholder='Seu nome' value="<?= htmlspecialchars($usuario->getNome()) ?>" required>
            </div>

            <div class='campo'>
                <textarea id="about" name="about" maxlength="200" class='abaabout' rows="4" placeholder='Fale um pouco sobre você'><?= htmlspecialchars($usuario->getAbout()) ?></textarea>
            </div>
                
            <h3>Foto de perfil:</h3>
            <label class="custom-file-upload">
                <input type="file" id="pfp" name="pfp" accept="image/*">
                Escolher imagem
            </label>
                <div class='botao'>
                    <button type="submit" class='btn' name="salvar">Salvar alterações</button>
                    <a href="myprofile.php" class='btn'>Voltar</a>
                </div>
            </form>
        
        </main>
    </div>

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