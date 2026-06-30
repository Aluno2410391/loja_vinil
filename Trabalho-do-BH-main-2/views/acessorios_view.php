<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acessórios - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/accessorios.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/reset.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-acessorios">

    <header class="navbar">
        <div class="home-container">
            <div class="logo-wrapper">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove">
            </div>
            <nav class="nav-links">
                <a href="index.php?action=home"><span>💿</span> Discos</a>
                <a href="index.php?action=acessorios" class="active"><span>🎧</span> Acessórios</a>
                <a href="index.php?action=carrinho"><span>🛒</span> Carrinho</a>
                <a href="index.php?action=suporte"><span>❓</span> Suporte</a>
                
                <?php if (!isset($_SESSION['usuario_id'])): ?>
                    <a href="index.php?action=login" class="btn-nav-login">Entrar</a>
                <?php else: ?>
                    <span class="user-name" style="margin-right: 10px; color: #4b5563; font-weight: 500;">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
                    <?php if (isset($_SESSION['usuario_perfil']) && ($_SESSION['usuario_perfil'] === 'administrador' || $_SESSION['usuario_perfil'] === 'admin')): ?>
                        <a href="index.php?action=adm" class="btn-nav-adm" style="color: #d97706; font-weight: 600; margin-right: 10px;">⚙️ Painel Admin</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="btn-nav-logout" style="color: #ef4444; font-weight: 500;">Sair</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="acessorios-hero">
        <div class="home-container">
            <h1>Equipamentos & Acessórios</h1>
            <p>Tudo o que precisa para cuidar da sua coleção e elevar a sua experiência sonora para o próximo nível.</p>
        </div>
    </div>

    <div class="home-container">
        <?php if (!empty($produtos)): ?>
            <div class="grid-discos">
                <?php foreach ($produtos as $produto): ?>
                    
                    <?php 
                    // MANTIDA INTEGRALMENTE A SUA LÓGICA DE PERSISTÊNCIA ORIGINAL
                    $fotoAcessorio = (!empty($produto['foto'])) 
                        ? htmlspecialchars($produto['foto']) 
                        : "https://images.unsplash.com/photo-1603048588665-791ca8aea617?q=80&w=500";
                    ?>

                    <div class="card-disco">
                        
                        <div class="container-imagem">
                            <img src="<?= $fotoAcessorio ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                        </div>

                        <div class="infos-produto">
                            <div>
                                <span class="produto-tag" style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; color: #d97706; font-weight: 600; display: block; margin-bottom: 4px;"><?= htmlspecialchars($produto['categoria'] ?? 'Acessório') ?></span>
                                <h3 style="margin-top: 5px;"><?= htmlspecialchars($produto['nome']) ?></h3>
                            </div>
                            
                            <div style="margin-top: 15px;">
                                <p class="preco">R$ <?= number_format($produto['preco_venda'], 2, ',', '.') ?></p>
                                <a href="index.php?action=carrinho_adicionar&id=<?= $produto['id'] ?>" class="btn-adicionar" style="width: 100%; display: block; text-align: center;">Adicionar ao Carrinho</a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            
            <div style="text-align: center; margin: 60px 0; padding: 40px; background: rgba(255,255,255,0.5); border-radius: 12px;">
                <p style="color: #705c47; font-style: italic; font-size: 1.1rem; margin: 0;">
                    Nenhum equipamento ou acessório disponível em estoque no momento. Volte logo!
                </p>
            </div>
            
        <?php endif; ?>
    </div>

</body>
</html>