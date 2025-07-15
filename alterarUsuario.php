<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/config.php'; // ajustar o caminho do banco

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$mensagem = '';
$usuario = null;

// Carrega dados do usuário
$stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Verifica se o e-mail foi alterado
    if ($email !== $usuario['email']) {
        // Verifica se o novo e-mail já existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $usuario['id']]);
        if ($stmt->fetch()) {
            $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>E-mail já cadastrado por outro usuário.</div>";
        }
    }

    // Se não houve erro com o e-mail, prossegue
    if (empty($mensagem)) {
        // Verifica se quer alterar a senha
        if (!empty($nova_senha)) {
            // Valida a senha atual
            $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
            $stmt->execute([$usuario['id']]);
            $usuario_db = $stmt->fetch();
            
            if (!password_verify($senha_atual, $usuario_db['senha'])) {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Senha atual incorreta.</div>";
            } 
            // Validação da nova senha
            elseif (
                strlen($nova_senha) < 8 ||
                !preg_match('/[A-Z]/', $nova_senha) ||
                !preg_match('/[\W_]/', $nova_senha)
            ) {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>A nova senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.</div>";
            } elseif ($nova_senha !== $confirmar_senha) {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>As novas senhas não coincidem.</div>";
            } else {
                $senhaHash = password_hash($nova_senha, PASSWORD_BCRYPT);
                $atualizarSenha = true;
            }
        }

        // Se não houve erros, atualiza os dados
        if (empty($mensagem)) {
            if (isset($atualizarSenha)) {
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $result = $stmt->execute([$nome, $email, $senhaHash, $usuario['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $result = $stmt->execute([$nome, $email, $usuario['id']]);
            }

            if ($result) {
                $mensagem = "<div style='color: #388e3c; margin-bottom: 10px;'>Dados atualizados com sucesso!</div>";
                // Atualiza os dados na sessão
                $_SESSION['usuario_nome'] = $nome;
                // Recarrega os dados do usuário
                $stmt = $pdo->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
                $stmt->execute([$_SESSION['usuario_id']]);
                $usuario = $stmt->fetch();
            } else {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Erro ao atualizar dados.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Dados - EcoMarket</title>
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
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
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
        .voltar-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #219150;
            text-decoration: none;
        }
        .voltar-link:hover {
            text-decoration: underline;
        }
        .senha-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="card-cadastro">
        <div class="eco-logo">
            <img src="assets/logo/eco logo.jpeg" alt="EcoMarket Logo">
            
        </div>
        <?= $mensagem ?>
        <form method="POST" style="display: flex; flex-direction: column; align-items: center;">
            <input type="text" class="form-control" name="nome" placeholder="Nome" value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>" required style="width: 90%; margin: 8px 0;">
            <input type="email" class="form-control" name="email" placeholder="E-mail" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required style="width: 90%; margin: 8px 0;">
            
            <div class="senha-section" style="width: 90%;">
                <h3 style="margin-bottom: 15px; color: #219150; font-size: 1rem;">Alterar Senha</h3>
                <input type="password" class="form-control" name="senha_atual" placeholder="Senha Atual" style="width: 100%; margin: 8px 0;">
                <input type="password" class="form-control" name="nova_senha" placeholder="Nova Senha" style="width: 100%; margin: 8px 0;">
                <input type="password" class="form-control" name="confirmar_senha" placeholder="Confirmar Nova Senha" style="width: 100%; margin: 8px 0;">
                <small style="display: block; margin-bottom: 10px; color: #666;">Deixe em branco se não quiser alterar</small>
            </div>
            
            <button type="submit" class="btn-cadastrar" style="width: 90%;">Salvar Alterações</button>
            <a href="perfil.php" class="voltar-link">Voltar ao perfil</a>
        </form>
    </div>
    <script>
        // Validação de senha no front-end
        document.querySelector('form').addEventListener('submit', function (e) {
            const novaSenha = document.querySelector('input[name="nova_senha"]').value;
            const confirmarSenha = document.querySelector('input[name="confirmar_senha"]').value;
            
            // Só valida se a nova senha foi preenchida
            if (novaSenha.trim() !== '') {
                const regexMaiuscula = /[A-Z]/;
                const regexEspecial = /[\W_]/;
                
                if (
                    novaSenha.length < 8 ||
                    !regexMaiuscula.test(novaSenha) ||
                    !regexEspecial.test(novaSenha)
                ) {
                    alert('A nova senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.');
                    e.preventDefault();
                    return;
                }
                
                if (novaSenha !== confirmarSenha) {
                    alert('As novas senhas não coincidem.');
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>