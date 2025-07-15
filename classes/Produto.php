<?php 
class Produto {
    private $conn;
    private $table_name = "tbProduto";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar($nome, $descricao, $preco, $categoria) {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, preco, categoria_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$nome, $descricao, $preco, $categoria]);
    }

    public function ler() {
        $query = "SELECT * FROM " . $this->table_name; 
        return $this->conn->query($query);
    }

    public function atualizar($id_categoria,$nome, $descricao, $preco, $categoria) {
        $query = "UPDATE " . $this->table_name . " SET nome = ?, descricao = ?, preco = ?, categoria_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$nome, $descricao, $preco, $categoria, $id_categoria]);
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


?>