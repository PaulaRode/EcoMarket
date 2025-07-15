<?php
require_once 'classes/Produto.php';

// Obter categorias únicas dos produtos simulados
$produtos = Produto::buscarTodos();
$categorias = array_unique(array_map(function($p) { return $p->categoria; }, $produtos));

// Filtragem por categoria
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
if ($categoriaSelecionada && in_array($categoriaSelecionada, $categorias)) {
    $produtos = Produto::buscarPorCategoria($categoriaSelecionada);
} else {
    $produtos = Produto::buscarTodos();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>EcoMarket - Vitrine de Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', 'Roboto', 'Open Sans', 'Helvetica Neue', Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        h1 { text-align: center; color: #2e7d32; }
        .topo-links { text-align: right; margin-bottom: 12px; }
        .topo-links a, .topo-links button { color: #388e3c; text-decoration: none; font-weight: bold; margin-left: 18px; background: none; border: none; cursor: pointer; font-size: 1em; padding: 0; }
        .topo-links .login-btn { color: #fff; background: #388e3c; border-radius: 20px; padding: 6px 22px; margin-left: 18px; transition: background 0.2s; }
        .topo-links .login-btn:hover { background: #2e7d32; }
        .institucional {
            background: #e8f5e9;
            border-radius: 8px;
            padding: 32px 24px;
            margin-bottom: 36px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(46,125,50,0.05);
        }
        .institucional h2 { color: #388e3c; margin-bottom: 12px; }
        .institucional p { color: #444; font-size: 1.1em; margin-bottom: 18px; }
        .institucional .cta {
            display: inline-block;
            background: #43a047;
            color: #fff;
            padding: 10px 28px;
            border-radius: 24px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        .institucional .cta:hover { background: #2e7d32; }
        .filtro-categoria { text-align: center; margin-bottom: 32px; }
        .filtro-categoria select { padding: 8px 12px; font-size: 1em; border-radius: 4px; border: 1px solid #bdbdbd; }
        .vitrine { display: flex; flex-wrap: wrap; gap: 24px; justify-content: center; }
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            width: 260px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .card h2 { font-size: 1.2em; margin: 0 0 8px 0; color: #388e3c; }
        .card p { margin: 0 0 8px 0; color: #555; }
        .card .preco { font-weight: bold; color: #1b5e20; margin-bottom: 8px; }
        .card .categoria { font-size: 0.95em; color: #757575; }
        /* Modal Login */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-login {
            background: #fff;
            border-radius: 10px;
            padding: 32px 28px 24px 28px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            min-width: 320px;
            max-width: 90vw;
            position: relative;
            text-align: center;
        }
        .modal-login h2 { color: #388e3c; margin-bottom: 18px; }
        .modal-login label { display: block; text-align: left; margin-bottom: 6px; color: #388e3c; font-weight: bold; }
        .modal-login input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border-radius: 4px;
            border: 1px solid #bdbdbd;
            font-size: 1em;
        }
        .modal-login button[type="submit"] {
            background: #43a047;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 0;
            width: 100%;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .modal-login button[type="submit"]:hover { background: #2e7d32; }
        .modal-login .fechar-modal {
            position: absolute;
            top: 10px;
            right: 16px;
            background: none;
            border: none;
            font-size: 1.5em;
            color: #888;
            cursor: pointer;
        }
        .modal-login .fechar-modal:hover { color: #d32f2f; }
        /* Responsividade */
        @media (max-width: 900px) {
            .container { max-width: 98vw; padding: 10px; }
            .vitrine { gap: 14px; }
            .institucional { padding: 24px 8px; }
        }
        @media (max-width: 600px) {
            .container { max-width: 100vw; margin: 0; padding: 4px; }
            .topo-links { text-align: center; margin-bottom: 18px; }
            .institucional { padding: 16px 4px; font-size: 0.98em; }
            .vitrine { flex-direction: column; align-items: center; gap: 12px; }
            .card { width: 98vw; max-width: 340px; padding: 10px; }
            .card img { width: 90px; height: 90px; }
            .modal-login { min-width: 90vw; padding: 18px 6vw 16px 6vw; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topo-links">
            <a href="contato.php">Contato</a>
            <button type="button" class="login-btn" id="abrirLogin">Login</button>
        </div>
        <!-- Modal de Login -->
        <div class="modal-overlay" id="modalLogin">
            <div class="modal-login">
                <button class="fechar-modal" id="fecharLogin" title="Fechar">&times;</button>
                <h2>Login do Produtor</h2>
                <form autocomplete="off">
                    <label for="login-email">E-mail</label>
                    <input type="email" id="login-email" name="email" placeholder="seu@email.com" required>
                    <label for="login-senha">Senha</label>
                    <input type="password" id="login-senha" name="senha" placeholder="Sua senha" required>
                    <button type="submit" disabled>Entrar</button>
                </form>
            </div>
        </div>
        <div class="institucional">
            <h2>EcoMarket: onde suas compras cuidam do planeta 🌱</h2>
            <p>O EcoMarket conecta você a produtos sustentáveis, naturais e orgânicos, promovendo o consumo consciente e apoiando pequenos produtores locais. Junte-se a nós nessa causa e descubra uma nova forma de consumir!</p>
            <a class="cta" href="#vitrine">Conheça nossos produtores</a>
        </div>
        <h1 id="vitrine">Vitrine de Produtos</h1>
        <form class="filtro-categoria" method="get">
            <label for="categoria">Filtrar por categoria:</label>
            <select name="categoria" id="categoria" onchange="this.form.submit()">
                <option value="">Todas</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php if ($cat === $categoriaSelecionada) echo 'selected'; ?>><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <div class="vitrine">
            <?php foreach ($produtos as $produto): ?>
                <div class="card">
                    <img src="<?php echo $produto->imagem ? 'imagens/' . $produto->imagem : 'https://via.placeholder.com/120x120?text=Produto'; ?>" alt="<?php echo htmlspecialchars($produto->nome); ?>">
                    <h2><?php echo htmlspecialchars($produto->nome); ?></h2>
                    <p><?php echo htmlspecialchars($produto->descricao); ?></p>
                    <div class="preco">R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></div>
                    <div class="categoria">Categoria: <?php echo htmlspecialchars($produto->categoria); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        const abrirLogin = document.getElementById('abrirLogin');
        const modalLogin = document.getElementById('modalLogin');
        const fecharLogin = document.getElementById('fecharLogin');
        abrirLogin.addEventListener('click', () => {
            modalLogin.classList.add('active');
        });
        fecharLogin.addEventListener('click', () => {
            modalLogin.classList.remove('active');
        });
        window.addEventListener('click', (e) => {
            if (e.target === modalLogin) {
                modalLogin.classList.remove('active');
            }
        });
    </script>
</body>
</html>
