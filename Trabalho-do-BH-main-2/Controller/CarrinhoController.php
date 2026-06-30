<?php
class CarrinhoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    /**
     * ROTA: Exibe a página do carrinho com os produtos processados
     */
    public function exibir() {
        $itens_carrinho = [];
        $valor_total_carrinho = 0.00;

        if (!empty($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
                try {
                    // CORREÇÃO CRÍTICA: Trocado '' AS foto por p.foto para capturar o dado dinâmico do banco
                    $sql = "SELECT 
                                p.id, 
                                p.nome, 
                                v.preco AS preco_venda,
                                p.foto
                            FROM produto p
                            INNER JOIN variacao v ON p.id = v.produto_id
                            WHERE p.id = ? 
                            LIMIT 1";
                    
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$produto_id]);
                    $produto = $stmt->fetch();

                    if ($produto) {
                        $produto['quantidade'] = $quantidade;
                        $produto['subtotal'] = $produto['preco_venda'] * $quantidade;
                        
                        $valor_total_carrinho += $produto['subtotal'];
                        $itens_carrinho[] = $produto;
                    }
                } catch (PDOException $e) {
                    // Falha silenciosa
                }
            }
        }

        // Inclui a view carregando as variáveis exatas que ela requisita ($itens_carrinho e $valor_total_carrinho)
        include dirname(__DIR__) . '/views/carrinho_view.php';
    }

    /**
     * ROTA: Adiciona um produto ao carrinho ou incrementa se já existir
     */
    public function adicionar() {
        $produto_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($produto_id) {
            if (isset($_SESSION['carrinho'][$produto_id])) {
                $_SESSION['carrinho'][$produto_id]++;
            } else {
                $_SESSION['carrinho'][$produto_id] = 1;
            }
        }

        header("Location: index.php?action=carrinho");
        exit;
    }

    /**
     * ROTA: Atualiza a quantidade quando alterada no input numérico
     */
    public function atualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $produto_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $quantidade = filter_input(INPUT_POST, 'whitespace', FILTER_DEFAULT); // Ignora focar no input fantasma
            $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);

            if ($produto_id && $quantidade && $quantidade > 0) {
                $_SESSION['carrinho'][$produto_id] = $quantidade;
            } elseif ($produto_id && $quantidade <= 0) {
                unset($_SESSION['carrinho'][$produto_id]);
            }
        }

        header("Location: index.php?action=carrinho");
        exit;
    }

    /**
     * ROTA: Remove um item específico do carrinho
     */
    public function remover() {
        $produto_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($produto_id && isset($_SESSION['carrinho'][$produto_id])) {
            unset($_SESSION['carrinho'][$produto_id]);
        }

        header("Location: index.php?action=carrinho");
        exit;
    }
}