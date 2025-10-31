<?php
require_once "classes/Usuario.php";
require_once "classes/Post.php";
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = Usuario::find($_SESSION['idUsuario']);
$pfp = $usuario->getPfp();
$nomeUsuario = $_SESSION['nome'];
$posts = Post::allPrivate();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <link rel="stylesheet" href="styles/menu.css">
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
                    <li class='side'><img class='pagesicon' id='home' src="images/home_icon.png" alt=""><a href="menu.php" class='pages'>Página inicial</a></li>
                    <li class='side'><img class='pagesicon' id='news' src="images/news_icon.png" alt=""><a href="news.php" class='pages'>Notícias do dia</a></li>
                    <li class='side'><img class='pagesicon' src="<?php echo htmlspecialchars($pfp); ?>" alt=""><a href="myprofile.php" class='pages'>Perfil</a></li>
            <?php if ($usuario && $usuario->isAdmin()) : ?>
                    <li class='side'><img class='pagesicon' id='addnotice' src="images/secret_icon.png" alt=""><a href="restrictpost.php" class='pages'>Adicionar notícia</a></li>
            <?php endif; ?>
                    <li class='side'><img class='pagesicon' id='logout' src="images/logout_icon.png" alt=""><a href="logout.php" class='pages'>Sair</a></li>

                </ul>
            </nav>
        </aside>

        <main>
            <article class='bn'>
                <h2>Breaking News</h2>
            </article>
            <?php if (empty($posts)): ?>
                <section class="no-posts">
                    <p>Não há notícias disponíveis no momento. Volte mais tarde!
                    </p>
                </section>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
    <?php $autor = $post->getAutor(); ?>
            <article class="post" id="post-<?php echo $post->getIdPost(); ?>">
            <div class='post-header'>
                <img src="<?php echo htmlspecialchars($autor->getPfp()); ?>" alt="Imagem de perfil" class="postpfp">
                <div class="post-user">
                    <span class="post-date"><?php echo htmlspecialchars($post->getData()); ?></span>
                </div>
            </div>

            <div class='post-content'>
                <p><?php echo htmlspecialchars($post->getConteudo()); ?></p>
                <?php if ($post->getImagem()): ?>
                    <img src="<?php echo htmlspecialchars($post->getImagem()); ?>" alt="Imagem do post" class='postimg'>
                <?php endif; ?>
            </div>

                <div class='post-actions'>
    <a href="like.php?idPost=<?= $post->getIdPost(); ?>" class="like-btn">
        <img src="images/<?= $post->isLikedBy($usuario->getIdUsuario()) ? 'liked.png' : 'like.png' ?>" alt="like" class='likepost'>
        <span class="like-count"><?= $post->getLikes(); ?></span>
    </a>
    <a href="comments.php?idPost=<?= $post->getIdPost(); ?>" class="comment-btn">
        <img src="images/comment.png" alt="comment" class='commentpost'>
    </a>
    <?php if ((int)$post->getIdUsuario() === (int)$_SESSION['idUsuario']): ?>
                    <a href="deletepost.php?idPost=<?= $post->getIdPost(); ?>" class="delete-btn"
                    class="delete-btn" 
                    onclick="return confirm('Deseja realmente apagar este post?');">
                    <img src="images/trash.png" class='deletepost' alt="">
                    </a>
                <?php endif; ?>
</div>
            </article>
            <?php endforeach; ?>
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
<div class="image-modal" id="imageModal">
  <span class="close-modal">&times;</span>
  <img class="modal-content" id="modalImg">
</div>

<script>
const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("modalImg");
const closeBtn = document.querySelector(".close-modal");
document.querySelectorAll(".postimg").forEach(img => {
  img.addEventListener("click", () => {
    modal.style.display = "flex";
    modalImg.src = img.src;
    document.body.style.overflow = "hidden";
  });
});

closeBtn.onclick = () => {
  modal.style.display = "none";
  document.body.style.overflow = "";
};

modal.onclick = (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
    document.body.style.overflow = "";
  }
};
</script>
</body>
</html>
