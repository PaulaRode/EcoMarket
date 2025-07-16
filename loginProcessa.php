<?php
session_start();
header('Content-Type: application/json');
require 'config/config.php'; // Isso traz $conn

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

// Query preparada usando MySQLi
$stmt = $conn->prepare("SELECT * FROM tbusu WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    echo json_encode(['erro' => 'E-mail não cadastrado']);
    exit;
}

// Confirma a senha usando password_verify
// if (!password_verify($senha, $usuario['senha'])) {
//     echo json_encode(['erro' => 'Senha incorreta']);
//     exit;
// }

// Login OK!
$_SESSION['id'] = $usuario['id'];
$_SESSION['nome'] = $usuario['nome'];
$_SESSION['email'] = $usuario['email'];

echo json_encode(['sucesso' => true]);
exit;
