<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vinil e Groove</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/login.css">
   
</head>
<body class="page-login">
    <div class="login-master-container">
        
        <div class="login-banner">
            <img src="https://tse2.mm.bing.net/th/id/OIP.DL9Fzs7ao4dtQXvbSfdTyQHaHa?cb=thfc1falcon&rs=1&pid=ImgDetMain&o=7&rm=3" alt="Vinil & Groove Clássicos">
        </div>

        <div class="login-section">
            <div class="login-header">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" class="logo-login" alt="Logo">
                <h1>Acesse sua Conta</h1>
                <p>Entre para gerenciar seus pedidos e discos favoritos.</p>
            </div>

            <?php if (!empty($sucesso)): ?>
                <div class="alert alert-success" style="background-color: #d1fae5; color: #065f46; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #a7f3d0; font-size: 14px; font-weight: 500;">
                    ✅ <?= htmlspecialchars($sucesso) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger">⚠️ <?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=autenticar">
                <div class="form-group">
                    <label for="email">E-mail Cadastrado</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="senha">Sua Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-login-enviar">Entrar na Loja</button>
            </form>

            <div class="login-footer">
                Não tem uma conta? <a href="index.php?action=cadastro">Cadastre-se aqui</a>
            </div>
        </div>
    </div>
</body>
</html>