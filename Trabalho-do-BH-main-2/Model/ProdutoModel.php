<?php

class ProdutoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Insere um novo produto/acessório na tabela 'produtos' ou 'produto'
     */
    public function cadastrar($nome, $categoria, $preco_venda, $foto, $descricao) {
        // Altere para 'produto' no singular caso seu banco siga o mesmo padrão de 'usuario'
        $sql = "INSERT INTO produtos (nome, categoria, preco_venda, foto, descricao) 
                VALUES (?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $nome,
                $categoria,
                $preco_venda,
                !empty($foto) ? $foto : null,
                $descricao
            ]);
        } catch (PDOException $e) {
            // Log de erro ou tratamento específico se necessário
            return false;
        }
    }
}