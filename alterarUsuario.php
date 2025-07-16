<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './classes/DataBase.php';
require_once './classes/Usuario.php';

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: Usuario.php');
    exit();
}

$db = (new DataBase())->getConnection();
$usuario = new Usuario($db);
$mensagem = '';

// Carrega dados do usuário atual
$dadosUsuario = $usuario->buscarPorId($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    
    // Validação do telefone
    if (!preg_match('/^\d{10,11}$/', $telefone)) {
        $mensagem = "<div style='color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px; text-align: center;'>O telefone deve conter apenas números e ter 10 ou 11 dígitos.</div>";
    } else {
        // Verifica se quer alterar a senha
        if (!empty($nova_senha)) {
            // Valida a nova senha
            if (
                strlen($nova_senha) < 8 ||
                !preg_match('/[A-Z]/', $nova_senha) ||
                !preg_match('/[\W_]/', $nova_senha)
            ) {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px; text-align: center;'>A nova senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.</div>";
            } else if (!$usuario->verificarSenha($_SESSION['usuario_id'], $senha_atual)) {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px; text-align: center;'>Senha atual incorreta.</div>";
            } else {
                // Atualiza com nova senha
                if ($usuario->atualizar($_SESSION['usuario_id'], $nome, $email, $telefone, $nova_senha)) {
                    $mensagem = "<div style='color: #388e3c; margin-bottom: 15px; padding: 10px; background-color: #e8f5e9; border-radius: 5px; text-align: center;'>Dados atualizados com sucesso!</div>";
                    $dadosUsuario = $usuario->buscarPorId($_SESSION['usuario_id']); // Atualiza dados locais
                } else {
                    $mensagem = "<div style='color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px; text-align: center;'>Erro ao atualizar dados.</div>";
                }
            }
        } else {
            // Atualiza sem alterar senha
            if ($usuario->atualizar($_SESSION['usuario_id'], $nome, $email, $telefone)) {
                $mensagem = "<div style='color: #388e3c; margin-bottom: 15px; padding: 10px; background-color: #e8f5e9; border-radius: 5px; text-align: center;'>Dados atualizados com sucesso!</div>";
                $dadosUsuario = $usuario->buscarPorId($_SESSION['usuario_id']); // Atualiza dados locais
            } else {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 15px; padding: 10px; background-color: #ffebee; border-radius: 5px; text-align: center;'>Erro ao atualizar dados.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - EcoMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #eafaf1;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .card-alterar {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 30px 25px 20px 25px;
            max-width: 350px;
            margin: 60px auto;
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
        .form-control {
            border-radius: 5px;
            border: 1px solid #b2dfdb;
            margin-bottom: 15px;
            padding: 10px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }
        .btn-alterar {
            background: #219150;
            color: #fff;
            border: none;
            border-radius: 5px;
            width: 90%;
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin: 10px auto;
            display: block;
            transition: background 0.2s;
        }
        .btn-alterar:hover {
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
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
        .senha-section h3 {
            margin-bottom: 15px;
            color: #219150;
            font-size: 1rem;
            text-align: center;
        }
        .senha-section small {
            display: block;
            margin-bottom: 10px;
            color: #666;
            text-align: center;
            font-size: 0.85rem;
        }
        #erro-telefone {
            color: #d32f2f;
            width: 90%;
            margin: 0 auto 15px auto;
            text-align: left;
            font-size: 0.95rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="card-alterar">
        <div class="eco-logo">
            <img src="assets/logo/logoEco.png" alt="EcoMarket Logo">
        </div>
        <?= $mensagem ?>
        <form method="POST" id="form-alterar">
            <input type="text" class="form-control" name="nome" placeholder="Nome" value="<?= htmlspecialchars($dadosUsuario['nome'] ?? '') ?>" required>
            <input type="email" class="form-control" name="email" placeholder="E-mail" value="<?= htmlspecialchars($dadosUsuario['email'] ?? '') ?>" required>
            <input type="tel" class="form-control" name="telefone" placeholder="Telefone" value="<?= htmlspecialchars($dadosUsuario['telefone'] ?? '') ?>" maxlength="11" required>
            <div id="erro-telefone">O telefone deve conter apenas números e ter 10 ou 11 dígitos.</div>
            
            <div class="senha-section">
                <h3>Alterar Senha</h3>
                <input type="password" class="form-control" name="senha_atual" placeholder="Senha Atual">
                <input type="password" class="form-control" name="nova_senha" placeholder="Nova Senha">
                <small>Deixe em branco se não quiser alterar a senha</small>
            </div>
            
            <button type="submit" class="btn-alterar">Salvar Alterações</button>
            <a href="perfil.php" class="voltar-link">Voltar ao perfil</a>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-alterar');
            const erroTelefone = document.getElementById('erro-telefone');
            const campoTelefone = form.querySelector('input[name="telefone"]');
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                const telefone = campoTelefone.value;
                const novaSenha = form.querySelector('input[name="nova_senha"]').value;
                let erro = false;
                
                // Validação do telefone
                if (!/^\d{10,11}$/.test(telefone)) {
                    erroTelefone.style.display = 'block';
                    erro = true;
                } else {
                    erroTelefone.style.display = 'none';
                }
                
                // Validação da senha (se foi preenchida)
                if (novaSenha.trim() !== '') {
                    const regexMaiuscula = /[A-Z]/;
                    const regexEspecial = /[\W_]/;
                    
                    if (
                        novaSenha.length < 8 ||
                        !regexMaiuscula.test(novaSenha) ||
                        !regexEspecial.test(novaSenha)
                    ) {
                        alert('A nova senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um caractere especial.');
                        erro = true;
                    }
                }
                
                if (erro) {
                    e.preventDefault();
                }
            });
            
            // Validação do telefone em tempo real
            campoTelefone.addEventListener('input', function() {
                // Permite apenas números
                if (!/^\d*$/.test(this.value)) {
                    this.value = this.value.replace(/\D/g, '');
                }
                // Limita a 11 caracteres
                if (this.value.length > 11) {
                    this.value = this.value.slice(0, 11);
                }
            });
        });
    </script>
</body>
</html>