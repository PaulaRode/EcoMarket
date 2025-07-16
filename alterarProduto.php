<?php
include_once './config/config.php';
include_once './classes/DataBase.php';
include_once './classes/Produto.php';
include_once './classes/Categoria.php';

$db = DataBase::getConnection();
$produtoObj = new Produto($db);

// Buscar categorias do banco
$listaCategorias = Categoria::buscarTodas();

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
            <input type="text" name="preco" id="preco" placeholder="0,00" pattern="[0-9]+([,\.][0-9]{1,2})?" onkeypress="return validarPreco(event)" oninput="formatarPreco(this)" value="<?= htmlspecialchars(str_replace('.', ',', $produto['preco'])) ?>" required>

            <label>Categoria:</label><br>
            <select name="categoria" required>
                <?php foreach ($listaCategorias as $categoria): ?>
                    <option value="<?php echo $categoria->id; ?>" <?= $produto['categoria'] == $categoria->nome ? 'selected' : '' ?>>
                        <?php echo htmlspecialchars($categoria->nome); ?>
                    </option>
                <?php endforeach; ?>
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

    <script>
        // Função para validar entrada de preço
        function validarPreco(event) {
            const char = String.fromCharCode(event.which);
            const input = event.target;
            const value = input.value;
            
            // Permitir apenas números, vírgula e ponto
            if (!/[0-9,\.]/.test(char)) {
                event.preventDefault();
                return false;
            }
            
            // Não permitir mais de uma vírgula ou ponto
            if ((char === ',' || char === '.') && (value.includes(',') || value.includes('.'))) {
                event.preventDefault();
                return false;
            }
            
            // Não permitir vírgula ou ponto no início
            if ((char === ',' || char === '.') && value.length === 0) {
                event.preventDefault();
                return false;
            }
            
            return true;
        }
        
        // Função para formatar o preço
        function formatarPreco(input) {
            let value = input.value;
            
            // Remover tudo exceto números, vírgula e ponto
            value = value.replace(/[^0-9,\.]/g, '');
            
            // Substituir vírgula por ponto para cálculos
            value = value.replace(',', '.');
            
            // Garantir apenas um ponto decimal
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            
            // Limitar a 2 casas decimais
            if (parts.length === 2 && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }
            
            // Converter de volta para vírgula para exibição
            value = value.replace('.', ',');
            
            input.value = value;
        }
        
        // Validação do formulário antes do envio
        document.querySelector('form').addEventListener('submit', function(e) {
            const precoInput = document.getElementById('preco');
            const preco = precoInput.value.replace(',', '.');
            
            if (isNaN(preco) || parseFloat(preco) <= 0) {
                e.preventDefault();
                alert('Por favor, insira um preço válido maior que zero.');
                precoInput.focus();
                return false;
            }
        });
    </script>

</body>

</html>