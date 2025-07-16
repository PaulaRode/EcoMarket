<?php
class Produto {
    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $categoria; // nome da categoria
    public $imagem; // caminho da imagem

    private $conn;

    public function __construct($id = null, $nome = null, $descricao = null, $preco = null, $categoria = null, $imagem = null) {
        if ($id !== null && $nome !== null && $descricao !== null && $preco !== null && $categoria !== null) {
            // Construtor para criar objeto Produto com dados
            $this->id = $id;
            $this->nome = $nome;
            $this->descricao = $descricao;
            $this->preco = $preco;
            $this->categoria = $categoria;
            $this->imagem = $imagem;
        } else {
            // Construtor para operações de banco de dados
            $this->conn = $id; // $id na verdade é a conexão PDO
        }
    }

    public static function buscarTodos() {
        $conn = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria, p.link_img as imagem
                FROM tbproduto p
                JOIN tbCategorias c ON p.categoria = c.id';
        $stmt = $conn->query($sql);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria'],
                $row['imagem']
            );
        }
        return $produtos;
    }

    public static function buscarPorCategoria($categoriaNome) {
        $conn = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria, p.link_img as imagem
                FROM tbproduto p
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
                $row['categoria'],
                $row['imagem']
            );
        }
        return $produtos;
    }

    public static function buscarPorProdutor($produtorId) {
        $conn = DataBase::getConnection();
        $sql = 'SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria, p.link_img as imagem
                FROM tbproduto p
                JOIN tbCategorias c ON p.categoria = c.id
                WHERE p.id_usuario = :produtorId';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['produtorId' => $produtorId]);
        $produtos = [];
        while ($row = $stmt->fetch()) {
            $produtos[] = new Produto(
                $row['id'],
                $row['nome'],
                $row['descricao'],
                $row['preco'],
                $row['categoria'],
                $row['imagem']
            );
        }
        return $produtos;
    }

    public function criar($nome, $descricao, $preco, $categoria, $imagem = null) {
        if (!$this->conn) {
            return false;
        }

        $query = "INSERT INTO tbproduto (nome, descricao, preco, categoria, id_usuario, link_img) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        // Pegar o ID do usuário logado da sessão
        $id_usuario = $_SESSION['id'] ?? null;
        
        return $stmt->execute([$nome, $descricao, $preco, $categoria, $id_usuario, $imagem]);
    }

    public function atualizar($id, $nome, $descricao, $preco, $categoria, $imagem = null) {
        if (!$this->conn) {
            return false;
        }

        $query = "UPDATE tbproduto SET nome = ?, descricao = ?, preco = ?, categoria = ?";
        $params = [$nome, $descricao, $preco, $categoria];
        
        if ($imagem !== null) {
            $query .= ", link_img = ?";
            $params[] = $imagem;
        }
        
        $query .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function buscarPorId($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT p.id, p.nome, p.descricao, p.preco, c.nome as categoria, p.link_img as imagem
                  FROM tbproduto p
                  JOIN tbCategorias c ON p.categoria = c.id
                  WHERE p.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function deletar($id) {
        if (!$this->conn) {
            return false;
        }

        $query = "DELETE FROM tbproduto WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
