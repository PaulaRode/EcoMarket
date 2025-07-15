<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/config.php'; //  tem que ajustar o caminho do banco 

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validação da senha
    if (
        strlen($senha) < 8 ||
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[\W_]/', $senha)
    ) {
        $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>A senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.</div>";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        // Verifica se o e-mail já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>E-mail já cadastrado.</div>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            if ($stmt->execute([$nome, $email, $senhaHash])) {
                header('Location: index.php');
                exit;
            } else {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Erro ao cadastrar.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro - EcoMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles/cadastrarUsuario.css">
    <style>
        body {
            background: #eafaf1;
            font-family: Arial, sans-serif;
        }

        .eco-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .eco-logo img {
            width: 110px;
            margin-bottom: 10px;
        }

        .eco-logo .eco-title {
            color: #219150;
            font-size: 2rem;
            font-weight: bold;
            line-height: 1;
        }

        .card-cadastro {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 30px 25px 20px 25px;
            max-width: 350px;
            margin: 60px auto;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #b2dfdb;
            margin-bottom: 15px;
            padding: 10px;
        }

        .btn-cadastrar {
            background: #219150;
            color: #fff;
            border: none;
            border-radius: 5px;
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .btn-cadastrar:hover {
            background: #176c3a;
        }
    </style>
</head>

<body>
    <div class="card-cadastro">
        <div class="eco-logo">
            <img src="assets/logo/logoEco.png" alt="EcoMarket Logo">
        </div>
        <?= $mensagem ?>
        <form method="POST" style="display: flex; flex-direction: column; align-items: center;">
            <input type="text" class="form-control" name="nome" placeholder="Nome" required
                style="width: 90%; margin: 8px 0;">
            <input type="email" class="form-control" name="email" placeholder="E-mail" required
                style="width: 90%; margin: 8px 0;">
            <input type="password" class="form-control" name="senha" placeholder="Senha" required
                style="width: 90%; margin: 8px 0;">
            <button type="submit" class="btn-cadastrar" style="width: 90%;">Cadastrar</button>
        </form>
    </div>
    <script>
        // Validação de senha no front-end
        document.querySelector('form').addEventListener('submit', function (e) {
            const senha = document.querySelector('input[name="senha"]').value;
            const regexMaiuscula = /[A-Z]/;
            const regexEspecial = /[\W_]/;
            if (
                senha.length < 8 ||
                !regexMaiuscula.test(senha) ||
                !regexEspecial.test(senha)
            ) {
                alert('A senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>