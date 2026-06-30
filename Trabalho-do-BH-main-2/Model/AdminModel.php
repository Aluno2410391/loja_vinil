<?php
class AdminModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Busca as últimas movimentações registradas na tabela de estoque
     */
    public function getUltimasMovimentacoes($limite = 5) {
        try {
            // Ajuste os nomes das colunas e tabelas caso seu banco use sinônimos
            $sql = "SELECT 
                        id, 
                        variacao_id, 
                        quantidade, 
                        tipo, 
                        origem, 
                        data_movimento 
                    FROM movimentacao_estoque 
                    ORDER BY data_movimento DESC 
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Busca o histórico das vendas faturadas recentemente
     */
    public function getVendasRecentes($limite = 5) {
        try {
            $sql = "SELECT 
                        id, 
                        usuario_id, 
                        data, 
                        status, 
                        valor_total 
                    FROM venda 
                    ORDER BY data DESC 
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}