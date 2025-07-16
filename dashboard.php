<?php
session_start();
require_once 'classes/Usuario.php';
require_once 'classes/Produto.php';
require_once 'config/config.php';

// Verificar se o usuário está logado - OBRIGATÓRIO
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    // Redirecionar para login se não estiver logado
    header('Location: login.php');
    exit;
}

$usuario = null;
$produtos = [];
$mensagem = '';

// Verificar mensagens de feedback
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'editado':
            $mensagem = '<div class="alert-success">✅ Produto editado com sucesso!</div>';
            break;
        case 'excluido':
            $mensagem = '<div class="alert-success">✅ Produto excluído com sucesso!</div>';
            break;
        case 'criado':
            $mensagem = '<div class="alert-success">✅ Produto criado com sucesso!</div>';
            break;
        case 'erro':
            $mensagem = '<div class="alert-error">❌ Erro ao processar a operação.</div>';
            break;
    }
}

// Buscar dados do usuário logado
if (isset($_SESSION['id'])) {
    $usuarioObj = new Usuario($conn);
    $usuario = $usuarioObj->buscarPorId($_SESSION['id']);
    // Buscar apenas os produtos do usuário logado
    $produtos = Produto::buscarPorProdutor($_SESSION['id']);
} else {
    // Se não conseguir buscar o usuário, fazer logout
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - EcoMarket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --verde-principal: #388e3c;
            --verde-secundario: #66bb6a;
            --verde-escuro: #205723;
            --verde-claro: #e8f5e9;
            --verde-medio: #a5d6a7;
            --branco: #fff;
            --cinza-leve: #f4f6f5;
            --cinza-texto: #4e5d4e;
            --amarelo-suave: #fffde7;
            --vermelho-suave: #ffebee;
            --sombra: 0 4px 24px rgba(56, 142, 60, 0.10);
            --borda-card: 1.5px solid #d0e6d0;
        }
        html, body {
            font-family: 'Montserrat', 'Segoe UI', 'Roboto', Arial, sans-serif;
            background: #1c3c27;
            min-height: 100vh;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 12px 48px 12px;
        }
        .header {
            background: var(--branco);
            border-radius: 20px;
            padding: 32px 32px 20px 32px;
            margin-bottom: 36px;
            box-shadow: var(--sombra);
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: wrap;
            gap: 22px;
            border: var(--borda-card);
        }
        .header h1 {
            color: var(--verde-escuro);
            font-size: 2.2em;
            font-weight: 800;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .header h1::after {
            content: '\1F331';
            font-size: 1.2em;
        }
        .dashboard-btn, .voltar-btn {
            background: linear-gradient(90deg, var(--verde-principal), var(--verde-secundario));
            color: var(--branco);
            padding: 10px 28px;
            border-radius: 24px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.05em;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(56, 142, 60, 0.10);
            border: none;
            outline: none;
        }
        .dashboard-btn:hover, .voltar-btn:hover {
            background: linear-gradient(90deg, var(--verde-secundario), var(--verde-principal));
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 4px 18px rgba(56, 142, 60, 0.18);
        }
        .voltar-btn {
            margin-left: auto !important;
            order: 2;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
            background: var(--verde-claro);
            padding: 8px 18px;
            border-radius: 16px;
            font-size: 1.08em;
        }
        .user-info span {
            color: var(--verde-principal);
            font-weight: 600;
        }
        .edit-user-btn {
            background: var(--verde-medio);
            color: var(--verde-escuro);
            border: none;
            padding: 8px 20px;
            border-radius: 22px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
            margin-right: 8px;
        }
        .edit-user-btn:hover {
            background: var(--verde-principal);
            color: var(--branco);
            transform: translateY(-2px);
        }
        .logout-btn {
            background: #e57373;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 22px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background 0.2s, transform 0.2s;
        }
        .logout-btn:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }
        .actions-bar {
            background: var(--branco);
            border-radius: 16px;
            padding: 26px 22px;
            margin-bottom: 32px;
            box-shadow: var(--sombra);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            border: var(--borda-card);
        }
        .add-product-btn {
            background: linear-gradient(90deg, var(--verde-medio), var(--verde-principal));
            color: var(--branco);
            border: none;
            padding: 15px 32px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1.13em;
            font-weight: 700;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(56, 142, 60, 0.10);
        }
        .add-product-btn:hover {
            background: linear-gradient(90deg, var(--verde-principal), var(--verde-medio));
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 4px 18px rgba(56, 142, 60, 0.18);
        }
        .view-products-btn {
            background: linear-gradient(90deg, var(--verde-secundario), var(--verde-principal));
            color: var(--branco);
            border: none;
            padding: 15px 32px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1.13em;
            font-weight: 700;
            transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(56, 142, 60, 0.10);
        }
        .view-products-btn:hover {
            background: linear-gradient(90deg, var(--verde-principal), var(--verde-secundario));
            transform: translateY(-2px) scale(1.04);
            box-shadow: 0 4px 18px rgba(56, 142, 60, 0.18);
        }
        .stats {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }
        .stat-item {
            background: var(--verde-claro);
            padding: 18px 32px;
            border-radius: 14px;
            text-align: center;
            min-width: 130px;
            box-shadow: 0 1px 4px rgba(139, 195, 74, 0.08);
            border: var(--borda-card);
        }
        .stat-number {
            font-size: 1.7em;
            font-weight: bold;
            color: var(--verde-principal);
        }
        .stat-label {
            font-size: 1.08em;
            color: var(--verde-escuro);
            margin-top: 4px;
        }
        .products-grid {
            display: flex;
            flex-direction: column;
            gap: 30px;
            margin-top: 32px;
        }
        .product-card {
            background: linear-gradient(90deg, var(--branco) 60%, var(--verde-claro) 100%);
            border-radius: 20px;
            box-shadow: var(--sombra);
            padding: 0;
            display: flex;
            align-items: center;
            gap: 0;
            overflow: hidden;
            position: relative;
            border-left: 8px solid var(--verde-medio);
            border: var(--borda-card);
        }
        .product-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 0 20px 20px 0;
            margin-right: 0;
            background: var(--verde-medio);
            flex-shrink: 0;
            border-right: 1.5px solid #c8e6c9;
        }
        .product-info {
            flex: 1;
            padding: 28px 28px 28px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .product-title {
            font-size: 1.3em;
            font-weight: 700;
            color: var(--verde-escuro);
            margin-bottom: 2px;
        }
        .product-description {
            color: var(--cinza-texto);
            margin-bottom: 6px;
            line-height: 1.6;
            font-size: 1.05em;
        }
        .product-price {
            font-size: 1.13em;
            font-weight: bold;
            color: var(--verde-principal);
            margin-bottom: 4px;
        }
        .product-category {
            background: var(--verde-medio);
            color: var(--verde-escuro);
            padding: 5px 18px;
            border-radius: 22px;
            font-size: 1em;
            display: inline-block;
            margin-bottom: 0;
            font-weight: 600;
        }
        .product-actions {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-right: 28px;
            align-items: flex-end;
        }
        .btn-edit, .btn-delete {
            border: none;
            padding: 12px 26px;
            border-radius: 24px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-edit {
            background: var(--amarelo-suave);
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
            background: var(--vermelho-suave);
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
            padding: 70px 20px;
            color: #666;
            background: var(--branco);
            border-radius: 18px;
            box-shadow: var(--sombra);
            border: var(--borda-card);
        }
        .empty-state h3 {
            color: var(--verde-escuro);
            margin-bottom: 18px;
            font-size: 1.5em;
        }
        .empty-state p {
            margin-bottom: 28px;
            font-size: 1.13em;
        }
        /* Modal de confirmação */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.18);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-confirm {
            background: var(--branco);
            border-radius: 18px;
            padding: 36px;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(67,160,71,0.13);
            border: var(--borda-card);
        }
        .modal-confirm h3 {
            color: var(--verde-escuro);
            margin-bottom: 18px;
        }
        .modal-confirm p {
            color: #666;
            margin-bottom: 28px;
        }
        .modal-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
        }
        .btn-cancel {
            background: #9e9e9e;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 22px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
        }
        .btn-confirm {
            background: #ef5350;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 22px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
        }
        
        /* Alertas de feedback */
        .alert-success, .alert-error {
            padding: 16px 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 600;
            text-align: center;
            animation: slideIn 0.3s ease-out;
        }
        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 2px solid #a5d6a7;
        }
        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 2px solid #ef9a9a;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Modal de Produtos */
        .modal-produtos {
            background: var(--branco);
            border-radius: 20px;
            padding: 32px;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            position: relative;
        }
        .modal-produtos h3 {
            color: var(--verde-escuro);
            margin-bottom: 24px;
            font-size: 1.8em;
            text-align: center;
        }
        .produtos-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .produto-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--verde-claro);
            border-radius: 12px;
            border: 1px solid var(--verde-medio);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .produto-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(56, 142, 60, 0.15);
        }
        .produto-imagem {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }
        .produto-detalhes {
            flex: 1;
        }
        .produto-detalhes h4 {
            color: var(--verde-escuro);
            margin: 0 0 8px 0;
            font-size: 1.2em;
            font-weight: 700;
        }
        .produto-detalhes p {
            color: var(--cinza-texto);
            margin: 0 0 8px 0;
            font-size: 0.95em;
            line-height: 1.4;
        }
        .produto-info {
            display: flex;
            gap: 16px;
            align-items: center;
        }
        .produto-info .preco {
            color: var(--verde-principal);
            font-weight: 700;
            font-size: 1.1em;
        }
        .produto-info .categoria {
            background: var(--verde-medio);
            color: var(--verde-escuro);
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 0.9em;
            font-weight: 600;
        }
        .produto-acoes {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn-edit-small, .btn-delete-small {
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            transition: all 0.2s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-edit-small {
            background: var(--amarelo-suave);
            color: #fbc02d;
            border: 1px solid #fbc02d;
        }
        .btn-edit-small:hover {
            background: #fff9c4;
            color: #e65100;
            border-color: #e65100;
            transform: scale(1.1);
        }
        .btn-delete-small {
            background: var(--vermelho-suave);
            color: #e57373;
            border: 1px solid #e57373;
        }
        .btn-delete-small:hover {
            background: #ffcdd2;
            color: #b71c1c;
            border-color: #b71c1c;
            transform: scale(1.1);
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--cinza-texto);
        }
        .empty-state h4 {
            color: var(--verde-escuro);
            margin-bottom: 12px;
            font-size: 1.3em;
        }
        .empty-state p {
            margin-bottom: 20px;
            font-size: 1.05em;
        }
        @media (max-width: 900px) {
            .container { padding: 8px; }
            .header, .actions-bar { flex-direction: column; text-align: center; }
            .stats { justify-content: center; }
            .voltar-btn { margin-left: 0 !important; order: unset; align-self: flex-end; }
        }
        @media (max-width: 700px) {
            .products-grid { gap: 20px; }
            .product-card { flex-direction: column; align-items: stretch; border-left: 0; border-top: 8px solid var(--verde-medio); }
            .product-image { width: 100%; height: 180px; border-radius: 0 0 20px 20px; border-right: 0; }
            .product-actions { flex-direction: row; justify-content: flex-end; margin: 14px 0 0 0; }
            .product-info { padding: 18px 12px 10px 12px; }
            .modal-produtos { padding: 20px; margin: 10px; }
            .produto-item { flex-direction: column; text-align: center; }
            .produto-acoes { justify-content: center; }
            .actions-bar > div:first-child { flex-direction: column; gap: 8px; }
        }
        @media (max-width: 500px) {
            .header h1 { font-size: 1.1em; }
            .product-title { font-size: 1em; }
            .product-description { font-size: 0.97em; }
            .product-image { height: 120px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="assets/logo.png" alt="EcoMarket Logo" style="height:80px; width:auto; max-width:150px; margin-right:14px;">
            <h1>🌱 Meu Dashboard</h1>
            <div class="user-info">
                <?php if ($usuario): ?>
                    <span>Olá, <?php echo htmlspecialchars($usuario['nome']); ?></span>
                    <button class="edit-user-btn" onclick="editarUsuario()">👤 Editar Perfil</button>
                    <button class="logout-btn" onclick="logout()">Sair</button>
                <?php else: ?>
                    <span>Visualizando todos os produtos</span>
                    <button class="logout-btn" onclick="window.location.href='login.php'">Login</button>
                <?php endif; ?>
            </div>
            <a href="index.php" class="dashboard-btn voltar-btn">← Voltar para Vitrine</a>
        </div>
        
        <?php if ($mensagem): ?>
            <?php echo $mensagem; ?>
        <?php endif; ?>
        
        <div class="actions-bar">
            <div style="display: flex; gap: 12px; align-items: center;">
                <button class="add-product-btn" onclick="window.location.href='cadastrarProduto.php'">
                    ➕ Adicionar Produto
                </button>
                <button class="view-products-btn" onclick="abrirModalProdutos()">
                    📦 Ver Meus Produtos
                </button>
            </div>
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
    </div>
    
    <!-- Modal de Produtos do Usuário -->
    <div class="modal-overlay" id="modalProdutos">
        <div class="modal-produtos">
            <button class="close-btn" onclick="fecharModalProdutos()">&times;</button>
            <h3>📦 Meus Produtos</h3>
            <div class="produtos-container">
                <?php if (empty($produtos)): ?>
                    <div class="empty-state">
                        <h4>Nenhum produto cadastrado ainda</h4>
                        <p>Comece adicionando seu primeiro produto sustentável!</p>
                        <button class="add-product-btn" onclick="window.location.href='cadastrarProduto.php'">
                            ➕ Adicionar Primeiro Produto
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="produto-item">
                            <img src="<?php echo $produto->imagem ? $produto->imagem : 'https://via.placeholder.com/80x80?text=Produto'; ?>" 
                                 alt="<?php echo htmlspecialchars($produto->nome); ?>" 
                                 class="produto-imagem"
                                 onerror="this.src='https://via.placeholder.com/80x80?text=Produto'">
                            <div class="produto-detalhes">
                                <h4><?php echo htmlspecialchars($produto->nome); ?></h4>
                                <p><?php echo htmlspecialchars($produto->descricao); ?></p>
                                <div class="produto-info">
                                    <span class="preco">R$ <?php echo number_format($produto->preco, 2, ',', '.'); ?></span>
                                    <span class="categoria"><?php echo htmlspecialchars($produto->categoria); ?></span>
                                </div>
                            </div>
                            <div class="produto-acoes">
                                <button class="btn-edit-small" onclick="editarProduto(<?php echo $produto->id; ?>)" title="Editar produto">
                                    ✏️
                                </button>
                                <button class="btn-delete-small" onclick="confirmarExclusao(<?php echo $produto->id; ?>, '<?php echo htmlspecialchars($produto->nome); ?>')" title="Excluir produto">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
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
        
        function editarUsuario() {
            window.location.href = 'alterarUsuario.php';
        }
        
        function abrirModalProdutos() {
            document.getElementById('modalProdutos').classList.add('active');
        }
        
        function fecharModalProdutos() {
            document.getElementById('modalProdutos').classList.remove('active');
        }
        
        function editarProduto(id) {
            // Adicionar feedback visual
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '⏳ Carregando...';
            btn.disabled = true;
            
            setTimeout(() => {
                window.location.href = `alterarProduto.php?id=${id}`;
            }, 300);
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
                // Adicionar feedback visual
                const btn = document.querySelector('.btn-confirm');
                const originalText = btn.innerHTML;
                btn.innerHTML = '⏳ Excluindo...';
                btn.disabled = true;
                
                setTimeout(() => {
                    window.location.href = `deletarProduto.php?id=${produtoIdParaExcluir}`;
                }, 500);
            }
        }
        
        // Fechar modais ao clicar fora
        document.getElementById('modalConfirm').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
        
        document.getElementById('modalProdutos').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalProdutos();
            }
        });
        
        // Auto-hide para mensagens de alerta
        const alerts = document.querySelectorAll('.alert-success, .alert-error');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    </script>
</body>
</html>
