<?php
require_once 'DataBase.php';

class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $categoria;
    public $imagem;

    public function __construct($id, $nome, $descricao, $preco, $categoria, $imagem) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
        $this->imagem = $imagem;
    }

    public static function buscarTodos() {
        // Aqui você implementará a busca real no banco depois
        // Simulação de produtos
        return [
            new Produto(1, 'Sabonete Natural', 'Sabonete artesanal feito com óleos essenciais.', 12.90, 'Cosméticos', 'sabonete.jpg'),
            new Produto(2, 'Granola Orgânica', 'Granola feita com ingredientes 100% orgânicos.', 18.50, 'Alimentos', 'granola.jpg'),
            new Produto(3, 'Shampoo Sólido', 'Shampoo ecológico sem plástico.', 22.00, 'Cosméticos', 'shampoo.jpg'),
            new Produto(4, 'Mel Puro', 'Mel de abelhas nativas, direto do produtor.', 25.00, 'Alimentos', 'mel.jpg'),
        ];
    }

    public static function buscarPorCategoria($categoria) {
        $todos = self::buscarTodos();
        return array_filter($todos, function($produto) use ($categoria) {
            return $produto->categoria === $categoria;
        });
    }
}
