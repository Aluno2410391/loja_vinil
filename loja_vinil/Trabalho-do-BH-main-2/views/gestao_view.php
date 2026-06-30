<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/adm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-admin" style="margin: 0; padding: 0;">

    <header class="navbar" style="width: 100%; position: fixed; top: 0; left: 0; right: 0; z-index: 1000; box-sizing: border-box;">
        <div class="home-container" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin: 0 auto; background: transparent; padding: 0 20px; box-sizing: border-box;">
            
            <div class="logo-wrapper">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove" style="max-height: 55px; border-radius:15px;">
            </div>
            
            <nav class="nav-links">
                <a href="index.php?action=home">💿 Discos</a>
                <a href="index.php?action=acessorios">🎧 Acessórios</a>
                <a href="index.php?action=adm">⚙️ Painel Admin</a>
                <a href="index.php?action=admin_gestao_produtos" class="active">📦 Gestão de Produtos</a>
                <a href="index.php?action=logout" class="btn-nav-logout" style="color: #ef4444; font-weight: 500; margin-left: 15px;">Sair</a>
            </nav>

        </div>
    </header>

    <div class="home-container" style="margin-top: 0; padding-top: 120px; box-sizing: border-box;">

        <div class="secao-titulo" style="display: flex; justify-content: space-between; align-items: center; margin: 20px 0 20px;">
            <div>
                <h1 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #2d241e;">📦 Gestão de Produtos</h1>
                <p style="color: #705c47; margin-top: 5px;">Visualize, edite ou remova os itens cadastrados no catálogo da sua loja.</p>
            </div>
            <a href="index.php?action=admin_produto_novo" class="btn-adicionar" style="background: #10b981; color: white; padding: 12px 20px; border-radius: 8px; font-weight: 600; text-decoration: none;">+ Novo Produto</a>
        </div>

        <?php if (isset($_GET['sucesso_exclusao'])): ?>
            <div style="background: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; border: 1px solid #fca5a5;">
                🗑️ Produto removido com sucesso do catálogo!
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['sucesso_edicao'])): ?>
            <div style="background: #ecfdf5; color: #047857; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; border: 1px solid #6ee7b7;">
                ✅ Alterações do produto salvas com sucesso!
            </div>
        <?php endif; ?>

        <section class="tabela-container" style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 20px; margin-top: 20px; overflow-x: auto;">
            <table class="tabela-adm" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb; color: #4b5563; font-weight: 600;">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Foto</th>
                        <th style="padding: 12px;">Nome do Produto</th>
                        <th style="padding: 12px;">Tipo</th>
                        <th style="padding: 12px;">Preço</th>
                        <th style="padding: 12px; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($todosProdutos)): ?>
                        <?php foreach ($todosProdutos as $prod): ?>
                            <?php 
                            // Fallback visual de imagem idêntico ao das vitrines
                            $fotoExibicao = !empty($prod['foto']) ? htmlspecialchars($prod['foto']) : 'https://images.unsplash.com/photo-1539628399213-d6aa89c93074?w=80&q=80';
                            ?>
                            <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;">
                                <td style="padding: 14px; color: #6b7280;">#<?= $prod['id'] ?></td>
                                <td style="padding: 14px;">
                                    <img src="<?= $fotoExibicao ?>" alt="Capa" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                </td>
                                <td style="padding: 14px; font-weight: 600; color: #1f2937;"><?= htmlspecialchars($prod['nome']) ?></td>
                                <td style="padding: 14px;"><span class="badge" style="background: #f3f4f6; color: #4b5563; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; text-transform: capitalize;"><?= $prod['tipo'] ?></span></td>
                                <td style="padding: 14px; font-weight: 700; color: #b45309;">R$ <?= number_format($prod['preco_venda'], 2, ',', '.') ?></td>
                                <td style="padding: 14px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="index.php?action=admin_produto_editar&id=<?= $prod['id'] ?>" style="background: #fbbf24; color: #78350f; padding: 8px 12px; border-radius: 6px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: 0.2s;">✏️ Editar</a>
                                        
                                        <a href="index.php?action=admin_produto_excluir&id=<?= $prod['id'] ?>" onclick="return confirm('Deseja realmente excluir permanentemente o produto: <?= htmlspecialchars($prod['nome']) ?>?');" style="background: #ef4444; color: white; padding: 8px 12px; border-radius: 6px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: 0.2s;">🗑️ Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #705c47; font-style: italic; padding: 30px;">Nenhum produto foi localizado no estoque da aplicação.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </div>
</body>
</html>