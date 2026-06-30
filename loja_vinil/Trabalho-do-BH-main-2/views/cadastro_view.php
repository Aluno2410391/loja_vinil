<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Vinil e Groove</title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/cadastro.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2 family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-cadastro">

    <header class="navbar">
        <div class="home-container">
            <div class="logo-wrapper">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove">
            </div>
            <nav class="nav-links">
                <a href="index.php?action=home">💿 Discos</a>
                <a href="index.php?action=acessorios">🎧 Acessórios</a>
                <a href="index.php?action=carrinho">🛒 Carrinho</a>
                <a href="index.php?action=suporte">❓ Suporte</a>
            </nav>
        </div>
    </header>

    <div class="cadastro-container">
        <div class="cadastro-card">
            
            <header class="cadastro-header">
                <h2>Crie sua Conta</h2>
                <p>Junte-se à comunidade Vinil e Groove e comece a montar sua coleção premium.</p>
            </header>

            <?php if (!empty($erro)): ?>
                <div class="alert-erro">
                    <span>⚠️</span> <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?action=cadastro_salvar" method="POST" class="cadastro-form">
                
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" required 
                           value="<?= !empty($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seuemail@provedor.com" required
                           value="<?= !empty($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="No mínimo 6 caracteres" minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="senha_confirmar">Confirme a Senha</label>
                    <input type="password" id="senha_confirmar" name="senha_confirmar" placeholder="Digite a senha novamente" minlength="6" required>
                </div>

                <button type="submit" class="btn-cadastro-submeter">
                    ✨ Finalizar Meu Cadastro
                </button>
            </form>

            <footer class="cadastro-footer">
                Já faz parte do clube? <a href="index.php?action=login">Faça Login aqui</a>
            </footer>

        </div>
    </div>

</body>
</html>