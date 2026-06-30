<?php
require_once dirname(__DIR__) . '/Model/Venda.php';

class PagamentoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * MÉTODOS DE RENDERIZAÇÃO: 
     * Calcula o total geral acumulado do carrinho e exibe a tela de pagamento
     */
    public function exibirTelaPagamento() {
        // SEGURANÇA: Bloqueia acesso anônimo se não houver login ativo
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // Se o carrinho estiver vazio, impede o avanço e retorna para a página do carrinho
        if (empty($_SESSION['carrinho'])) {
            header("Location: index.php?action=carrinho");
            exit;
        }

        $totalGeral = 0;

        // Prepara a query para buscar o preço real de cada variação de produto ativa
        $stmtPreco = $this->pdo->prepare("SELECT preco FROM variacao WHERE produto_id = ? LIMIT 1");

        foreach ($_SESSION['carrinho'] as $produtoId => $dadosOuQuantidade) {
            // Verifica se a estrutura guarda um array completo ou apenas o inteiro da quantidade
            $quantidade = is_array($dadosOuQuantidade) ? $dadosOuQuantidade['quantidade'] : $dadosOuQuantidade;

            // Busca o preço real na tabela de variação para blindar contra adulterações no front-end
            $stmtPreco->execute([$produtoId]);
            $variacao = $stmtPreco->fetch();
            
            if ($variacao) {
                $precoReal = (float)$variacao['preco'];
                $totalGeral += ($precoReal * $quantidade);
            }
        }

        // Inclui a View disponibilizando a variável $totalGeral preenchida corretamente
        include dirname(__DIR__) . '/views/pagamento_view.php';
    }

    /**
     * MÉTODO DE PROCESSAMENTO (POST): 
     * Valida os dados enviados e persiste a compra no banco de dados de forma transacional
     */
    public function processar() {
        // SEGURANÇA: Impede faturamentos sem login ativo
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }

        // Valida se o carrinho possui registos ativos para faturar
        if (empty($_SESSION['carrinho'])) {
            header("Location: index.php?action=carrinho");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $metodo = filter_input(INPUT_POST, 'metodo', FILTER_DEFAULT);

            // Restringe a execução apenas aos métodos aceitos pelo formulário
            if (in_array($metodo, ['pix', 'cartao', 'boleto'])) {
                
                $totalGeral = 0;
                $itensProcessados = [];

                // Prepara a query para capturar o ID da variação e o preço unitário correto
                $stmtVariacao = $this->pdo->prepare("SELECT id, preco FROM variacao WHERE produto_id = ? LIMIT 1");

                foreach ($_SESSION['carrinho'] as $produtoId => $dadosOuQuantidade) {
                    $quantidade = is_array($dadosOuQuantidade) ? $dadosOuQuantidade['quantidade'] : $dadosOuQuantidade;

                    $stmtVariacao->execute([$produtoId]);
                    $variacao = $stmtVariacao->fetch();
                    
                    if ($variacao) {
                        $variacaoId = (int)$variacao['id'];
                        $precoReal = (float)$variacao['preco'];

                        // Acumula no somatório do montante total da venda
                        $totalGeral += ($precoReal * $quantidade);

                        // Organiza a lista indexada pela 'variacao_id' exigida pelo seu banco de dados
                        $itensProcessados[$variacaoId] = [
                            'quantidade' => $quantidade,
                            'preco' => $precoReal
                        ];
                    }
                }

                // Se houver itens estruturados com sucesso, inicia a persistência
                if (!empty($itensProcessados)) {
                    $usuarioId = $_SESSION['usuario_id'];
                    $modelVenda = new Venda($this->pdo);

                    // Dispara a gravação nas tabelas 'venda' e 'venda_item'
                    $vendaId = $modelVenda->criarVenda($usuarioId, $totalGeral, $metodo, $itensProcessados);

                    if ($vendaId) {
                        // Venda realizada com absoluto sucesso! Esvazia o carrinho da sessão
                        unset($_SESSION['carrinho']);
                        
                        // Redireciona para a tela de agradecimento passando o ID de registro
                        header("Location: index.php?action=pagamento_sucesso&pedido_id=" . $vendaId);
                        exit;
                    }
                }
            }
        }

        // Caso ocorra uma falha ou requisição inválida, retorna o cliente para a tela inicial de pagamento
        header("Location: index.php?action=pagamento");
        exit;
    }
}