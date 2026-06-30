<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-sucesso" style="margin: 0; padding: 0; background-color: #fcfbfa;">

    <header class="navbar" style="width: 100%; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-sizing: border-box;">
        <div class="home-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin: 0 auto; background: transparent; padding: 0 20px; box-sizing: border-box;">
            
            <div class="logo-wrapper">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove" style="max-height: 55px; border-radius:15px;">
            </div>
            
            <nav class="nav-links">
                <a href="index.php?action=home">💿 Discos</a>
                <a href="index.php?action=acessorios">🎧 Acessórios</a>
                <a href="index.php?action=suporte">💬 Suporte</a>
                <a href="index.php?action=carrinho">🛒 Carrinho</a>
                <?php if (isset($_SESSION['usuario_perfil']) && ($_SESSION['usuario_perfil'] === 'administrador' || $_SESSION['usuario_perfil'] === 'admin')): ?>
                    <a href="index.php?action=adm">⚙️ Painel Admin</a>
                <?php endif; ?>
                <a href="index.php?action=logout" style="color: #ef4444; font-weight: 500; margin-left: 15px;">Sair</a>
            </nav>

        </div>
    </header>

    <div class="home-container" style="margin-top: 0; padding-top: 140px; padding-bottom: 60px; box-sizing: border-box; display: flex; justify-content: center; align-items: center;">
        
        <div class="card-sucesso" style="background: white; border-radius: 16px; box-shadow: 0 10px 30px rgba(45, 36, 30, 0.06); padding: 40px 30px; text-align: center; max-width: 550px; width: 100%; border: 1px solid #f1eeea;">
            
            <div class="icone-check" style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 2.5rem; margin: 0 auto 24px;">
                ✓
            </div>

            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #2d241e; margin-bottom: 12px;">🎉 Pedido Recebido!</h1>
            
            <p style="font-family: 'Plus Jakarta Sans', sans-serif; color: #705c47; font-size: 1.1rem; line-height: 1.6; margin-bottom: 24px;">
                Obrigado por comprar na <strong>Vinil e Groove</strong>. O seu pagamento está sendo processado e o seu pedido começará a ser preparado logo em seguida.
            </p>

            <?php if (!empty($pedidoId)): ?>
                <div style="background: #fcfbfa; border: 1px dashed #e7e2dc; border-radius: 8px; padding: 12px; margin-bottom: 30px;">
                    <span style="font-size: 0.9rem; color: #a18c76; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Código do Pedido</span>
                    <strong style="font-size: 1.3rem; color: #2d241e; font-family: monospace;">#<?= htmlspecialchars($pedidoId) ?></strong>
                </div>
            <?php endif; ?>

            <div style="border-top: 1px solid #f1eeea; padding-top: 24px;">
                <p style="color: #a18c76; font-size: 0.9rem; margin-bottom: 20px;">Você receberá atualizações do andamento no seu e-mail cadastrado.</p>
                
                <a href="index.php?action=home" class="btn-voltar-vitrine" style="background: #2d241e; color: #fcfbfa; padding: 14px 28px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; transition: background 0.2s; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 4px 12px rgba(45, 36, 30, 0.15);">
                    💿 Voltar para os Discos
                </a>
            </div>

        </div>

    </div>

</body>
</html>