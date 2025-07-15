<?php
require_once 'DataBase.php';

class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $categoria;
    public $imagem;

    public function __construct($id, $nome, $descricao, $preco, $categoria, $imagem = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
        $this->imagem = $imagem;
    }

    public static function buscarTodos() {
        $pdo = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria FROM tbproduto p JOIN tbcategorias c ON p.categoria = c.id';
        $stmt = $pdo->query($sql);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria'],
                null // Sem imagem
            );
        }
        return $produtos;
    }

    public static function buscarPorCategoria($categoria) {
        $pdo = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria FROM tbproduto p JOIN tbcategorias c ON p.categoria = c.id WHERE c.nome = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$categoria]);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria'],
                null // Sem imagem
            );
        }
        return $produtos;
    }
}
