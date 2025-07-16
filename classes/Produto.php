<?php
require_once 'DataBase.php';

class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $categoria; // nome da categoria
    // public $imagem; // Descomente quando a coluna existir no banco

    public function __construct($id, $nome, $descricao, $preco, $categoria/*, $imagem = null*/) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
        // $this->imagem = $imagem; // Descomente quando a coluna existir no banco
    }

    public static function buscarTodos() {
        $conn = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria
                FROM tbProduto p
                JOIN tbCategorias c ON p.categoria = c.id';
        $stmt = $conn->query($sql);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria']
                // , null // Descomente e ajuste quando a coluna existir no banco
            );
        }
        return $produtos;
    }

    public static function buscarPorCategoria($categoriaNome) {
        $conn = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria
                FROM tbProduto p
                JOIN tbCategorias c ON p.categoria = c.id
                WHERE c.nome = :categoriaNome';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['categoriaNome' => $categoriaNome]);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria']
                // , null // Descomente e ajuste quando a coluna existir no banco
            );
        }
        return $produtos;
    }

    public static function buscarPorProdutor($produtorId) {
        $conn = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria
                FROM tbProduto p
                JOIN tbCategorias c ON p.categoria = c.id
                WHERE p.produtor_id = :produtorId';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['produtorId' => $produtorId]);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria']
                // , null // Descomente e ajuste quando a coluna existir no banco
            );
        }
        return $produtos;
    }
}
