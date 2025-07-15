<?php
session_start();
require_once 'classes/Usuario.php';
require_once 'classes/Produto.php';

// Verificar se o usuário está logado (opcional)
$usuario = null;
$produtos = [];

if (isset($_SESSION['usuario_id'])) {
    $usuario = Usuario::buscarPorId($_SESSION['usuario_id']);
    $produtos = Produto::buscarPorProdutor($_SESSION['usuario_id']);
} else {
    // Se não estiver logado, mostrar todos os produtos
    $produtos = Produto::buscarTodos();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - EcoMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Roboto', 'Open Sans', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: rgba(255,255,255,0.97);
            border-radius: 18px;
            padding: 28px 24px;
            margin-bottom: 36px;
            box-shadow: 0 4px 24px rgba(76, 175, 80, 0.10);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }
        .header h1 {
            color: #2e7d32;
            font-size: 2em;
            font-weight: 700;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header h1::after {
            content: '\1F331'; /* emoji muda de folha */
            font-size: 1.2em;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .user-info span {
            color: #388e3c;
            font-weight: 500;
        }
        .logout-btn {
            background: #ff7043;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 22px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 500;
            transition: background 0.2s, transform 0.2s;
        }
        .logout-btn:hover {
            background: #d84315;
            transform: translateY(-2px);
        }
        .actions-bar {
            background: rgba(255,255,255,0.93);
            border-radius: 14px;
            padding: 22px 18px;
            margin-bottom: 28px;
            box-shadow: 0 2px 12px rgba(76, 175, 80, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }
        .add-product-btn {
            background: linear-gradient(135deg, #81c784, #388e3c);
            color: white;
            border: none;
            padding: 13px 28px;
            border-radius: 28px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(56, 142, 60, 0.10);
        }
        .add-product-btn:hover {
            background: linear-gradient(135deg, #388e3c, #81c784);
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 4px 18px rgba(56, 142, 60, 0.18);
        }
        .stats {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }
        .stat-item {
            background: #f9fbe7;
            padding: 14px 24px;
            border-radius: 12px;
            text-align: center;
            min-width: 120px;
            box-shadow: 0 1px 4px rgba(139, 195, 74, 0.08);
        }
        .stat-number {
            font-size: 1.6em;
            font-weight: bold;
            color: #43a047;
        }
        .stat-label {
            font-size: 1em;
            color: #7cb342;
            margin-top: 4px;
        }
        .products-grid {
            display: flex;
            flex-direction: column;
            gap: 26px;
            margin-top: 28px;
        }
        .product-card {
            background: linear-gradient(90deg, #f1f8e9 60%, #e0f2f1 100%);
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(56, 142, 60, 0.08);
            padding: 0;
            display: flex;
            align-items: center;
            gap: 0;
            overflow: hidden;
            position: relative;
            border-left: 8px solid #81c784;
        }
        .product-image {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 0 18px 18px 0;
            margin-right: 0;
            background: #c8e6c9;
            flex-shrink: 0;
        }
        .product-info {
            flex: 1;
            padding: 24px 24px 24px 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .product-title {
            font-size: 1.25em;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 2px;
        }
        .product-description {
            color: #5d6d5d;
            margin-bottom: 6px;
            line-height: 1.5;
            font-size: 1em;
        }
        .product-price {
            font-size: 1.1em;
            font-weight: bold;
            color: #388e3c;
            margin-bottom: 4px;
        }
        .product-category {
            background: #e8f5e9;
            color: #388e3c;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.95em;
            display: inline-block;
            margin-bottom: 0;
        }
        .product-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-right: 24px;
            align-items: flex-end;
        }
        .btn-edit, .btn-delete {
            border: none;
            padding: 10px 22px;
            border-radius: 22px;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-edit {
            background: #fffde7;
            color: #fbc02d;
            border: 1.5px solid #fbc02d;
        }
        .btn-edit:hover {
            background: #fff9c4;
            color: #e65100;
            border-color: #e65100;
            transform: translateY(-1px) scale(1.04);
        }
        .btn-delete {
            background: #ffebee;
            color: #e57373;
            border: 1.5px solid #e57373;
        }
        .btn-delete:hover {
            background: #ffcdd2;
            color: #b71c1c;
            border-color: #b71c1c;
            transform: translateY(-1px) scale(1.04);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .empty-state h3 {
            color: #2e7d32;
            margin-bottom: 16px;
            font-size: 1.5em;
        }
        .empty-state p {
            margin-bottom: 24px;
            font-size: 1.1em;
        }
        /* Modal de confirmação */
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
        .modal-confirm {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }
        .modal-confirm h3 {
            color: #2e7d32;
            margin-bottom: 16px;
        }
        .modal-confirm p {
            color: #666;
            margin-bottom: 24px;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .btn-cancel {
            background: #9e9e9e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }
        .btn-confirm {
            background: #ef5350;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }
        @media (max-width: 900px) {
            .container { padding: 8px; }
            .header, .actions-bar { flex-direction: column; text-align: center; }
            .stats { justify-content: center; }
        }
        @media (max-width: 700px) {
            .products-grid { gap: 18px; }
            .product-card { flex-direction: column; align-items: stretch; border-left: 0; border-top: 8px solid #81c784; }
            .product-image { width: 100%; height: 180px; border-radius: 0 0 18px 18px; }
            .product-actions { flex-direction: row; justify-content: flex-end; margin: 12px 0 0 0; }
            .product-info { padding: 18px 12px 8px 12px; }
        }
        @media (max-width: 500px) {
            .header h1 { font-size: 1.2em; }
            .product-title { font-size: 1em; }
            .product-description { font-size: 0.95em; }
            .product-image { height: 120px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 Dashboard do Produtor</h1>
            <div class="user-info">
                <?php if ($usuario): ?>
                    <span>Olá, <?php echo htmlspecialchars($usuario->nome); ?></span>
                    <button class="logout-btn" onclick="logout()">Sair</button>
                <?php else: ?>
                    <span>Visualizando todos os produtos</span>
                    <button class="logout-btn" onclick="window.location.href='login.php'">Login</button>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="actions-bar">
            <?php if ($usuario): ?>
                <button class="add-product-btn" onclick="window.location.href='cadastrarProduto.php'">
                    ➕ Adicionar Produto
                </button>
            <?php else: ?>
                <button class="add-product-btn" onclick="window.location.href='login.php'">
                    🔐 Fazer Login para Gerenciar
                </button>
            <?php endif; ?>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($produtos); ?></div>
                    <div class="stat-label">Produtos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count(array_unique(array_map(function($p) { return $p->categoria; }, $produtos))); ?></div>
                    <div class="stat-label">Categorias</div>
                </div>
            </div>
        </div>
        
        <?php if (empty($produtos)): ?>
            <div class="empty-state">
                <h3>Nenhum produto cadastrado ainda</h3>
                <p>Comece adicionando seu primeiro produto sustentável!</p>
                <?php if ($usuario): ?>
                    <button class="add-product-btn" onclick="window.location.href='cadastrarProduto.php'">
                        ➕ Adicionar Primeiro Produto
                    </button>
                <?php else: ?>
                    <button class="add-product-btn" onclick="window.location.href='login.php'">
                        🔐 Fazer Login para Adicionar
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card">
                        <img src="imagens/<?php echo $produto->imagem; ?>" alt="<?php echo htmlspecialchars($produto->nome); ?>" class="product-image">
                        <div class="product-info">
                            <h3 class="product-title"><?php echo htmlspecialchars($produto->nome); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($produto->descricao); ?></p>
                            <div class="product-price">R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></div>
                            <div class="product-category"><?php echo htmlspecialchars($produto->categoria); ?></div>
                        </div>
                        <?php if ($usuario): ?>
                        <div class="product-actions">
                            <button class="btn-edit" onclick="editarProduto(<?php echo $produto->id; ?>)">
                                ✏️ Alterar
                            </button>
                            <button class="btn-delete" onclick="confirmarExclusao(<?php echo $produto->id; ?>, '<?php echo htmlspecialchars($produto->nome); ?>')">
                                🗑️ Excluir
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal de confirmação de exclusão -->
    <div class="modal-overlay" id="modalConfirm">
        <div class="modal-confirm">
            <h3>Confirmar Exclusão</h3>
            <p>Tem certeza que deseja excluir o produto "<span id="produtoNome"></span>"?</p>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="fecharModal()">Cancelar</button>
                <button class="btn-confirm" onclick="excluirProduto()">Excluir</button>
            </div>
        </div>
    </div>
    
    <script>
        let produtoIdParaExcluir = null;
        
        function logout() {
            if (confirm('Tem certeza que deseja sair?')) {
                window.location.href = 'logout.php';
            }
        }
        
        function login() {
            window.location.href = 'login.php';
        }
        
        function editarProduto(id) {
            window.location.href = `alterarProduto.php?id=${id}`;
        }
        
        function confirmarExclusao(id, nome) {
            produtoIdParaExcluir = id;
            document.getElementById('produtoNome').textContent = nome;
            document.getElementById('modalConfirm').classList.add('active');
        }
        
        function fecharModal() {
            document.getElementById('modalConfirm').classList.remove('active');
            produtoIdParaExcluir = null;
        }
        
        function excluirProduto() {
            if (produtoIdParaExcluir) {
                window.location.href = `deletarProduto.php?id=${produtoIdParaExcluir}`;
            }
        }
        
        // Fechar modal ao clicar fora
        document.getElementById('modalConfirm').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
    </script>
</body>
</html>
