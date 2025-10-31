<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "lfnews";


$mysqli = new mysqli($host, $usuario, $senha);
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}


$mysqli->query("CREATE DATABASE IF NOT EXISTS $banco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($banco);


$mysqli->query("
CREATE TABLE IF NOT EXISTS usuario (
    idUsuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    admin TINYINT(1) NOT NULL DEFAULT 0,
    pfp VARCHAR(150) NOT NULL DEFAULT 'images/defaultpfp.png',
    about VARCHAR(255) NOT NULL DEFAULT ' '
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");


$mysqli->query("
CREATE TABLE IF NOT EXISTS post (
    idPost INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT UNSIGNED NOT NULL,
    conteudo TEXT NOT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    imagem VARCHAR(255) DEFAULT NULL,
    noticias TINYINT(1) NOT NULL DEFAULT 0,
    likes INT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (idUsuario) REFERENCES usuario(idUsuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");


$mysqli->query("
CREATE TABLE IF NOT EXISTS comentario (
    idComentario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    idPost INT UNSIGNED NOT NULL,
    idUsuario INT UNSIGNED NOT NULL,
    comentario TEXT NOT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idPost) REFERENCES post(idPost) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES usuario(idUsuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$mysqli->query("
CREATE TABLE IF NOT EXISTS likepost (
    idLike INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    idPost INT UNSIGNED NOT NULL,
    idUsuario INT UNSIGNED NOT NULL,
    FOREIGN KEY (idPost) REFERENCES post(idPost) ON DELETE CASCADE,
    FOREIGN KEY (idUsuario) REFERENCES usuario(idUsuario) ON DELETE CASCADE,
    UNIQUE KEY unico_like (idPost, idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");


$hashSenhaAdmin = '$2y$10$gMyYK4i2WqqWciBC0F7KvOyCdiQlz2Zx9FRoetceNlTFT8bRBgvX2';
$mysqli->query("
INSERT INTO usuario (nome, email, senha, admin)
SELECT * FROM (SELECT 'admin', 'joaovluftp@gmail.com', '$hashSenhaAdmin', 1) AS tmp
WHERE NOT EXISTS (
    SELECT email FROM usuario WHERE email = 'joaovluftp@gmail.com'
) LIMIT 1;
");

?>
