<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,600&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-home">

    <header class="navbar" style="width: 100%; left: 0; top: 0; right: 0;">
        <div class="home-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin: 0 auto; background: transparent; padding: 0 20px; box-sizing: border-box;">
            
            <div class="logo-wrapper">
                <?php 
                $logoLocal = dirname(__DIR__) . '/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png';
                $logoUrl = (file_exists($logoLocal)) 
                    ? BASE_URL . '/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png' 
                    : 'https://images.unsplash.com/photo-1539628399213-d6aa89c93074?w=100&q=80';
                ?>
                <img src="<?= $logoUrl ?>" alt="Logo Vinil & Groove">
            </div>
            
            <nav class="nav-links">
                <a href="index.php?action=home" class="active"><span>💿</span> Discos</a>
                <a href="index.php?action=acessorios"><span>🎧</span> Acessórios</a>
                <a href="index.php?action=carrinho"><span>🛒</span> Carrinho</a>
                <a href="index.php?action=suporte"><span>❓</span> Suporte</a>
                
                <?php if (!empty($_SESSION['usuario_perfil']) && ($_SESSION['usuario_perfil'] === 'administrador' || $_SESSION['usuario_perfil'] === 'admin')): ?>
                    <a href="index.php?action=adm" class="btn-nav-adm" style="color: #d97706; font-weight: 600;">⚙️ Painel</a>
                    <a href="index.php?action=admin_gestao_produtos" class="btn-nav-gestao-produtos" style="color: #3b82f6; font-weight: 600; margin-left: 10px;">📊 Editar</a>
                    <a href="index.php?action=admin_produto_novo" class="btn-nav-cadastro-produto" style="color: #10b981; font-weight: 600; margin-left: 10px;">📦 Cadastrar</a>
                <?php endif; ?>
                
                <a href="index.php?action=logout" class="btn-logout" style="color: #ef4444; margin-left: 15px; font-weight: 500;">Sair</a>
            </nav>

        </div>
    </header>

    <div class="home-container" style="margin-top: 30px;">
        
        <div class="boas-vindas">
            <p>Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Cliente') ?></strong>! Bem-vindo de volta à experiência Vinil e Groove.</p>
        </div>

        <div class="secao-titulo" style="text-align: center; margin: 50px 0 30px;">
            <h2>🔥 Discos em Destaque</h2>
            <p style="color: #5c4d3c; font-size: 1.1rem; max-width: 600px; margin: 10px auto 0;">Explore nossa curadoria de vinis novos e clássicos essenciais para a sua coleção.</p>
        </div>

        <?php if (!empty($produtos)): ?>
            <div class="grid-discos">
                <?php foreach ($produtos as $disco): ?>
                    
                    <?php 
                    $fotoProduto = (!empty($disco['foto'])) 
                        ? htmlspecialchars($disco['foto']) 
                        : "https://images.unsplash.com/photo-1539628399213-d6aa89c93074?w=500&q=80";
                    ?>

                    <div class="card-disco">
                        <div class="container-imagem">
                            <img src="<?= $fotoProduto ?>" alt="<?= htmlspecialchars($disco['nome']) ?>">
                        </div>
                        
                        <div class="infos-produto">
                            <div>
                                <span class="produto-tag" style="font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; color: #7c2d12; font-weight: 600; display: block; margin-bottom: 4px;">
                                    <?= strtoupper(htmlspecialchars($disco['categoria'])) ?>
                                </span>
                                <h3><?= htmlspecialchars($disco['nome']) ?></h3>
                            </div>
                            <div style="margin-top: 15px;">
                                <p class="preco">R$ <?= number_format($disco['preco_venda'], 2, ',', '.') ?></p>
                                <a href="index.php?action=carrinho_adicionar&id=<?= $disco['id'] ?>" class="btn-adicionar" style="width: 100%; display: block; text-align: center;">Adicionar ao Carrinho</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; margin: 60px 0; padding: 40px; background: rgba(255,255,255,0.5); border-radius: 12px;">
                <p style="color: #705c47; font-style: italic; font-size: 1.15rem;">Nenhum álbum disponível na vitrine no momento.</p>
            </div>
        <?php endif; ?>
        
    </div>
</body>
</html>