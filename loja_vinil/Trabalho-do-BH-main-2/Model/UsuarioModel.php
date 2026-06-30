<?php
class UsuarioModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Verifica se o e-mail já está registrado na tabela 'usuario'
     */
    public function emailExiste($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM usuario WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Insere o novo usuário respeitando as colunas exatas do banco SQL
     */
    public function cadastrar($nome, $email, $senha) {
        try {
            // Criptografa a senha para o novo padrão seguro
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            
            // CORREÇÃO: O banco só aceita 'admin' ou 'vendedor'. Vamos definir 'vendedor' como padrão.
            $perfilPadrao = 'vendedor'; 
            $ativo = 1;

            $stmt = $this->pdo->prepare("
                INSERT INTO usuario (nome, email, senha, perfil, ativo) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([$nome, $email, $senhaHash, $perfilPadrao, $ativo]);
        } catch (PDOException $e) {
            // Em caso de erro, descomente a linha abaixo para debugar:
            // die("Erro no Model Cadastro: " . $e->getMessage());
            return false;
        }
    }
}