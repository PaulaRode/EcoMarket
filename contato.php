<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Contato - EcoMarket</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 60px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 32px 24px; }
        h1 { color: #2e7d32; text-align: center; margin-bottom: 24px; }
        form { display: flex; flex-direction: column; gap: 16px; }
        label { font-weight: bold; color: #388e3c; }
        input, textarea { padding: 8px; border-radius: 4px; border: 1px solid #bdbdbd; font-size: 1em; }
        button { background: #43a047; color: #fff; border: none; border-radius: 4px; padding: 10px; font-size: 1em; font-weight: bold; cursor: pointer; transition: background 0.2s; }
        button:hover { background: #2e7d32; }
        .info { margin-top: 24px; color: #444; font-size: 1.05em; }
        .voltar { display: block; margin-top: 24px; text-align: center; color: #388e3c; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contato</h1>
        <form>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            <label for="mensagem">Mensagem:</label>
            <textarea id="mensagem" name="mensagem" rows="4" placeholder="Digite sua mensagem" required></textarea>
            <button type="submit" disabled>Enviar (fictício)</button>
        </form>
        <div class="info">
            <p><strong>E-mail:</strong> contato@ecomarket.com</p>
            <p><strong>Telefone:</strong> (11) 99999-9999</p>
        </div>
        <a class="voltar" href="index.php">&larr; Voltar para a página inicial</a>
    </div>
</body>
</html> 