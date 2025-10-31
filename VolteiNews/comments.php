<?php
require_once "classes/Usuario.php";
require_once "classes/Post.php";
require_once "classes/Comentarios.php";
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = Usuario::find($_SESSION['idUsuario']);
$pfp = $usuario->getPfp();
$nomeUsuario = $_SESSION['nome'];

if (!isset($_GET['idPost'])) {
    header("Location: menu.php");
    exit;
}

$idPost = (int)$_GET['idPost'];
$post = Post::find($idPost);
$comentarios = Comentarios::allByPost($idPost);


if (isset($_POST['comentario'])) {
    $texto = trim($_POST['comentario']);
    if (!empty($texto)) {
        $novoComentario = new Comentarios($idPost, $usuario->getIdUsuario(), $texto);
        $novoComentario->save();
        header("Location: comments.php?idPost=$idPost");
        exit;
    }
}

if (isset($_GET['excluir'])) {
    Comentarios::excluir((int)$_GET['excluir']);
    header("Location: comments.php?idPost=$idPost");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentários</title>
    <link rel="stylesheet" href="styles/comments.css">
</head>
<body>
    <header>
        <div class="center-header">
            <img src="images/logo.png" alt="logo" class="logo">
            <h1>Comentários</h1>
        </div>
    </header>
    <div class="line"></div>

    <div class="container">
        <main>
            <article class="post">
                <a href="menu.php"><img src="images/return.png" class='return' alt=""></a>
                <h2>Comentários sobre: "<?php echo htmlspecialchars($post->getConteudo()); ?>"</h2>

                <form method="post" class="form-comentario">
                    <textarea name="comentario" class='new-comment' placeholder="Adicione um comentário..." required></textarea><br>
                    <button type="submit" class='addcomment'>Enviar</button>
                </form>

                <?php if (empty($comentarios)): ?>
                    <p class="sem-comentarios">Não há comentários neste post. Seja o primeiro!</p>
                <?php else: ?>
                    <?php foreach ($comentarios as $comentario): 
                        $autor = Usuario::find($comentario->getIdUsuario());
                    ?>
                    <div class="comentario">
                            <div class='post-header'>
                    <img src="<?php echo htmlspecialchars($autor->getPfp()); ?>" alt="Imagem de perfil" class="postpfp">
                        <div class="post-user">
                        <span class="username"><?php echo htmlspecialchars($autor->getNome()); ?></span>
                        <span class="post-date"><?php echo htmlspecialchars($post->getData()); ?></span>
                        </div>
                            </div>
                    <div class='post-content'>
                        <p class="texto"><?php echo htmlspecialchars($comentario->getComentario()); ?></p>
                    </div>
                            <?php if ($autor->getIdUsuario() === $usuario->getIdUsuario()): ?>
                                <a href="comments.php?idPost=<?php echo $idPost; ?>&excluir=<?php echo $comentario->getIdComentario(); ?>" class="excluir"><img src="images/trash.png" alt="" class='delete'></a>
                            <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </article>
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
