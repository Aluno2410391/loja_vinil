<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto - Painel Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/Produto.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-admin-cadastro">

    <header class="navbar">
        <div class="home-container">
            <div class="logo-wrapper">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove">
            </div>
            <nav class="nav-links">
                <a href="index.php?action=admin">⚙️ Voltar ao Painel</a>
                <a href="index.php?action=home">💿 Ver Loja</a>
                <a href="index.php?action=logout" style="color: #ef4444 !important;">Sair</a>
            </nav>
        </div>
    </header>

    <div class="admin-container">
        
        <a href="index.php?action=admin" class="btn-voltar">⬅️ Voltar para a listagem</a>

        <div class="admin-card">
            
            <header class="admin-header">
                <h2>📦 Novo Produto</h2>
                <p>Insira as informações comerciais e técnicas para disponibilizar o item na vitrine da loja.</p>
            </header>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger" style="background-color: #fee2e2; color: #991b1b; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fca5a5; font-size: 14px; font-weight: 500;">
                    ⚠️ <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=admin_produto_salvar" method="POST" class="admin-form">
                
                <div class="form-group">
                    <label for="nome">Nome do Produto / Álbum</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Pink Floyd - The Dark Side of the Moon" required>
                </div>

                <div class="form-row-duplo">
                    <div class="form-group">
                        <label for="categoria">Categoria do Item</label>
                        <select id="categoria" name="categoria" required>
                            <option value="Discos">💿 Discos de Vinil</option>
                            <option value="Acessorios">🎧 Equipamentos & Acessórios</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="categoria_musical">Gênero / Categoria Musical</label>
                        <select id="categoria_musical" name="categoria_musical">
                            <option value="1">🎸 Rock</option>
                            <option value="2">🎤 Pop</option>
                            <option value="3">🎧 Hip Hop</option>
                            <option value="4">🎶 MPB</option>
                            <option value="5">🔌 Eletrônica</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="preco_venda">Preço de Venda (R$)</label>
                        <input type="number" id="preco_venda" name="preco_venda" step="0.01" placeholder="0.00" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="foto">URL da Imagem do Produto</label>
                    <input type="url" id="foto" name="foto" placeholder="https://exemplo.com/imagem.jpg">
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição / Detalhes do Produto</label>
                    <textarea id="descricao" name="descricao" placeholder="Escreva aqui detalhes sobre o estado de conservação, faixas do álbum ou especificações do equipamento..." required></textarea>
                </div>

                <button type="submit" class="btn-salvar-produto">
                    💾 Gravar e Publicar Produto
                </button>
            </form>

        </div>
    </div>

</body>
</html>

Por Desconhecido - <a rel="nofollow" class="external free" href="https://www.correio24horas.com.br/noticia/nid/baladas-epicas-historias-de-boates-que-marcaram-epoca-em-salvador/">https://www.correio24horas.com.br/noticia/nid/baladas-epicas-historias-de-boates-que-marcaram-epoca-em-salvador/</a>, <a href="//pt.wikipedia.org/wiki/Ficheiro:Michael_Jackson_-_Thriller.jpg" title="Conteúdo restrito">Conteúdo restrito</a>, <a href="https://pt.wikipedia.org/w/index.php?curid=2873452">Hiperligação</a>