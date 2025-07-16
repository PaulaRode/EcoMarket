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
        
        /* Modal Styles */
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
        .modal {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .modal h3 {
            color: #388e3c;
            margin-bottom: 20px;
            font-size: 1.5em;
            text-align: center;
        }
        .modal .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        .modal .close-btn:hover {
            color: #388e3c;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #388e3c;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #388e3c;
        }
        .modal .btn-submit {
            background: linear-gradient(90deg, #388e3c, #66bb6a);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        .modal .btn-submit:hover {
            background: linear-gradient(90deg, #66bb6a, #388e3c);
        }
        
        /* Footer */
        .footer {
            background: #1c3c27;
            color: #fff;
            padding: 40px 0 20px 0;
            margin-top: 60px;
        }
        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 16px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }
        .footer-section h4 {
            color: #66bb6a;
            margin-bottom: 20px;
            font-size: 1.2em;
        }
        .footer-section p, .footer-section a {
            color: #ccc;
            text-decoration: none;
            line-height: 1.6;
        }
        .footer-section a:hover {
            color: #66bb6a;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #2d4a3a;
            color: #999;
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
            .modal { padding: 20px; margin: 10px; }
            .footer-content { grid-template-columns: 1fr; gap: 20px; }
            .footer { padding: 20px 0 10px 0; }
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
            <a href="sobre.php">Sobre</a>
            <button onclick="abrirFormulario()" style="background: none; border: none; cursor: pointer; color: #388e3c; font-weight: 700; font-size: 1.08em; padding: 0 10px; border-radius: 20px; transition: background 0.18s;">Contato</button>
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
                    <img src="<?php echo $produto->imagem ? $produto->imagem : 'https://via.placeholder.com/120x120?text=Produto'; ?>" alt="<?php echo htmlspecialchars($produto->nome); ?>">
                    <h2><?php echo htmlspecialchars($produto->nome); ?></h2>
                    <p><?php echo htmlspecialchars($produto->descricao); ?></p>
                    <div class="preco">R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></div>
                    <div class="categoria">Categoria: <?php echo htmlspecialchars($produto->categoria); ?></div>
                    <div class="card-actions">
                        <button class="comprar-btn" onclick="abrirModalProduto(<?php echo htmlspecialchars(json_encode($produto)); ?>)">Comprar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>🌱 EcoMarket</h4>
                <p>Conectando você a produtos sustentáveis e naturais. Apoiamos pequenos produtores locais e promovemos o consumo consciente.</p>
            </div>
            <div class="footer-section">
                <h4>📞 Contato</h4>
                <p>Email: contato@ecomarket.com</p>
                <p>Telefone: (51) 99999-9999</p>
                <p>Endereço: Rua das Flores, 123 - Porto Alegre/RS</p>
            </div>
            <div class="footer-section">
                <h4>🔗 Links Úteis</h4>
                <p><a href="sobre.php">Sobre Nós</a></p>
                <p><a href="#" onclick="abrirFormulario()">Fale Conosco</a></p>
                <p><a href="login.php">Área do Produtor</a></p>
            </div>
            <div class="footer-section">
                <h4>🌍 Sustentabilidade</h4>
                <p>Comprometidos com o meio ambiente e com o desenvolvimento sustentável da nossa comunidade.</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 EcoMarket. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Modal de Formulário de Contato -->
    <div class="modal-overlay" id="modalFormulario">
        <div class="modal">
            <button class="close-btn" onclick="fecharModal('modalFormulario')">&times;</button>
            <h3>📧 Fale Conosco</h3>
            <form id="formContato">
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone">
                </div>
                <div class="form-group">
                    <label for="assunto">Assunto:</label>
                    <select id="assunto" name="assunto" required>
                        <option value="">Selecione um assunto</option>
                        <option value="duvida">Dúvida sobre Produtos</option>
                        <option value="sugestao">Sugestão</option>
                        <option value="reclamacao">Reclamação</option>
                        <option value="parceria">Proposta de Parceria</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mensagem">Mensagem:</label>
                    <textarea id="mensagem" name="mensagem" rows="5" required placeholder="Digite sua mensagem aqui..."></textarea>
                </div>
                <button type="submit" class="btn-submit">Enviar Mensagem</button>
            </form>
        </div>
    </div>

    <!-- Modal de Informações do Produto -->
    <div class="modal-overlay" id="modalProduto">
        <div class="modal">
            <button class="close-btn" onclick="fecharModal('modalProduto')">&times;</button>
            <h3 id="produtoTitulo">Informações do Produto</h3>
            <div id="produtoConteudo">
                <!-- Conteúdo será preenchido via JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Funções para os modais
        function abrirFormulario() {
            document.getElementById('modalFormulario').classList.add('active');
        }

        function abrirModalProduto(produto) {
            const modal = document.getElementById('modalProduto');
            const titulo = document.getElementById('produtoTitulo');
            const conteudo = document.getElementById('produtoConteudo');
            
            titulo.textContent = produto.nome;
            conteudo.innerHTML = `
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="${produto.imagem ? produto.imagem : 'https://via.placeholder.com/200x200?text=Produto'}" 
                         alt="${produto.nome}" style="width: 200px; height: 200px; object-fit: cover; border-radius: 12px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Descrição:</strong>
                    <p>${produto.descricao}</p>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Preço:</strong>
                    <p style="color: #388e3c; font-size: 1.2em; font-weight: bold;">R$ ${parseFloat(produto.preco).toFixed(2).replace('.', ',')}</p>
                </div>
                <div style="margin-bottom: 20px;">
                    <strong>Categoria:</strong>
                    <p>${produto.categoria}</p>
                </div>
                <div style="text-align: center;">
                    <button onclick="comprarProduto(${produto.id})" style="background: linear-gradient(90deg, #388e3c, #66bb6a); color: #fff; border: none; padding: 12px 30px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer;">
                        🛒 Finalizar Compra
                    </button>
                </div>
            `;
            
            modal.classList.add('active');
        }

        function fecharModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function comprarProduto(produtoId) {
            alert('Funcionalidade de compra será implementada em breve! Produto ID: ' + produtoId);
            fecharModal('modalProduto');
        }

        // Fechar modais ao clicar fora
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Envio do formulário de contato
        document.getElementById('formContato').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Mensagem enviada com sucesso! Entraremos em contato em breve.');
            fecharModal('modalFormulario');
            this.reset();
        });
    </script>

</body>
</html>

