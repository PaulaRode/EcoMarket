<?php 
class Usuario  {
    private $conn;
    private $table_name = "tbusu";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar($nome, $email, $senha, $telefone) {
        $query = "INSERT INTO " . $this->table_name . " (nome, email, senha, telefone) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($senha, PASSWORD_BCRYPT);
        return $stmt->execute([$nome, $email, $password_hash, $telefone]);
    }

    public function ler() {
        $query = "SELECT * FROM " . $this->table_name; 
        return $this->conn->query($query);
    }

    public function login($email, $senha) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return null;
    }

    public function atualizar($id, $nome, $email, $senha, $telefone) {
        $query = "UPDATE " . $this->table_name . " SET nome = ?, email = ?, senha = ?, telefone = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($senha, PASSWORD_BCRYPT);
        return $stmt->execute([$nome, $email, $password_hash, $telefone, $id]);
    }

    public function deletar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }


}