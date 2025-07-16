<?php
require_once 'DataBase.php';

class Categoria {
    public $id;
    public $nome;

    public function __construct($id, $nome) {
        $this->id = $id;
        $this->nome = $nome;
    }

    public static function buscarTodas() {
        $conn = DataBase::getConnection();
        $sql = 'SELECT id, nome FROM tbCategorias';
        $stmt = $conn->query($sql);
        $categorias = [];
        while ($row = $stmt->fetch()) {
            $categorias[] = new Categoria($row['id'], $row['nome']);
        }
        return $categorias;
    }
}
