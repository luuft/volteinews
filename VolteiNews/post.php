<?php
require_once "classes/Usuario.php";
require_once "classes/Post.php";
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = Usuario::find($_SESSION['idUsuario']);
$mensagem = '';

if (isset($_POST['conteudo'])) {
    $conteudo = $_POST['conteudo'] ?? '';
    $imagem = null;

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $uploadDir = 'images/posts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755);
        }

        $imagem = $uploadDir . time() . '_' . basename($_FILES['imagem']['name']);
        move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);
    }

    $post = new Post($usuario->getIdUsuario(), $conteudo, 0, $imagem);
    if ($post->save()) {
        header("Location: menu.php");
        exit;
    } else {
        $mensagem = "Erro ao criar o post.";
    }
}


$pfp = $usuario->getPfp();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <link rel="stylesheet" href="styles/post.css">
</head>
<body>
    <header>
        <div class="center-header">
            <img src="images/logo.png" alt="logo" class="logo">
            <h1>NEWS</h1>
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
                    <li class='side'><a href="menu.php" class='pages'><img class='pagesicon' id='home' src="images/home_icon.png" alt="">Página inicial</a></li>
                    <li class='side'><a href="news.php" class='pages'><img class='pagesicon' id='news' src="images/news_icon.png" alt="">Notícias do dia</a></li>
                    <li class='side'><a href="myprofile.php" class='pages'><img class='pagesicon' src="<?php echo htmlspecialchars($pfp); ?>" alt="">Perfil</a></li>
                    <?php if ($usuario && $usuario->isAdmin()) : ?>
                    <li class='side'><a href="restrictpost.php" class='pages'><img class='pagesicon' id='addnotice' src="images/secret_icon.png" alt="">Adicionar notícia</a></li>
                    <?php endif; ?>
                    <li class='side'><a href="logout.php" class='pages'><img class='pagesicon' id='logout' src="images/logout_icon.png" alt="">Sair</a></li>
                </ul>
            </nav>
        </aside>

        <main>

            <div class="image-modal" id="imageModal">
  <span class="close-modal">&times;</span>
  <img class="modal-content" id="modalImg">
</div>
<h2>Adicione seu post!</h2>

<form action="" method="post" enctype="multipart/form-data">
    <textarea name="conteudo" maxlength="200" placeholder="No que você está pensando?" required></textarea>
    <br>
    <label class="custom-file-upload">
  Selecionar imagem
  <input type="file" name="imagem" accept="image/*">
</label>

    <br>
    <button type="submit">Postar</button>
</form>

<?php if ($mensagem): ?>
    <p><?php echo htmlspecialchars($mensagem); ?></p>
<?php endif; ?>

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
</script>
</body>
</html>



