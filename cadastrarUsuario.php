<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './classes/DataBase.php';
require_once './classes/Usuario.php';
$db = (new DataBase())->getConnection();
$usuario = new Usuario($db);
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $senha = $_POST['senha'];
    // Validação da senha
    if (
        strlen($senha) < 8 ||
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[\W_]/', $senha)
    ) {
        $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>A senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.</div>";
    } else {
        if (!preg_match('/^\d{10,11}$/', $telefone)) {
            $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>O telefone deve conter apenas números e ter 10 ou 11 dígitos.</div>";
        } else if ($usuario->criar($nome, $email, $senha, $telefone)) {
            header('Location: index.php');
            exit;
        } else {
            $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Erro ao cadastrar.</div>";
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
        <form method="POST" style="display: flex; flex-direction: column; align-items: center;" id="form-cadastro">
            <input type="text" class="form-control" name="nome" placeholder="Nome" required
                style="width: 90%; margin: 8px 0;">
            <input type="email" class="form-control" name="email" placeholder="E-mail" required
                style="width: 90%; margin: 8px 0;">
            <input type="tel" class="form-control" name="telefone" placeholder="Telefone" maxlength="11" required
                style="width: 90%; margin: 8px 0;">
            <div id="erro-telefone"
                style="color: #d32f2f; font-size: 0.95rem; margin-bottom: 8px; display: none; width: 90%; text-align: left;">
                O telefone deve conter apenas números e ter 10 ou 11 dígitos.</div>
            <input type="password" class="form-control" name="senha" placeholder="Senha" required
                style="width: 90%; margin: 8px 0;">
            <button type="submit" class="btn-cadastrar" style="width: 90%;">Cadastrar</button>
        </form>
    </div>
    <script>
        // Validação de senha e telefone no front
        document.getElementById('form-cadastro').addEventListener('submit', function (e) {
            const senha = document.querySelector('input[name="senha"]').value;
            const telefone = document.querySelector('input[name="telefone"]').value;
            const erroTelefone = document.getElementById('erro-telefone');
            const regexMaiuscula = /[A-Z]/;
            const regexEspecial = /[\W_]/;
            let erro = false;
            erroTelefone.style.display = 'none';
            if (
                senha.length < 8 ||
                !regexMaiuscula.test(senha) ||
                !regexEspecial.test(senha)
            ) {
                alert('A senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.');
                e.preventDefault();
                erro = true;
            }
            if (!/^\d{10,11}$/.test(telefone)) {
                erroTelefone.style.display = 'block';
                e.preventDefault();
                erro = true;
            }
            if (!erro) {
                erroTelefone.style.display = 'none';
            }
        });
    </script>
</body>

</html>