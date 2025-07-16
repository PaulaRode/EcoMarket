<?php
require_once 'classes/Produto.php';
require_once 'classes/Categoria.php';

// Buscar todas as categorias do banco
$listaCategorias = Categoria::buscarTodas();

// Filtragem por categoria
$categoriaSelecionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';
if ($categoriaSelecionada) {
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
        body {
            font-family: 'Montserrat', 'Segoe UI', 'Roboto', Arial, sans-serif;
            background: #f4f6f5;
            margin: 0;
            padding: 0;
        }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #fff;
            box-shadow: 0 2px 12px rgba(56,142,60,0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 72px;
        }
        .topbar .logo-area {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .topbar .logo-area img {
            height: 48px;
            width: auto;
            max-width: 90px;
        }
        .topbar .logo-area span {
            font-size: 2em;
            color: #388e3c;
            font-weight: 800;
            letter-spacing: 1.5px;
        }
        .topbar .nav-links {
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .topbar .nav-links a, .topbar .nav-links button {
            color: #388e3c;
            text-decoration: none;
            font-weight: 700;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.08em;
            padding: 0 10px;
            border-radius: 20px;
            transition: background 0.18s, color 0.18s;
        }
        .topbar .nav-links .login-btn {
            color: #fff;
            background: linear-gradient(90deg, #388e3c, #66bb6a);
            border-radius: 22px;
            padding: 8px 28px;
            margin-left: 8px;
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(56,142,60,0.10);
        }
        .topbar .nav-links .login-btn:hover {
            background: linear-gradient(90deg, #66bb6a, #388e3c);
        }
        .topbar .nav-links a:hover {
            background: #e8f5e9;
            color: #205723;
        }
        .container {
            max-width: 1280px;
            margin: 40px auto 0 auto;
            padding: 24px 16px 48px 16px;
        }
        .institucional {
            background: #e8f5e9;
            border-radius: 14px;
            padding: 40px 32px 32px 32px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(56,142,60,0.07);
        }
        .institucional h2 {
            color: #388e3c;
            margin-bottom: 14px;
            font-size: 2em;
            font-weight: 800;
        }
        .institucional p {
            color: #444;
            font-size: 1.13em;
            margin-bottom: 22px;
        }
        .institucional .cta {
            display: inline-block;
            background: linear-gradient(90deg, #388e3c, #66bb6a);
            color: #fff;
            padding: 12px 36px;
            border-radius: 26px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1em;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(56,142,60,0.10);
        }
        .institucional .cta:hover {
            background: linear-gradient(90deg, #66bb6a, #388e3c);
        }
        h1 {
            text-align: left;
            color: #205723;
            font-size: 2.1em;
            font-weight: 800;
            margin-bottom: 18px;
            margin-top: 0;
        }
        .filtro-categoria {
            text-align: right;
            margin-bottom: 32px;
        }
        .filtro-categoria label {
            font-weight: 700;
            color: #388e3c;
            margin-right: 8px;
        }
        .filtro-categoria select {
            padding: 8px 16px;
            font-size: 1.08em;
            border-radius: 6px;
            border: 1.5px solid #bdbdbd;
            background: #fff;
            color: #205723;
            font-weight: 600;
        }
        .vitrine {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: flex-start;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(56,142,60,0.10);
            width: 300px;
            padding: 22px 18px 18px 18px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            transition: box-shadow 0.18s, transform 0.18s;
            border: 1.5px solid #e0f2f1;
        }
        .card:hover {
            box-shadow: 0 8px 32px rgba(56,142,60,0.18);
            transform: translateY(-4px) scale(1.02);
        }
        .card img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 16px;
            align-self: center;
            background: #e8f5e9;
        }
        .card h2 {
            font-size: 1.18em;
            margin: 0 0 8px 0;
            color: #205723;
            font-weight: 800;
        }
        .card p {
            margin: 0 0 10px 0;
            color: #555;
            font-size: 1.05em;
            min-height: 48px;
        }
        .card .preco {
            font-weight: 900;
            color: #388e3c;
            font-size: 1.25em;
            margin-bottom: 8px;
        }
        .card .categoria {
            font-size: 1em;
            color: #388e3c;
            background: #e8f5e9;
            border-radius: 16px;
            padding: 4px 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .card .card-actions {
            margin-top: 10px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .card .comprar-btn {
            background: linear-gradient(90deg, #388e3c, #66bb6a);
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 8px 22px;
            font-weight: 800;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.18s, transform 0.18s;
        }
        .card .comprar-btn:hover {
            background: linear-gradient(90deg, #66bb6a, #388e3c);
            transform: scale(1.04);
        }
        /* Responsividade */
        @media (max-width: 900px) {
            .container { max-width: 98vw; padding: 10px; }
            .vitrine { gap: 18px; }
            .institucional { padding: 24px 8px; }
            .topbar { padding: 0 8px; }
        }
        @media (max-width: 600px) {
            .container { max-width: 100vw; margin: 0; padding: 4px; }
            .institucional { padding: 16px 4px; font-size: 0.98em; }
            .vitrine { flex-direction: column; align-items: center; gap: 12px; }
            .card { width: 98vw; max-width: 340px; padding: 12px; }
            .card img { width: 90px; height: 90px; }
            .topbar .logo-area span { font-size: 1.2em; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo-area">
            <img src="assets/logo.png" alt="EcoMarket Logo">
            <span>EcoMarket</span>
        </div>
        <div class="nav-links">
            <a href="sobre.php">Contato</a>
            <a href="login.php" class="login-btn">Login</a>
        </div>
    </div>
    <div class="container">

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
                <?php foreach ($listaCategorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat->nome); ?>" <?php if ($cat->nome === $categoriaSelecionada) echo 'selected'; ?>><?php echo htmlspecialchars($cat->nome); ?></option>
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
                    <div class="card-actions">
                        <button class="comprar-btn" disabled>Comprar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>

