<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Carrinho - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/Carrinho.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-carrinho">

    <header class="navbar">
        <div class="home-container" style="padding-bottom: 0; padding-top: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; padding: 10px 0;">
                <div class="logo-wrapper">
                    <?php 
                    $logoLocal = dirname(__DIR__) . '/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png';
                    $logoUrl = (file_exists($logoLocal)) 
                        ? BASE_URL . '/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png' 
                        : 'https://images.unsplash.com/photo-1539628399213-d6aa89c93074?w=100&q=80';
                    ?>
                    <img src="<?= $logoUrl ?>" alt="Logo Vinil & Groove" style="max-height: 55px; width: auto; border-radius:15px;">
                </div>
                <nav class="nav-links">
                    <a href="index.php?action=home"><span>💿</span> Discos</a>
                    <a href="index.php?action=acessorios"><span>🎧</span> Acessórios</a>
                    <a href="index.php?action=carrinho" class="active"><span>🛒</span> Carrinho</a>
                    <a href="index.php?action=suporte"><span>❓</span> Suporte</a>
                    
                    <?php if (!empty($_SESSION['usuario_perfil']) && ($_SESSION['usuario_perfil'] === 'administrador' || $_SESSION['usuario_perfil'] === 'admin')): ?>
                        <a href="index.php?action=admin" style="background-color: #222; color: #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);">⚙️ Painel Admin</a>
                    <?php endif; ?>
                    
                    <a href="index.php?action=logout" style="color: #ef4444; margin-left: 15px;">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="carrinho-container">
        <h2>🛒 Seu Carrinho de Compras</h2>

        <?php if (!empty($itens_carrinho) && is_array($itens_carrinho)): ?>
            
            <div class="carrinho-wrapper">
                <table class="tabela-carrinho">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço Unitário</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th style="text-align: right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens_carrinho as $item): ?>
                            <tr>
                                <td class="celula-produto">
                                    <div class="carrinho-produto-info">
                                        <?php 
                                        // REGRA IDÊNTICA À VITRINE (HOME_VIEW.PHP)
                                        // Captura a coluna 'foto' enviada dinamicamente pelo Controller.
                                        // Se estiver em branco (NULL), atribui uma imagem genérica elegante do Unsplash.
                                        $fotoCarrinho = (!empty($item['foto'])) 
                                            ? htmlspecialchars($item['foto']) 
                                            : "https://images.unsplash.com/photo-1539628399213-d6aa89c93074?w=500&q=80";
                                        ?>
                                        <img src="<?= $fotoCarrinho ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                                        <h3><?= htmlspecialchars($item['nome']) ?></h3>
                                    </div>
                                </td>
                                
                                <td data-label="Preço">
                                    <span class="carrinho-preco">R$ <?= number_format($item['preco_venda'], 2, ',', '.') ?></span>
                                </td>
                                
                                <td data-label="Quantidade">
                                    <form method="POST" action="index.php?action=carrinho_atualizar" style="display: inline-block;">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <div class="carrinho-qtd-wrapper">
                                            <input type="number" name="whitespace" style="display:none;"><input type="number" name="quantidade" class="carrinho-qtd-input" value="<?= $item['quantidade'] ?>" min="1" max="99" onchange="this.form.submit()">
                                        </div>
                                    </form>
                                </td>
                                
                                <td data-label="Subtotal">
                                    <span class="carrinho-subtotal">
                                        R$ <?= number_format($item['preco_venda'] * $item['quantidade'], 2, ',', '.') ?>
                                    </span>
                                </td>
                                
                                <td data-label="Ações" style="text-align: right;">
                                    <a href="index.php?action=carrinho_remover&id=<?= $item['id'] ?>" class="btn-remover-item" title="Remover item do carrinho">
                                        ❌ Remover
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="carrinho-total-box">
                <div>
                    <span class="carrinho-total-label">Total do seu pedido:</span>
                    <div class="carrinho-total-valor">
                        R$ <?= number_format($valor_total_carrinho ?? 0.00, 2, ',', '.') ?>
                    </div>
                </div>
                
                <div class="carrinho-acoes">
                    <a href="index.php?action=home" class="btn-carrinho-voltar">
                        💿 Continuar Comprando
                    </a>
                    <a href="index.php?action=pagamento" class="btn-carrinho-avancar">
                        Avançar para o Pagamento ➔
                    </a>
                </div>
            </div>

        <?php else: ?>
            
            <div class="carrinho-wrapper carrinho-vazio">
                <p>O seu carrinho está vazio e sem nenhum disco girando por aqui...</p>
                <a href="index.php?action=home" class="btn-carrinho-avancar" style="display: inline-block; text-decoration: none;">
                    💿 Explorar a Vitrine de Discos
                </a>
            </div>
            
        <?php endif; ?>
    </div>

</body>
</html>