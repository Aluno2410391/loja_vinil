<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Pagamento - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/Pagamento.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-pagamento">

    <header class="navbar">
        <div class="home-container" style="padding-bottom: 0; padding-top: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; padding: 10px 0;">
                <div class="logo-wrapper">
                    <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove" style="max-height: 55px; border-radius:15px; width: auto;">
                </div>
                <nav class="nav-links">
                    <a href="index.php?action=home"><span>💿</span> Discos</a>
                    <a href="index.php?action=acessorios"><span>🎧</span> Acessórios</a>
                    <a href="index.php?action=carrinho"><span>🛒</span> Carrinho</a>
                    <a href="index.php?action=suporte"><span>❓</span> Suporte</a>
                    
                    <?php if (!empty($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'administrador'): ?>
                        <a href="index.php?action=admin" style="background-color: #222; color: #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);">⚙️ Painel Admin</a>
                    <?php endif; ?>
                    
                    <a href="index.php?action=logout" style="color: #ef4444; margin-left: 15px;">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="pagamento-container">
        
        <div class="resumo-box">
            <h2>🛒 Fechar Pedido</h2>
            <p>Confira o valor total da sua seleção de vinis e acessórios:</p>
            <div class="preco-final">
                R$ <?= number_format($totalGeral ?? 0.00, 2, ',', '.') ?>
            </div>
        </div>

        <h3 class="metodos-titulo">Escolha a sua Forma de Pagamento</h3>

        <div class="pagamento-grid">
            
            <div class="metodo-card">
                <img src="<?= BASE_URL ?>/views/img/pix.png" alt="Pix" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3204/3204051.png'">
                <div class="metodo-info">
                    <h3>Pix (Aprovação Imediata)</h3>
                    <p>Gere um código Copia e Cola ou um QR Code para pagar instantaneamente no aplicativo do seu banco.</p>
                </div>
                <form method="POST" action="index.php?action=processar_pagamento">
                    <input type="hidden" name="metodo" value="pix">
                    <button type="submit" class="btn-finalizar">Pagar com Pix</button>
                </form>
            </div>

            <div class="metodo-card">
                <img src="<?= BASE_URL ?>/views/img/cartao.png" alt="Cartão" onerror="this.src='https://cdn-icons-png.flaticon.com/512/2695/2695966.png'">
                <div class="metodo-info">
                    <h3>Cartão de Crédito</h3>
                    <p>Parcele em até 12x sem juros. Aceitamos todas as principais bandeiras com segurança e criptografia máxima.</p>
                </div>
                <form method="POST" action="index.php?action=processar_pagamento">
                    <input type="hidden" name="metodo" value="cartao">
                    <button type="submit" class="btn-finalizar">Pagar com Cartão</button>
                </form>
            </div>

            <div class="metodo-card">
                <img src="<?= BASE_URL ?>/views/img/Boleto.png" alt="Boleto" onerror="this.src='https://cdn-icons-png.flaticon.com/512/1041/1041865.png'">
                <div class="metodo-info">
                    <h3>Boleto Bancário</h3>
                    <p>Ideal para pagamentos à vista via internet banking ou casas lotéricas. Compensação de 1 a 2 dias úteis.</p>
                </div>
                <form method="POST" action="index.php?action=processar_pagamento">
                    <input type="hidden" name="metodo" value="boleto">
                    <button type="submit" class="btn-finalizar">Gerar Boleto</button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>