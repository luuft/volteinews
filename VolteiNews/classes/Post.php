<?php
require_once __DIR__ . "/../bd/MySQL.php";
require_once __DIR__ . "/Usuario.php";

class Post {
    private int $idPost;     
    private int $idUsuario;   
    private string $conteudo;  
    private int $noticias;
    private string $data;      
    private ?string $imagem;   
    private int $likes;        

    public function __construct(
        int $idUsuario, 
        string $conteudo = '', 
        int $noticias = 0,
        ?string $imagem = null, 
        string $data = '', 
        int $likes = 0 
    ) {
        $this->idPost = 0; 
        $this->idUsuario = $idUsuario;
        $this->conteudo = $conteudo;
        $this->noticias = $noticias;
        $this->data = $data ?: date('Y-m-d H:i:s');
        $this->imagem = $imagem;
        $this->likes = $likes;
    }

    public function getIdPost(): int { 
        return $this->idPost; 
    }
    public function getIdUsuario(): int { 
        return $this->idUsuario; 
    }
    public function getConteudo(): string { 
        return $this->conteudo; 
    }
    public function getNoticias(): int {
        return $this->noticias;
    }
    public function getData(): string { 
        return $this->data; 
    }
    public function getImagem(): ?string { 
        return $this->imagem; 
    }
    public function getLikes(): int { 
        return $this->likes; 
    }
    
    

    

    public function save(): bool {
        $conexao = new MySQL();

        if ($this->idPost > 0) {
            $sql = "UPDATE post SET 
                        conteudo = '{$this->conteudo}',
                        noticias = {$this->noticias},
                        data = '{$this->data}',
                        imagem = '{$this->imagem}',
                        likes = '{$this->likes}'
                        
                    WHERE idPost = {$this->idPost}";
        } else {
            $sql = "INSERT INTO post (idUsuario, conteudo, noticias, data, imagem, likes)
                    VALUES (
                        {$this->idUsuario},
                        '{$this->conteudo}',
                        {$this->noticias},
                        '{$this->data}',
                        '{$this->imagem}',
                        {$this->likes}
                    )";
        }

        return $conexao->executa($sql);
    }

    public static function find(int $idPost): Post {
        $conexao = new MySQL();
        $sql = "SELECT * FROM post WHERE idPost = {$idPost}";
        $resultado = $conexao->consulta($sql);

        if (!$resultado) {
            throw new Exception("Post não encontrado");
        }

        $post = new Post(
            $resultado[0]['idUsuario'],
            $resultado[0]['conteudo'],
            $resultado[0]['noticias'] ?? 0,
            $resultado[0]['imagem'] ?? null,
            $resultado[0]['data'],
            $resultado[0]['likes'] ?? 0
        );

        $post->idPost = $resultado[0]['idPost'];
        return $post;
    }

    public static function all(): array {
        $conexao = new MySQL();
        $sql = "SELECT * FROM post ORDER BY idPost DESC";
        $resultados = $conexao->consulta($sql);

        $posts = [];
        foreach ($resultados as $row) {
            $post = new Post(
                $row['idUsuario'],
                $row['conteudo'],
                $row['noticias'] ?? 0,
                $row['imagem'] ?? null,
                $row['data'],
                $row['likes'] ?? 0
            );
            $post->idPost = $row['idPost'];
            $posts[] = $post;
        }
        return $posts;
    }

    public function getAutor(): Usuario {
        return Usuario::find($this->idUsuario);
    }
    
    public static function allPublic(): array {
    $db = new MySQL();
    $resultado = $db->consulta("SELECT * FROM post WHERE noticias = 0 ORDER BY data DESC");

    $posts = [];
    foreach ($resultado as $linha) {
        $p = new Post(
            (int)$linha['idUsuario'],
            $linha['conteudo'],
            (int)($linha['noticias'] ?? 0),
            $linha['imagem'] ?? null,
            $linha['data'],
            (int)($linha['likes'] ?? 0)    
        );

        $p->idPost = (int)$linha['idPost'];
        $posts[] = $p;
    }
    return $posts;
}

    public static function allPrivate(): array {
    $db = new MySQL();
    $resultado = $db->consulta("SELECT * FROM post WHERE noticias = 1 ORDER BY data DESC");

    $posts = [];
    foreach ($resultado as $linha) {
        $p = new Post(
            (int)$linha['idUsuario'],
            $linha['conteudo'],
            (int)($linha['noticias'] ?? 1),
            $linha['imagem'] ?? null,
            $linha['data'],
            (int)($linha['likes'] ?? 0)
        );

        $p->idPost = (int)$linha['idPost'];
        $posts[] = $p;
    }
    return $posts;
}

public function toggleLike(int $idUsuario): bool {
    $conexao = new MySQL();

    $sqlCheck = "SELECT * FROM likepost WHERE idPost = {$this->idPost} AND idUsuario = {$idUsuario}";
    $resultado = $conexao->consulta($sqlCheck);

    if ($resultado) {
        $sqlDelete = "DELETE FROM likepost WHERE idPost = {$this->idPost} AND idUsuario = {$idUsuario}";
        $conexao->executa($sqlDelete);
        $this->likes--; 
    } else {
        $sqlInsert = "INSERT INTO likepost (idPost, idUsuario) VALUES ({$this->idPost}, {$idUsuario})";
        $conexao->executa($sqlInsert);
        $this->likes++; 
    }

    $sqlUpdate = "UPDATE post SET likes = {$this->likes} WHERE idPost = {$this->idPost}";
    return $conexao->executa($sqlUpdate);
}


public function isLikedBy(int $idUsuario): bool {
    $conexao = new MySQL();
    $sql = "SELECT * FROM likepost WHERE idPost = {$this->idPost} AND idUsuario = {$idUsuario}";
    $resultado = $conexao->consulta($sql);

    return !empty($resultado);
}

public static function AllMy(int $idUsuario): array {
    $db = new MySQL();
    $resultado = $db->consulta("SELECT * FROM post p JOIN usuario u ON p.idUsuario = u.idUsuario WHERE p.idUsuario = {$idUsuario} ORDER BY data DESC");

    $posts = [];
    foreach ($resultado as $linha) {
        $p = new Post(
            (int)$linha['idUsuario'],
            $linha['conteudo'],
            (int)($linha['noticias'] ?? 0),
            $linha['imagem'] ?? null,
            $linha['data'],
            (int)($linha['likes'] ?? 0)
        );

        $p->idPost = (int)$linha['idPost'];
        $posts[] = $p;
    }
    return $posts;
}

public function delete(): bool {
    $conexao = new MySQL();

    $sqlLikes = "DELETE FROM likepost WHERE idPost = {$this->idPost}";
    $conexao->executa($sqlLikes);

    $sql = "DELETE FROM post WHERE idPost = {$this->idPost}";
    return $conexao->executa($sql);
}

public static function profile(int $idUsuario): array {
    try {
        $usuario = Usuario::find($idUsuario);
    } catch (Exception $e) {
        throw new Exception("Usuário não encontrado.");
    }

    $posts = self::AllMy($idUsuario);

    return [
        'usuario' => $usuario,
        'posts'   => $posts
    ];
}


}

?>
