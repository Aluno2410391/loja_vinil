<?php
class Venda {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Regista a venda completa e os seus itens usando a estrutura correta do banco
     */
    public function criarVenda($usuarioId, $total, $metodoPagamento, $itensCarrinho) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insere na tabela 'venda' conforme o seu SQL original
            $sqlVenda = "INSERT INTO venda (usuario_id, data, status, valor_total) 
                         VALUES (?, CURDATE(), 'aberta', ?)";
            $stmt = $this->pdo->prepare($sqlVenda);
            $stmt->execute([$usuarioId, $total]);
            
            $vendaId = $this->pdo->lastInsertId();

            // 2. Insere os itens vinculados na tabela 'venda_item'
            $sqlItem = "INSERT INTO venda_item (venda_id, variacao_id, quantidade, preco_unitario) 
                        VALUES (?, ?, ?, ?)";
            $stmtItem = $this->pdo->prepare($sqlItem);

            foreach ($itensCarrinho as $variacaoId => $item) {
                $stmtItem->execute([
                    $vendaId,
                    $variacaoId,
                    $item['quantidade'],
                    $item['preco']
                ]);
            }

            $this->pdo->commit();
            return $vendaId;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die("Erro crítico ao processar transação da venda: " . $e->getMessage());
        }
    }
}