<?php
session_start();

// Verificar se o usuário está logado - OBRIGATÓRIO
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Redirecionar para login se não estiver logado
    header('Location: login.php');
    exit;
}

include_once './classes/DataBase.php';
include_once './classes/Produto.php';

// Cria conexão PDO com o banco
$db = DataBase::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = new Produto($db);

    // Sanitização básica
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_VALIDATE_INT);

    if (!$nome || !$descricao || $preco === false || !$categoria) {
        echo "<script>alert('Preencha todos os campos corretamente.');</script>";
    } else {
        // Processar upload da imagem
        $imagemNome = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $pastaDestino = "uploads/";
            if (!is_dir($pastaDestino)) {
                mkdir($pastaDestino, 0755, true);
            }

            $nomeOriginal = basename($_FILES['imagem']['name']);
            $novoNome = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $nomeOriginal); // limpa caracteres inválidos
            $caminhoCompleto = $pastaDestino . $novoNome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoCompleto)) {
                $imagemNome = $caminhoCompleto;
            }
        }

        // Executa cadastro do produto com o caminho da imagem (pode ser null)
        if ($produto->criar($nome, $descricao, $preco, $categoria, $imagemNome)) {
            header("Location: dashboard.php?msg=criado");
            exit();
        } else {
            header("Location: dashboard.php?msg=erro");
            exit();
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="./styles/cadastrarProduto.css">

</head>

<body>
    <div class="container-cadastro">
        <img src="./assets/logo.png" alt="Logo da Empresa" class="logo">
        <h1>Cadastrar Produto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nome">Nome do Produto:</label>
            <input type="text" name="nome" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="10" required></textarea>

            <label>Preço:</label>
            <input type="number" name="preco" step="0.01"><br>

            <label>Categoria:</label>
            <select name="categoria" required>
                <option value="1">Alimentos</option>
                <option value="2">Limpeza</option>
            </select>


            <label>Imagem:</label>
            <input type="file" name="imagem" accept="image/*"><br>

            <input type="submit" value="Cadastrar Produto">

        </form>
        <a href="./dashboard.php" class="btn-voltar">Voltar</a>

    </div>

</body>

</html>