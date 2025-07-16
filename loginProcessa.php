<?php
// Desabilita exibição de erros para não interferir com o JSON
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

try {
    require 'config/config.php'; // Isso traz $conn
} catch (Exception $e) {
    echo json_encode(['erro' => 'Erro na configuração: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['erro' => 'Preencha todos os campos']);
    exit;
}

try {
    // Query preparada usando PDO
    $stmt = $conn->prepare("SELECT * FROM tbUsu WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo json_encode(['erro' => 'E-mail não cadastrado']);
        exit;
    }

    // Confirma a senha - comentado para desenvolvimento
    // if ($senha != $usuario['senha'] && !password_verify($senha, $usuario['senha'])) {
    //     echo json_encode(['erro' => 'Senha incorreta']);
    //     exit;
    // }

    // Login OK!
    $_SESSION['id'] = $usuario['id'];
    $_SESSION['nome'] = $usuario['nome'];
    $_SESSION['email'] = $usuario['email'];

    echo json_encode(['sucesso' => true, 'redirect' => 'dashboard.php']);
    exit;
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
    exit;
}
