<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Vinil e Groove</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/views/css/adm.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body class="page-admin">

    <header class="navbar">
        <div class="home-container" style="padding-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; padding: 10px 0;">
                <img src="<?= BASE_URL ?>/views/img/Gemini_Generated_Image_ttm0vrttm0vrttm0.png" alt="Logo Vinil & Groove" style="max-height: 55px; border-radius:15px;">
                <nav class="nav-links">
                    <a href="index.php?action=home">💿 Discos</a>
                    <a href="index.php?action=acessorios">🎧 Acessórios</a>
                    <a href="index.php?action=carrinho">🛒 Carrinho</a>
                    <a href="index.php?action=suporte">❓ Suporte</a>
                    <a href="index.php?action=admin" class="active">⚙️ Painel Admin</a>
                    <a href="index.php?action=logout" style="color: #ef4444;">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="admin-container">
        
        <header class="admin-header">
            <h1>⚙️ Painel de Controle</h1>
            <p>Gerenciamento de estoque, auditoria e vendas da loja.</p>
        </header>

        <div class="admin-dashboard-grid">
            <div class="metric-card vendas">
                <span class="card-title">Fluxo de Caixa</span>
                <span class="card-value">Ativo</span>
            </div>
            <div class="metric-card estoque">
                <span class="card-title">Auditoria</span>
                <span class="card-value">Ligada</span>
            </div>
        </div>

        <section class="admin-section">
            <h2>📦 Últimas Movimentações de Estoque</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Variação do Produto</th>
                            <th>Quantidade</th>
                            <th>Operação</th>
                            <th>Origem / Destino</th>
                            <th>Data e Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($movimentacoes) && is_array($movimentacoes)): ?>
                            <?php foreach ($movimentacoes as $mov): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($mov['id']) ?></td>
                                    <td>Produto ID: <?= htmlspecialchars($mov['variacao_id']) ?></td>
                                    <td><strong><?= htmlspecialchars($mov['quantidade']) ?> un</strong></td>
                                    <td>
                                        <span class="badge <?= ($mov['tipo'] === 'saida' || $mov['tipo'] === 'Saída') ? 'saida' : 'entrada' ?>">
                                            <?= htmlspecialchars($mov['tipo']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($mov['origem'] ?? 'Não informada') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($mov['data_movimento'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #705c47; font-style: italic; padding: 20px;">
                                    Nenhuma movimentação de estoque encontrada ou registrada no momento.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-section">
            <h2>🛒 Vendas Recentes</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>ID do Cliente</th>
                            <th>Data da Compra</th>
                            <th>Estado do Pedido</th>
                            <th>Total Cobrado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($vendasRecentes) && is_array($vendasRecentes)): ?>
                            <?php foreach ($vendasRecentes as $venda): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($venda['id']) ?></td>
                                    <td>Cliente ID: <?= htmlspecialchars($venda['usuario_id']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($venda['data'])) ?></td>
                                    <td>
                                        <span class="badge <?= ($venda['status'] === 'pago' || $venda['status'] === 'Aprovado') ? 'pago' : 'pendente' ?>">
                                            <?= htmlspecialchars($venda['status']) ?>
                                        </span>
                                    </td>
                                    <td><strong>R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #705c47; font-style: italic; padding: 20px;">
                                    Nenhum histórico de venda faturado ou localizado recentemente.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</body>
</html>