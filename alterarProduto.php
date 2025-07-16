<?php
include_once './config/config.php';
include_once './classes/DataBase.php';
include_once './classes/Produto.php';

$db = DataBase::getConnection();
$produtoObj = new Produto($db);

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    echo "ID do produto não fornecido.";
    exit();
}

$id = $_GET['id'];

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];

    // Processar upload da nova imagem
    $novaImagem = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $pastaDestino = "uploads/";
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0755, true);
        }

        $nomeOriginal = basename($_FILES['imagem']['name']);
        $novoNome = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $nomeOriginal);
        $caminhoCompleto = $pastaDestino . $novoNome;

        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
            $novaImagem = $caminhoCompleto;
        }
    }

    if ($produtoObj->atualizar($id, $nome, $descricao, $preco, $categoria, $novaImagem)) {
        header("Location: dashboard.php?msg=editado");
        exit();
    } else {
        echo "Erro ao editar o produto.";
    }
} else {
    // Buscar os dados do produto pelo ID
    $produto = $produtoObj->buscarPorId($id);

    if (!$produto) {
        echo "Produto não encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Alterar Produto</title>
    <link rel="stylesheet" href="./styles/alterarProduto.css">

</head>

<body>
    <div class="container-cadastro">
        <img src="./assets/logo.png" alt="Logo da Empresa" class="logo">

        <h1>Alterar Produto</h1>
        <form method="post" enctype="multipart/form-data">
            <label>Nome:</label><br>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>

            <label>Descrição:</label><br>
            <textarea name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea>

            <label>Preço:</label><br>
            <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>

            <label>Categoria:</label><br>
            <select name="categoria" required>
                <option value="1" <?= $produto['categoria'] == 'alimentos' ? 'selected' : '' ?>>Alimentos</option>
                <option value="2" <?= $produto['categoria'] == 'limpeza' ? 'selected' : '' ?>>Limpeza</option>
            </select>

            <label>Imagem atual:</label><br>
            <?php if (isset($produto['imagem']) && $produto['imagem']): ?>
                <img src="<?= htmlspecialchars($produto['imagem']) ?>" width="150" style="border-radius: 8px; border: 1px solid #ddd;">
            <?php else: ?>
                <p>Nenhuma imagem cadastrada</p>
            <?php endif; ?>

            <label>Nova imagem (opcional):</label><br>
            <input type="file" name="imagem" accept="image/*">

            <button type="submit">Salvar Alterações</button>
        </form>
        <a href="./dashboard.php" class="btn-voltar">Voltar</a>

        <a href="deletarProduto.php?id=<?= $id ?>"
            onclick="return confirm('Tem certeza que deseja excluir este produto?');"
            style="display: inline-block; background-color: red; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin-top: 15px;">
            Excluir Produto
        </a>

    </div>
</body>

</html>