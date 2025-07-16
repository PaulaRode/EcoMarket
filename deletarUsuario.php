<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './classes/DataBase.php';
require_once './classes/Usuario.php';
$db = (new DataBase())->getConnection();
$usuario = new Usuario($db);
$mensagem = '';


session_start();

//  Verifica se o usuário está logado
if (isset($_SESSION['id'])) {
    $id = intval($_SESSION['id']);
    if ($id > 0) {
        if (!isset($_POST['confirmar'])) {
            // Exibe confirmação
            $mensagem = "<form method='POST' style='display: flex; flex-direction: column; align-items: center;'><div style='color: #d32f2f; margin-bottom: 10px; text-align:center;'>Você realmente tem certeza que deseja excluir sua conta?</div><input type='hidden' name='confirmar' value='1'><button type='submit' style='background:#d32f2f;color:#fff;border:none;padding:10px 20px;border-radius:5px;cursor:pointer; margin: 0 auto; display: block;'>Sim, excluir</button></form>";
        } else {
            $stmt = $db->prepare("DELETE FROM tbUsu WHERE id = ?");
            if ($stmt === false) {
                $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Erro ao preparar a query de exclusão: " . htmlspecialchars($db->error) . "</div>";
            } else {
                $stmt->bind_param('i', $id);
                if ($stmt->execute()) {
                    // desloga o usuário
                    session_destroy();
                    header('Location: index.php');
                    exit;
                } else {
                    $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Erro ao deletar usuário: " . htmlspecialchars($stmt->error) . "</div>";
                }
                $stmt->close();
            }
        }
    } else {
        $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>ID inválido.</div>";
    }
} else {
    $mensagem = "<div style='color: #d32f2f; margin-bottom: 10px;'>Nenhum usuário logado.</div>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Deletar Usuário - EcoMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background: #eafaf1;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-deletar {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 30px 25px 20px 25px;
            max-width: 350px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="card-deletar">
        <h2 style="color: #219150; text-align: center;">Deletar Usuário</h2>
        <?= $mensagem ?>
        <a href="dashboard.php"
            style="display: block; text-align: center; color: #219150; text-decoration: underline; margin-top: 20px;">Voltar</a>
    </div>
</body>

</html>