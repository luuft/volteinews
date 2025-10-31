<?php
require_once __DIR__ . "/../bd/MySQL.php";
require_once __DIR__ . "/Usuario.php";
require_once __DIR__ . "/Post.php";

class Comentarios {
    private int $idComentario;
    private int $idPost;
    private int $idUsuario;
    private string $comentario;
    private string $data;

    public function __construct(
        int $idPost,
        int $idUsuario,
        string $comentario,
        string $data = ''
    ) {
        $this->idComentario = 0;
        $this->idPost = $idPost;
        $this->idUsuario = $idUsuario;
        $this->comentario = $comentario;
        $this->data = $data ?: date('Y-m-d H:i:s');
    }

    public function getIdComentario(): int {
        return $this->idComentario;
    }

    public function getIdPost(): int {
        return $this->idPost;
    }

    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    public function getComentario(): string {
        return $this->comentario;
    }

    public function getData(): string {
        return $this->data;
    }

    public function save(): bool {
    $conexao = new MySQL();

    if ($this->idComentario > 0) {
        $sql = "UPDATE comentario SET 
                comentario = '{$this->comentario}',
                data = '{$this->data}'
                WHERE idComentario = {$this->idComentario}";
    } else {
        $sql = "INSERT INTO comentario (idPost, idUsuario, comentario, data)
                VALUES (
                {$this->idPost},
                {$this->idUsuario},
                '{$this->comentario}',
                '{$this->data}'
                )";
    }

        return $conexao->executa($sql);
    }

    public static function allByPost(int $idPost): array {
        $conexao = new MySQL();
        $sql = "SELECT * FROM comentario WHERE idPost = {$idPost} ORDER BY data DESC";
        $resultados = $conexao->consulta($sql);

        $comentarios = [];
        foreach ($resultados as $row) {
            $c = new Comentarios(
                (int)$row['idPost'],
                (int)$row['idUsuario'],
                $row['comentario'],
                $row['data']
            );
            $c->idComentario = (int)$row['idComentario'];
            $comentarios[] = $c;
        }

        return $comentarios;
    }

    public static function excluir(int $idComentario): bool {
        $conexao = new MySQL();
        $sql = "DELETE FROM comentario WHERE idComentario = {$idComentario}";
        return $conexao->executa($sql);
    }
}

?>