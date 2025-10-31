<?php 
require_once __DIR__ . "/../bd/MySQL.php";

class Usuario {
    private int $idUsuario;

    
    public function __construct(
        private string $nome='', 
        private string $email='', 
        private string $senha='', 
        private string $pfp='images/defaultpfp.png',
        private string $about=''
    ) {}

    
    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setSenha(string $senha): void {
        $this->senha = $senha;
    }

    public function getSenha(): string {
        return $this->senha;
    }

    public function setPfp(string $pfp): void {
        $this->pfp = $pfp;
    }

    public function getPfp(): string {
        return $this->pfp;
    }

    public function setAbout(string $about): void {
        $this->about = $about ?? '';
    }

    public function getAbout(): string {
        return $this->about;
    }

    
    public function save(): bool {
    $conexao = new MySQL();

    if (isset($this->idUsuario)) {
        $sql = "UPDATE usuario SET 
                    nome = '{$this->nome}', 
                    email = '{$this->email}', 
                    pfp = '{$this->pfp}', 
                    about = '{$this->about}' 
                WHERE idUsuario = {$this->idUsuario}";
    } else {
        $senhaHash = password_hash($this->senha, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuario (nome, email, senha, pfp, about) 
                VALUES ('{$this->nome}', '{$this->email}', '{$senhaHash}', '{$this->pfp}', '{$this->about}')";
    }

    return $conexao->executa($sql);
}


    public static function find($idUsuario): Usuario {
        $conexao = new MySQL();
        $sql = "SELECT * FROM usuario WHERE idUsuario = {$idUsuario}";
        $resultado = $conexao->consulta($sql);

        $u = new Usuario(
            $resultado[0]['nome'],
            $resultado[0]['email'],
            $resultado[0]['senha']
        );
        $u->setIdUsuario($resultado[0]['idUsuario']);
        $u->setAdmin((bool)$resultado[0]['admin']); 
        $u->setPfp($resultado[0]['pfp']);
        $u->setAbout($resultado[0]['about']);
        return $u;
    }

    public function exists(): bool {
        $conexao = new MySQL();
        $sql = "SELECT idUsuario FROM usuario WHERE email = '{$this->email}'";
        $resultados = $conexao->consulta($sql);
        return count($resultados) > 0;
    }

    
    public function authenticate(): bool {
        $conexao = new MySQL();
        $sql = "SELECT idUsuario, nome, email, senha, admin, pfp, about FROM usuario WHERE email = '{$this->email}'";
        $resultados = $conexao->consulta($sql);

        if(count($resultados) === 0){
        return false;
    }

        if (password_verify($this->senha, $resultados[0]['senha'])) {
            session_start();
            $_SESSION['idUsuario'] = $resultados[0]['idUsuario'];
            $_SESSION['nome'] = $resultados[0]['nome'];
            $_SESSION['email'] = $resultados[0]['email'];
            $_SESSION['pfp'] = $resultados[0]['pfp'];
            $_SESSION['admin'] = (bool)$resultados[0]['admin'];
            $_SESSION['about'] = $resultados[0]['about'];
            return true;
        } else {
            return false;
        }

    }
    public static function login(string $email, string $senha): ?Usuario {
        $u = new Usuario("", $email, $senha);
        return $u->authenticate() ? $u : null;
    }

    public static function cadastro(string $nome, string $email, string $senha): Usuario {
        $u = new Usuario($nome, $email, $senha);
        $u->save();
        return $u;
    }

    private bool $admin = false;

    public function isAdmin(): bool {
    return $this->admin;
    }

    public function setAdmin(bool $admin): void {
    $this->admin = $admin;
    }

    public function newPfp(string $novaPfp): bool {
        $conexao = new MySQL();
        $sql = "UPDATE usuario SET pfp = '{$novaPfp}' WHERE idUsuario = {$this->idUsuario}";

    if ($conexao->executa($sql)) {
        $this->pfp = $novaPfp;
        return true;
    }
        return false;
    }

    public function aboutMe(string $novaDesc): bool {
        $conexao = new MySQL();
        $sql = "UPDATE usuario SET about = '{$novaDesc}' WHERE idUsuario = {$this->idUsuario}";

        if ($conexao->executa($sql)) {
        $this->about = $novaDesc;
        return true;
    }
        return false;
    }
    

}


?>
