<?php
require_once "classes/Usuario.php";
require_once "classes/Post.php";
session_start();


if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = Usuario::find($_SESSION['idUsuario']);

if (isset($_GET['idPost'])) {
    $idPost = (int)$_GET['idPost'];

    try {
        $post = Post::find($idPost);
        $post->toggleLike($usuario->getIdUsuario());
    } catch (Exception $e) {
    
    }
}


$referer = $_SERVER['HTTP_REFERER'] ?? 'menu.php';
header("Location: $referer");
exit;
