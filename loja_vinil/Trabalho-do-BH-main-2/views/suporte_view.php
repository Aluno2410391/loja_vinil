<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central de Suporte - Vinil e Groove</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/Suporte.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body class="page-suporte">
    <header class="navbar">
        <div class="home-container" style="padding-bottom: 0; padding-top: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; padding: 10px 0;">
                <div class="logo-wrapper">
                    <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove" style="max-height: 55px; width: auto; border-radius:15px;">
                </div>
                <nav class="nav-links">
                    <a href="index.php?action=home"><span>💿</span> Discos</a>
                    <a href="index.php?action=acessorios"><span>🎧</span> Acessórios</a>
                    <a href="index.php?action=carrinho"><span>🛒</span> Carrinho</a>
                    <a href="index.php?action=suporte" class="active"><span>❓</span> Suporte</a>
                    
                    <?php if (!empty($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'administrador'): ?>
                        <a href="index.php?action=admin" style="background-color: #222; color: #fff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);">⚙️ Painel Admin</a>
                    <?php endif; ?>
                    
                    <a href="index.php?action=logout" style="color: #ef4444; margin-left: 15px;">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="suporte-container">
        
        <header class="suporte-header">
            <h2>❓ Central de Suporte</h2>
            <p>Precisa de ajuda com um pedido, rastreamento ou tem dúvidas sobre os nossos vinis? Estamos aqui para ajudar!</p>
        </header>

        <div class="suporte-grid">
            
            <div class="suporte-canais">
                
                <div class="canal-card">
                    <span class="canal-icone">📧</span>
                    <div class="canal-info">
                        <h3>E-mail Oficial</h3>
                        <p>suporte@vinilegroove.com</p>
                    </div>
                </div>
                
                <div class="canal-card">
                    <span class="canal-icone">📱</span>
                    <div class="canal-info">
                        <h3>WhatsApp</h3>
                        <p>(11) 99999-9999</p>
                    </div>
                </div>
                
                <div class="canal-card">
                    <span class="canal-icone">⏰</span>
                    <div class="canal-info">
                        <h3>Horário de Funcionamento</h3>
                        <p>Segunda a Sexta, das 9h às 18h</p>
                    </div>
                </div>
                
            </div>

            <div class="suporte-form-box">
                <h3>Envie um Ticket de Atendimento</h3>
                
                <?php if (!empty($sucesso)): ?>
                    <div class="alert-sucesso">
                        ✅ Sua mensagem foi enviada com sucesso! Nossa equipe entrará em contato em até 24h úteis.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?action=suporte_enviar" class="suporte-form">
                    
                    <div class="form-group">
                        <label for="assunto">Qual é o motivo do contacto?</label>
                        <select id="assunto" name="assunto" required>
                            <option value="">Selecione o assunto mais adequado...</option>
                            <option value="pedido">Dúvidas sobre um Pedido Efetuado</option>
                            <option value="entrega">Rastreamento ou Atraso na Entrega</option>
                            <option value="produto">Defeito ou Troca de Produto/Vinil</option>
                            <option value="outro">Sugestões, Elogios ou Outros Assuntos</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mensagem">Escreva a sua mensagem detalhadamente</label>
                        <textarea id="mensagem" name="mensagem" placeholder="Por favor, digite aqui as informações necessárias (como o número do pedido, se aplicável) para que possamos ajudar você da melhor forma..." required></textarea>
                    </div>

                    <button type="submit" class="btn-suporte-enviar">
                        🚀 Enviar Mensagem de Suporte
                    </button>
                    
                </form>
            </div>

        </div>
    </div>

</body>
</html>