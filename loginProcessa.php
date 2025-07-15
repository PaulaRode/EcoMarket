<?php
session_start();
header('Content-Type: application/json');
require 'config/config.php'; // conexão PDO

// Se a requisição não for POST, retorna erro apropriado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

// Coletar dados enviados por POST
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['erro' => 'Preencha todos os campos']);
    exit;
}

// Buscar usuário no banco
$stmt = $pdo->prepare("SELECT * FROM tbusu WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo json_encode(['erro' => 'E-mail não cadastrado']);
    exit;
}

// Comparar senha (ideal seria usar password_hash + password_verify)
if ($senha != $usuario['senha']) {
    echo json_encode(['erro' => 'Senha incorreta']);
    exit;
}

// Login bem-sucedido
$_SESSION['id'] = $usuario['id'];
$_SESSION['nome'] = $usuario['nome'];
$_SESSION['email'] = $usuario['email'];

echo json_encode(['sucesso' => true]);
?> 