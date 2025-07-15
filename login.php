<?php
session_start();
header('Content-Type: application/json');
require 'conexao.php'; // conexão PDO

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['erro' => 'Preencha todos os campos']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo json_encode(['erro' => 'E-mail não cadastrado']);
    exit;
}

if (!password_verify($senha, $usuario['senha'])) {
    echo json_encode(['erro' => 'Senha incorreta']);
    exit;
}

// Login bem-sucedido
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_email'] = $usuario['email'];

echo json_encode(['sucesso' => true]);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            overflow: hidden;
        }

        /* Folhas decorativas detalhadas no fundo */
        .leaf-bg {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            opacity: 0.8;
        }

        .leaf-bg-left {
            top: -10px;
            left: -20px;
            animation: leafFloat 8s ease-in-out infinite;
        }

        .leaf-bg-right {
            bottom: -30px;
            right: -10px;
            transform: scaleX(-1) scaleY(-1);
            animation: leafFloat 10s ease-in-out infinite reverse;
        }

        .leaf-bg-top {
            top: -20px;
            left: 50%;
            transform: translateX(-60%) scale(1.1);
            animation: leafSway 12s ease-in-out infinite;
        }

        .leaf-bg-bottom {
            bottom: -40px;
            left: 40%;
            transform: translateX(-40%) scale(1.1);
            animation: leafSway 9s ease-in-out infinite reverse;
        }

        .leaf-bg-center {
            top: 50%;
            left: 20%;
            transform: translateY(-50%) scale(0.7);
            animation: leafPulse 15s ease-in-out infinite;
        }

        .leaf-bg-center-right {
            top: 30%;
            right: 15%;
            transform: scale(0.6) rotate(45deg);
            animation: leafRotate 20s linear infinite;
        }

        .leaf-bg-small-1 {
            top: 20%;
            left: 80%;
            transform: scale(0.4);
            animation: leafFloat 6s ease-in-out infinite;
        }

        .leaf-bg-small-2 {
            bottom: 20%;
            left: 10%;
            transform: scale(0.3) rotate(-30deg);
            animation: leafSway 7s ease-in-out infinite;
        }
        
        .leaf-bg-small-3 {
            top: 15%;
            right: 25%;
            transform: scale(0.25) rotate(15deg);
            animation: leafFloat 9s ease-in-out infinite;
        }
        
        .leaf-bg-small-4 {
            bottom: 35%;
            right: 5%;
            transform: scale(0.2) rotate(-45deg);
            animation: leafSway 11s ease-in-out infinite reverse;
        }
        
        .leaf-bg-small-5 {
            top: 60%;
            left: 5%;
            transform: scale(0.18) rotate(60deg);
            animation: leafPulse 13s ease-in-out infinite;
        }
        
        .leaf-bg-small-6 {
            top: 25%;
            left: 35%;
            transform: scale(0.22) rotate(-20deg);
            animation: leafRotate 25s linear infinite;
        }
        
        .leaf-bg-small-7 {
            top: 45%;
            right: 35%;
            transform: scale(0.19) rotate(75deg);
            animation: leafFloat 14s ease-in-out infinite;
        }
        
        .leaf-bg-small-8 {
            bottom: 15%;
            left: 25%;
            transform: scale(0.16) rotate(-60deg);
            animation: leafSway 16s ease-in-out infinite reverse;
        }
        
        .leaf-bg-small-9 {
            top: 10%;
            left: 60%;
            transform: scale(0.21) rotate(30deg);
            animation: leafPulse 18s ease-in-out infinite;
        }
        
        .leaf-bg-small-10 {
            bottom: 45%;
            right: 20%;
            transform: scale(0.17) rotate(-15deg);
            animation: leafRotate 30s linear infinite reverse;
        }
        
        .leaf-bg-small-11 {
            top: 35%;
            left: 15%;
            transform: scale(0.14) rotate(45deg);
            animation: leafFloat 12s ease-in-out infinite;
        }
        
        .leaf-bg-small-12 {
            bottom: 25%;
            left: 45%;
            transform: scale(0.18) rotate(-75deg);
            animation: leafSway 20s ease-in-out infinite;
        }
        
        .leaf-bg-center-large {
            top: 45%;
            left: 45%;
            transform: scale(0.8) rotate(15deg);
            animation: leafPulse 25s ease-in-out infinite;
        }
        
        .leaf-bg-center-large-2 {
            top: 55%;
            right: 40%;
            transform: scale(0.7) rotate(-20deg);
            animation: leafSway 30s ease-in-out infinite;
        }
        
        .leaf-bg-center-large-3 {
            top: 40%;
            left: 55%;
            transform: scale(0.75) rotate(25deg);
            animation: leafFloat 28s ease-in-out infinite;
        }

        @keyframes leafFloat {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(3deg);
            }
        }

        @keyframes leafSway {

            0%,
            100% {
                transform: translateX(-60%) scale(1.1) rotate(0deg);
            }

            50% {
                transform: translateX(-60%) scale(1.1) rotate(2deg);
            }
        }

        @keyframes leafPulse {

            0%,
            100% {
                transform: translateY(-50%) scale(0.7);
                opacity: 0.6;
            }

            50% {
                transform: translateY(-50%) scale(0.75);
                opacity: 0.8;
            }
        }

        @keyframes leafRotate {
            0% {
                transform: scale(0.6) rotate(45deg);
            }

            100% {
                transform: scale(0.6) rotate(405deg);
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.04);
            padding: 40px 30px;
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.18);
            display: flex;
            flex-direction: column;
            align-items: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .login-logo {
            width: 110px;
            margin-bottom: 8px;
        }

        .login-title {
            color: #fff;
            font-size: 2.3rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 28px;
            text-align: center;
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        }

        .login-form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .login-input {
            padding: 13px 15px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
            outline: none;
            transition: box-shadow 0.2s, border 0.2s;
            box-shadow: 0 1px 4px 0 rgba(22, 101, 52, 0.08);
        }

        .login-input:focus {
            box-shadow: 0 0 0 2px #22c55e, 0 1px 4px 0 rgba(22, 101, 52, 0.10);
            border: 1px solid #22c55e;
        }

        .login-button {
            background: #166534;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px 0 rgba(22, 101, 52, 0.10);
        }

        .login-button:hover {
            background: linear-gradient(90deg, #14532d 60%, #16a34a 100%);
            transform: translateY(-2px) scale(1.03);
        }

        .cadastro-link {
            margin-top: 10px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
        }

        .cadastro-link a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .cadastro-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Folhas decorativas realistas -->
    <svg class="leaf-bg leaf-bg-left" width="320" height="400" viewBox="0 0 320 400" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha principal realista -->
        <path d="M280 380C120 340 40 220 80 80C140 -60 320 60 240 220C200 300 280 320 280 380Z" fill="url(#leafGradient1)" fill-opacity="0.3" />
        <!-- Nervura central principal -->
        <path d="M160 100L180 180L200 260L220 320" stroke="#4ade80" stroke-width="3" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M160 100L140 160L120 220L100 280" stroke="#4ade80" stroke-width="2" stroke-opacity="0.3" fill="none" stroke-linecap="round" />
        <path d="M160 100L200 140L240 180L260 240" stroke="#4ade80" stroke-width="2" stroke-opacity="0.3" fill="none" stroke-linecap="round" />
        <path d="M160 100L180 120L200 140L220 160" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.25" fill="none" stroke-linecap="round" />
        <path d="M160 100L140 120L120 140L100 160" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.25" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M80 120C90 110 100 115 95 125C90 135 85 130 80 120Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M200 160C210 150 220 155 215 165C210 175 205 170 200 160Z" fill="#22c55e" fill-opacity="0.08" />
        <path d="M120 200C130 190 140 195 135 205C130 215 125 210 120 200Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Folha secundária realista -->
        <path d="M140 280C80 240 60 180 100 140C140 100 220 140 200 200C190 230 160 240 140 280Z" fill="url(#leafGradient2)" fill-opacity="0.25" />
        <!-- Nervuras da folha secundária -->
        <path d="M150 160L170 200L180 240" stroke="#16a34a" stroke-width="2" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M150 160L160 180L170 200" stroke="#16a34a" stroke-width="1.5" stroke-opacity="0.3" fill="none" stroke-linecap="round" />
        <!-- Detalhes orgânicos realistas -->
        <ellipse cx="100" cy="80" rx="25" ry="12" fill="#bbf7d0" fill-opacity="0.2" />
        <ellipse cx="200" cy="120" rx="18" ry="8" fill="#6ee7b7" fill-opacity="0.25" />
        <ellipse cx="160" cy="300" rx="22" ry="10" fill="#bbf7d0" fill-opacity="0.18" />
        <!-- Pequenas folhas realistas -->
        <path d="M60 200C80 180 120 185 110 220C100 240 70 230 60 200Z" fill="#6ee7b7" fill-opacity="0.2" />
        <path d="M260 160C280 140 320 145 310 180C300 200 270 190 260 160Z" fill="#bbf7d0" fill-opacity="0.18" />
        <!-- Gradientes para realismo -->
        <defs>
            <linearGradient id="leafGradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.4" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.2" />
            </linearGradient>
            <linearGradient id="leafGradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.35" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.25" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.15" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-right" width="360" height="480" viewBox="0 0 360 480" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha principal realista -->
        <path d="M60 60C240 100 340 240 300 420C240 520 20 440 80 240C120 140 20 100 60 60Z" fill="url(#leafGradient3)" fill-opacity="0.28" />
        <!-- Nervura central principal -->
        <path d="M180 140L200 220L220 300L240 380" stroke="#4ade80" stroke-width="3.5" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M180 140L160 200L140 260L120 320" stroke="#4ade80" stroke-width="2.5" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M180 140L220 180L260 220L280 280" stroke="#4ade80" stroke-width="2.5" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M180 140L200 160L220 180L240 200" stroke="#4ade80" stroke-width="2" stroke-opacity="0.3" fill="none" stroke-linecap="round" />
        <path d="M180 140L160 160L140 180L120 200" stroke="#4ade80" stroke-width="2" stroke-opacity="0.3" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M100 180C110 170 120 175 115 185C110 195 105 190 100 180Z" fill="#22c55e" fill-opacity="0.12" />
        <path d="M240 220C250 210 260 215 255 225C250 235 245 230 240 220Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M160 280C170 270 180 275 175 285C170 295 165 290 160 280Z" fill="#22c55e" fill-opacity="0.15" />
        <!-- Folha secundária realista -->
        <path d="M240 380C280 320 220 240 160 260C100 280 160 380 240 380Z" fill="url(#leafGradient4)" fill-opacity="0.22" />
        <!-- Nervuras da folha secundária -->
        <path d="M200 280L220 320L230 360" stroke="#16a34a" stroke-width="2.5" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M200 280L210 300L220 320" stroke="#16a34a" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Elementos orgânicos realistas -->
        <ellipse cx="280" cy="140" rx="28" ry="14" fill="#bbf7d0" fill-opacity="0.18" />
        <ellipse cx="160" cy="200" rx="20" ry="10" fill="#6ee7b7" fill-opacity="0.22" />
        <ellipse cx="240" cy="320" rx="24" ry="12" fill="#bbf7d0" fill-opacity="0.2" />
        <!-- Folhas pequenas realistas -->
        <path d="M120 180C140 160 180 165 170 200C160 220 130 210 120 180Z" fill="#6ee7b7" fill-opacity="0.25" />
        <path d="M300 280C320 260 360 265 350 300C340 320 310 310 300 280Z" fill="#bbf7d0" fill-opacity="0.22" />
        <path d="M80 320C100 300 140 305 130 340C120 360 90 350 80 320Z" fill="#6ee7b7" fill-opacity="0.2" />
        <!-- Gradientes para realismo -->
        <defs>
            <linearGradient id="leafGradient3" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.45" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.35" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.25" />
            </linearGradient>
            <linearGradient id="leafGradient4" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.4" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.2" />
            </linearGradient>
        </defs>
    </svg>
    <svg class="leaf-bg leaf-bg-top" width="240" height="160" viewBox="0 0 240 160" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha horizontal superior realista -->
        <path d="M20 140C80 40 200 20 220 80C240 140 80 140 20 140Z" fill="url(#leafGradient5)" fill-opacity="0.2" />
        <!-- Nervura central -->
        <path d="M120 60L140 100L160 120" stroke="#4ade80" stroke-width="2" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M120 60L100 90L80 110" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.35" fill="none" stroke-linecap="round">
            <path d="M120 60L130 80L140 100" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
            <path d="M120 60L110 80L100 90" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.35" fill="none" stroke-linecap="round">
                <!-- Textura da folha -->
                <path d="M80 80C90 70 100 75 95 85C90 95 85 90 80 80Z" fill="#22c55e" fill-opacity="0.1" />
                <path d="M160 60C170 50 180 55 175 65C170 75 165 70 160 60Z" fill="#22c55e" fill-opacity="0.08" />
                <!-- Detalhes orgânicos -->
                <ellipse cx="180" cy="80" rx="15" ry="8" fill="#6ee7b7" fill-opacity="0.2" />
                <ellipse cx="60" cy="100" rx="12" ry="6" fill="#bbf7d0" fill-opacity="0.18" />
                <!-- Folha pequena realista -->
                <path d="M40 80C60 60 100 65 90 100C80 120 50 110 40 80Z" fill="#6ee7b7" fill-opacity="0.22" />
                <!-- Gradiente para realismo -->
                <defs>
                    <linearGradient id="leafGradient5" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.35" />
                        <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.25" />
                        <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.15" />
                    </linearGradient>
                </defs>
                g>
                    <svg class="leaf-bg leaf-bg-bottom" width="280" height="180" viewBox="0 0 280 180" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha horizontal inferior realista -->
        <path d="M260 60C180 160 40 180 20 120C0 60 160 60 260 60Z" fill="url(#leafGradient6)" fill-opacity="0.18" />
        <!-- Nervura central -->
        <path d="M140 100L160 140L180 160" stroke="#16a34a" stroke-width="2.5" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M140 100L120 130L100 150" stroke="#16a34a" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M140 100L150 120L160 140" stroke="#16a34a" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M140 100L130 120L120 130" stroke="#16a34a" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M80 100C90 90 100 95 95 105C90 115 85 110 80 100Z" fill="#22c55e" fill-opacity="0.12" />
        <path d="M200 80C210 70 220 75 215 85C210 95 205 90 200 80Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M120 140C130 130 140 135 135 145C130 155 125 150 120 140Z" fill="#22c55e" fill-opacity="0.15" />
        <!-- Detalhes orgânicos realistas -->
        <ellipse cx="80" cy="120" rx="18" ry="9" fill="#bbf7d0" fill-opacity="0.2" />
        <ellipse cx="200" cy="100" rx="16" ry="8" fill="#6ee7b7" fill-opacity="0.22" />
        <!-- Folhas pequenas realistas -->
        <path d="M220 100C240 80 280 85 270 120C260 140 230 130 220 100Z" fill="#bbf7d0" fill-opacity="0.18" />
        <path d="M60 120C80 100 120 105 110 140C100 160 70 150 60 120Z" fill="#6ee7b7" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient6" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.4" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.2" />
            </linearGradient>
        </defs>
    </svg>

                    <!-- Folhas centrais menores realistas -->
    <svg class="leaf-bg leaf-bg-center" width="200" height="240" viewBox="0 0 200 240" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha central realista -->
        <path d="M100 20C160 60 180 140 140 200C100 240 40 200 60 120C80 60 80 40 100 20Z" fill="url(#leafGradient7)" fill-opacity="0.22" />
        <!-- Nervura central -->
        <path d="M100 60L120 120L130 180" stroke="#4ade80" stroke-width="2.5" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M100 60L80 100L70 140" stroke="#4ade80" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M100 60L110 80L120 120" stroke="#4ade80" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M100 60L90 80L80 100" stroke="#4ade80" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M70 80C80 70 90 75 85 85C80 95 75 90 70 80Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M130 120C140 110 150 115 145 125C140 135 135 130 130 120Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="120" cy="100" rx="14" ry="7" fill="#6ee7b7" fill-opacity="0.22" />
        <ellipse cx="80" cy="160" rx="12" ry="6" fill="#bbf7d0" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient7" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.4" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.2" />
            </linearGradient>
        </defs>
    </svg>

                    <svg class="leaf-bg leaf-bg-center-right" width="160" height="200" viewBox="0 0 160 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha central direita realista -->
        <path d="M80 180C20 140 0 60 40 20C80 -20 160 20 120 100C100 140 120 160 80 180Z" fill="url(#leafGradient8)" fill-opacity="0.2" />
        <!-- Nervura central -->
        <path d="M80 60L100 100L110 140" stroke="#16a34a" stroke-width="2.5" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M80 60L90 80L100 100" stroke="#16a34a" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M80 60L70 80L60 100" stroke="#16a34a" stroke-width="2" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M60 80C70 70 80 75 75 85C70 95 65 90 60 80Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M100 60C110 50 120 55 115 65C110 75 105 70 100 60Z" fill="#22c55e" fill-opacity="0.08" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="100" cy="80" rx="12" ry="6" fill="#bbf7d0" fill-opacity="0.22" />
        <ellipse cx="60" cy="120" rx="10" ry="5" fill="#6ee7b7" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient8" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.4" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.2" />
            </linearGradient>
        </defs>
    </svg>

                    <!-- Folhas pequenas flutuantes realistas -->
    <svg class="leaf-bg leaf-bg-small-1" width="80" height="100" viewBox="0 0 80 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 1 realista -->
        <path d="M40 10C60 30 70 60 50 80C40 90 20 80 30 50C35 30 35 20 40 10Z" fill="url(#leafGradient9)" fill-opacity="0.25" />
        <!-- Nervura central -->
        <path d="M40 30L50 60L45 80" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M40 30L45 45L50 60" stroke="#4ade80" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M40 30L35 45L30 60" stroke="#4ade80" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M30 40C35 35 40 37 38 42C36 47 33 45 30 40Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="50" cy="50" rx="8" ry="4" fill="#6ee7b7" fill-opacity="0.25" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient9" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.5" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.4" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.3" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-2" width="60" height="80" viewBox="0 0 60 80" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 2 realista -->
        <path d="M30 70C10 50 0 20 20 10C30 0 50 10 40 40C35 55 35 65 30 70Z" fill="url(#leafGradient10)" fill-opacity="0.22" />
        <!-- Nervura central -->
        <path d="M30 20L40 40L35 60" stroke="#16a34a" stroke-width="1.5" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M30 20L35 30L40 40" stroke="#16a34a" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M30 20L25 30L20 40" stroke="#16a34a" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M20 30C25 25 30 27 28 32C26 37 23 35 20 30Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="35" cy="35" rx="6" ry="3" fill="#bbf7d0" fill-opacity="0.22" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient10" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.45" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.35" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.25" />
            </linearGradient>
        </defs>
    </svg>

    <!-- Folhas adicionais flutuantes -->
    <svg class="leaf-bg leaf-bg-small-3" width="70" height="90" viewBox="0 0 70 90" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 3 realista -->
        <path d="M35 15C50 25 60 45 45 65C35 75 20 65 25 40C30 25 30 20 35 15Z" fill="url(#leafGradient11)" fill-opacity="0.28" />
        <!-- Nervura central -->
        <path d="M35 25L40 45L38 65" stroke="#4ade80" stroke-width="1.5" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M35 25L38 35L40 45" stroke="#4ade80" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M35 25L32 35L30 45" stroke="#4ade80" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M30 35C33 32 36 34 35 37C34 40 31 38 30 35Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="40" cy="40" rx="7" ry="4" fill="#6ee7b7" fill-opacity="0.25" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient11" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.55" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.45" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.35" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-4" width="50" height="70" viewBox="0 0 50 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 4 realista -->
        <path d="M25 60C15 45 10 25 20 15C25 10 35 15 30 35C28 45 30 50 25 60Z" fill="url(#leafGradient12)" fill-opacity="0.24" />
        <!-- Nervura central -->
        <path d="M25 20L28 35L27 50" stroke="#16a34a" stroke-width="1.5" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M25 20L27 25L28 35" stroke="#16a34a" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M25 20L23 25L22 35" stroke="#16a34a" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M22 25C24 23 26 24 25 26C24 28 22 27 22 25Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="28" cy="30" rx="5" ry="3" fill="#bbf7d0" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient12" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.5" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.4" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.3" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-5" width="40" height="60" viewBox="0 0 40 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 5 realista -->
        <path d="M20 50C12 40 8 25 15 15C20 10 28 15 25 30C23 40 25 45 20 50Z" fill="url(#leafGradient13)" fill-opacity="0.26" />
        <!-- Nervura central -->
        <path d="M20 18L22 30L21 45" stroke="#4ade80" stroke-width="1.2" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M20 18L21 22L22 30" stroke="#4ade80" stroke-width="0.8" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M20 18L19 22L18 30" stroke="#4ade80" stroke-width="0.8" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M18 22C19 21 20 21.5 19.5 23C19 24.5 18.5 24 18 22Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="22" cy="25" rx="4" ry="2" fill="#6ee7b7" fill-opacity="0.22" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient13" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.5" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.4" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.3" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-6" width="55" height="75" viewBox="0 0 55 75" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 6 realista -->
        <path d="M27.5 65C20 50 15 30 25 20C27.5 17 35 20 32 40C30 50 32 55 27.5 65Z" fill="url(#leafGradient14)" fill-opacity="0.23" />
        <!-- Nervura central -->
        <path d="M27.5 25L30 40L29 55" stroke="#16a34a" stroke-width="1.3" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M27.5 25L29 30L30 40" stroke="#16a34a" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M27.5 25L26 30L25 40" stroke="#16a34a" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M25 30C26.5 28 28 28.5 27.5 30.5C27 32.5 26 32 25 30Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="30" cy="35" rx="5" ry="3" fill="#bbf7d0" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient14" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.48" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.38" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.28" />
            </linearGradient>
        </defs>
    </svg>

    <!-- Folhas adicionais extras -->
    <svg class="leaf-bg leaf-bg-small-7" width="45" height="65" viewBox="0 0 45 65" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 7 realista -->
        <path d="M22.5 55C16 45 12 30 20 20C22.5 17 28 20 26 35C24 45 26 50 22.5 55Z" fill="url(#leafGradient15)" fill-opacity="0.25" />
        <!-- Nervura central -->
        <path d="M22.5 22L24 35L23 50" stroke="#4ade80" stroke-width="1.2" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M22.5 22L23 27L24 35" stroke="#4ade80" stroke-width="0.9" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M22.5 22L22 27L21 35" stroke="#4ade80" stroke-width="0.9" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M21 27C22 25.5 23 26 22.5 27.5C22 29 21.5 28.5 21 27Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="24" cy="30" rx="4" ry="2.5" fill="#6ee7b7" fill-opacity="0.22" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient15" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.52" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.42" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.32" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-8" width="35" height="55" viewBox="0 0 35 55" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 8 realista -->
        <path d="M17.5 45C12 35 9 25 15 18C17.5 15 22 18 20 30C18 35 20 40 17.5 45Z" fill="url(#leafGradient16)" fill-opacity="0.24" />
        <!-- Nervura central -->
        <path d="M17.5 20L19 30L18 40" stroke="#16a34a" stroke-width="1" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M17.5 20L18 24L19 30" stroke="#16a34a" stroke-width="0.7" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M17.5 20L17 24L16 30" stroke="#16a34a" stroke-width="0.7" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M16 24C16.8 23 17.5 23.3 17.2 24.5C16.9 25.7 16.3 25.3 16 24Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="19" cy="25" rx="3" ry="2" fill="#bbf7d0" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient16" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.5" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.4" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.3" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-9" width="50" height="70" viewBox="0 0 50 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 9 realista -->
        <path d="M25 60C18 50 15 35 22 25C25 22 30 25 28 40C26 50 28 55 25 60Z" fill="url(#leafGradient17)" fill-opacity="0.26" />
        <!-- Nervura central -->
        <path d="M25 27L27 40L26 55" stroke="#4ade80" stroke-width="1.3" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M25 27L26 32L27 40" stroke="#4ade80" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M25 27L24 32L23 40" stroke="#4ade80" stroke-width="1" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M23 32C24.2 30.5 25 31 24.5 32.5C24 34 23.3 33.5 23 32Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="27" cy="35" rx="4.5" ry="3" fill="#6ee7b7" fill-opacity="0.23" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient17" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.54" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.44" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.34" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-10" width="40" height="60" viewBox="0 0 40 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 10 realista -->
        <path d="M20 50C14 40 11 30 17 20C20 17 25 20 23 35C21 40 23 45 20 50Z" fill="url(#leafGradient18)" fill-opacity="0.23" />
        <!-- Nervura central -->
        <path d="M20 22L22 35L21 45" stroke="#16a34a" stroke-width="1.1" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M20 22L21 27L22 35" stroke="#16a34a" stroke-width="0.8" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M20 22L19 27L18 35" stroke="#16a34a" stroke-width="0.8" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M18 27C19 25.5 20 26 19.5 27.5C19 29 18.3 28.5 18 27Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="22" cy="30" rx="3.5" ry="2.5" fill="#bbf7d0" fill-opacity="0.21" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient18" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.48" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.38" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.28" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-11" width="30" height="45" viewBox="0 0 30 45" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 11 realista -->
        <path d="M15 38C10 30 8 22 13 15C15 12 19 15 17 28C16 32 17 36 15 38Z" fill="url(#leafGradient19)" fill-opacity="0.25" />
        <!-- Nervura central -->
        <path d="M15 17L16 28L15 35" stroke="#4ade80" stroke-width="0.9" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M15 17L15.5 21L16 28" stroke="#4ade80" stroke-width="0.6" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M15 17L14.5 21L14 28" stroke="#4ade80" stroke-width="0.6" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M14 21C14.6 20 15 20.3 14.7 21.5C14.4 22.7 13.8 22.3 14 21Z" fill="#22c55e" fill-opacity="0.1" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="16" cy="22" rx="2.5" ry="1.8" fill="#6ee7b7" fill-opacity="0.22" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient19" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.53" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.43" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.33" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-small-12" width="42" height="58" viewBox="0 0 42 58" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha pequena 12 realista -->
        <path d="M21 48C15 38 12 28 18 18C21 15 26 18 24 33C22 38 24 43 21 48Z" fill="url(#leafGradient20)" fill-opacity="0.24" />
        <!-- Nervura central -->
        <path d="M21 20L23 33L22 43" stroke="#16a34a" stroke-width="1.2" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias -->
        <path d="M21 20L22 25L23 33" stroke="#16a34a" stroke-width="0.9" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M21 20L20 25L19 33" stroke="#16a34a" stroke-width="0.9" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M19 25C20 23.5 21 24 20.5 25.5C20 27 19.3 26.5 19 25Z" fill="#22c55e" fill-opacity="0.11" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="23" cy="28" rx="3.8" ry="2.8" fill="#bbf7d0" fill-opacity="0.21" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient20" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.49" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.39" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.29" />
            </linearGradient>
        </defs>
    </svg>

    <!-- Folhas grandes próximas ao centro -->
    <svg class="leaf-bg leaf-bg-center-large" width="350" height="450" viewBox="0 0 350 450" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha grande 1 próxima ao centro -->
        <path d="M175 40C250 65 300 170 280 300C250 400 90 350 130 170C155 100 100 70 175 40Z" fill="url(#leafGradient21)" fill-opacity="0.18" />
        <!-- Nervura central principal -->
        <path d="M175 70L190 150L205 230L215 320" stroke="#4ade80" stroke-width="3.5" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias principais -->
        <path d="M175 70L160 130L145 190L130 250" stroke="#4ade80" stroke-width="2.5" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M175 70L210 130L245 190L270 250" stroke="#4ade80" stroke-width="2.5" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras terciárias -->
        <path d="M175 70L185 100L195 130" stroke="#4ade80" stroke-width="1.8" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M175 70L165 100L155 130" stroke="#4ade80" stroke-width="1.8" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M110 100C125 85 140 90 135 120C130 140 120 135 110 100Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M240 150C255 135 270 140 265 170C260 190 250 185 240 150Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="270" cy="100" rx="30" ry="15" fill="#bbf7d0" fill-opacity="0.18" />
        <ellipse cx="140" cy="150" rx="22" ry="11" fill="#6ee7b7" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient21" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.4" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.3" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.2" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-center-large-2" width="300" height="400" viewBox="0 0 300 400" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha grande 2 próxima ao centro -->
        <path d="M150 30C220 55 260 150 240 270C210 350 70 300 100 150C120 90 80 60 150 30Z" fill="url(#leafGradient22)" fill-opacity="0.16" />
        <!-- Nervura central principal -->
        <path d="M150 60L165 130L180 200L190 280" stroke="#16a34a" stroke-width="3" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias principais -->
        <path d="M150 60L135 110L120 160L105 210" stroke="#16a34a" stroke-width="2.2" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M150 60L175 110L200 160L225 210" stroke="#16a34a" stroke-width="2.2" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras terciárias -->
        <path d="M150 60L160 85L170 110" stroke="#16a34a" stroke-width="1.5" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M150 60L140 85L130 110" stroke="#16a34a" stroke-width="1.5" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M100 80C115 65 130 70 125 100C120 120 110 115 100 80Z" fill="#22c55e" fill-opacity="0.09" />
        <path d="M200 120C215 105 230 110 225 140C220 160 210 155 200 120Z" fill="#22c55e" fill-opacity="0.11" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="230" cy="80" rx="25" ry="12" fill="#bbf7d0" fill-opacity="0.17" />
        <ellipse cx="120" cy="130" rx="18" ry="9" fill="#6ee7b7" fill-opacity="0.19" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient22" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#6ee7b7;stop-opacity:0.38" />
                <stop offset="50%" style="stop-color:#34d399;stop-opacity:0.28" />
                <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.18" />
            </linearGradient>
        </defs>
    </svg>

    <svg class="leaf-bg leaf-bg-center-large-3" width="320" height="420" viewBox="0 0 320 420" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Folha grande 3 próxima ao centro -->
        <path d="M160 35C230 60 270 160 250 280C220 360 80 310 110 160C130 95 90 65 160 35Z" fill="url(#leafGradient23)" fill-opacity="0.17" />
        <!-- Nervura central principal -->
        <path d="M160 65L175 135L190 205L200 285" stroke="#4ade80" stroke-width="3.2" stroke-opacity="0.45" fill="none" stroke-linecap="round" />
        <!-- Nervuras secundárias principais -->
        <path d="M160 65L145 115L130 165L115 215" stroke="#4ade80" stroke-width="2.4" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <path d="M160 65L185 115L210 165L235 215" stroke="#4ade80" stroke-width="2.4" stroke-opacity="0.4" fill="none" stroke-linecap="round" />
        <!-- Nervuras terciárias -->
        <path d="M160 65L170 90L180 115" stroke="#4ade80" stroke-width="1.6" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <path d="M160 65L150 90L140 115" stroke="#4ade80" stroke-width="1.6" stroke-opacity="0.35" fill="none" stroke-linecap="round" />
        <!-- Textura da folha -->
        <path d="M110 85C125 70 140 75 135 105C130 125 120 120 110 85Z" fill="#22c55e" fill-opacity="0.1" />
        <path d="M210 130C225 115 240 120 235 150C230 170 220 165 210 130Z" fill="#22c55e" fill-opacity="0.12" />
        <!-- Detalhes orgânicos -->
        <ellipse cx="240" cy="85" rx="28" ry="14" fill="#bbf7d0" fill-opacity="0.18" />
        <ellipse cx="130" cy="140" rx="20" ry="10" fill="#6ee7b7" fill-opacity="0.2" />
        <!-- Gradiente para realismo -->
        <defs>
            <linearGradient id="leafGradient23" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#bbf7d0;stop-opacity:0.39" />
                <stop offset="50%" style="stop-color:#86efac;stop-opacity:0.29" />
                <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.19" />
            </linearGradient>
        </defs>
    </svg>

                <div class="login-container">
                    <img src="assets/logo.png" alt="Eco Market Logo" class="login-logo">
                    <div class="login-title">Bem<br>Vindo!</div>
                    <form class="login-form" action="login.php" method="post">
                        <label for="email" style="color:#fff;font-weight:500;margin-bottom:2px;">Usuário</label>
                        <input type="email" name="email" id="email" class="login-input" placeholder="E-mail" required>
                        <label for="senha" style="color:#fff;font-weight:500;margin-bottom:2px;">Senha</label>
                        <input type="password" name="senha" id="senha" class="login-input" placeholder="Senha" required>
                        <button type="submit" class="login-button">Entrar</button>
                        <div class="cadastro-link">
                            <a href="cadastrarUsuario.php">Não tem uma conta? Cadastre-se</a>
                        </div>
                        
                    </form>
                </div>
</body>

</html>