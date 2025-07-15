<?php
session_start();
require_once 'classes/Usuario.php';
require_once 'classes/Produto.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario = Usuario::buscarPorId($_SESSION['usuario_id']);
$produtos = Produto::buscarPorProdutor($_SESSION['usuario_id']);
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 4px 20px rgba(76, 175, 80, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .header h1 {
            color: #2e7d32;
            font-size: 1.8em;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-info span {
            color: #388e3c;
            font-weight: 500;
        }
        
        .logout-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }
        
        .actions-bar {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(76, 175, 80, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }
        
        .add-product-btn {
            background: linear-gradient(135deg, #66bb6a, #4caf50);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .add-product-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }
        
        .stats {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            background: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 10px;
            text-align: center;
            min-width: 120px;
        }
        
        .stat-number {
            font-size: 1.5em;
            font-weight: bold;
            color: #2e7d32;
        }
        
        .stat-label {
            font-size: 0.9em;
            color: #666;
            margin-top: 4px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }
        
        .product-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(76, 175, 80, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #66bb6a, #4caf50);
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(76, 175, 80, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        
        .product-title {
            font-size: 1.3em;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 8px;
        }
        
        .product-description {
            color: #666;
            margin-bottom: 12px;
            line-height: 1.5;
        }
        
        .product-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #1b5e20;
            margin-bottom: 8px;
        }
        
        .product-category {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            display: inline-block;
            margin-bottom: 16px;
        }
        
        .product-actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }
        
        .btn-edit {
            background: #ffa726;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .btn-edit:hover {
            background: #ff9800;
            transform: translateY(-1px);
        }
        
        .btn-delete {
            background: #ef5350;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .btn-delete:hover {
            background: #f44336;
            transform: translateY(-1px);
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
        
        /* Responsividade */
        @media (max-width: 768px) {
            .container { padding: 16px; }
            .header { flex-direction: column; text-align: center; }
            .actions-bar { flex-direction: column; text-align: center; }
            .stats { justify-content: center; }
            .products-grid { grid-template-columns: 1fr; }
            .product-actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 Dashboard do Produtor</h1>
            <div class="user-info">
                <span>Olá, <?php echo htmlspecialchars($usuario->nome); ?></span>
                <button class="logout-btn" onclick="logout()">Sair</button>
            </div>
        </div>
        
        <div class="actions-bar">
            <button class="add-product-btn" onclick="window.location.href='cadastrarProduto.php'">
                ➕ Adicionar Produto
            </button>
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
                <button class="add-product-btn" onclick="window.location.href='cadastrarProduto.php'">
                    ➕ Adicionar Primeiro Produto
                </button>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card">
                        <img src="imagens/<?php echo $produto->imagem; ?>" alt="<?php echo htmlspecialchars($produto->nome); ?>" class="product-image">
                        <h3 class="product-title"><?php echo htmlspecialchars($produto->nome); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($produto->descricao); ?></p>
                        <div class="product-price">R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></div>
                        <div class="product-category"><?php echo htmlspecialchars($produto->categoria); ?></div>
                        <div class="product-actions">
                            <button class="btn-edit" onclick="editarProduto(<?php echo $produto->id; ?>)">
                                ✏️ Editar
                            </button>
                            <button class="btn-delete" onclick="confirmarExclusao(<?php echo $produto->id; ?>, '<?php echo htmlspecialchars($produto->nome); ?>')">
                                🗑️ Excluir
                            </button>
                        </div>
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
