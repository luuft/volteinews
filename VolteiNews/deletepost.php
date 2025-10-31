<?php
require_once "classes/Post.php";
session_start();

if (!isset($_SESSION['idUsuario']) || !isset($_GET['idPost'])) {
    header("Location: menu.php");
    exit;
}

$idPost = (int) $_GET['idPost'];
$post = Post::find($idPost);

if ((int)$post->getIdUsuario() === (int)$_SESSION['idUsuario']) {
    $post->delete();
}


header("Location: menu.php");
exit;
?>