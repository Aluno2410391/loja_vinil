<?php
/**
 * Classe de Conexão com o Banco de Dados (Padrão Singleton)
 * Camada: Model / Infraestrutura
 */
class Conexao {
    // Armazena a instância da conexão de forma estática
    private static $instance = null;

    /**
     * Método estático para obter a conexão com o banco de dados
     * @return PDO
     */
    public static function getConexao() {
        // Se a instância ainda não existe, cria uma nova
        if (self::$instance === null) {
            try {
                // Configurações do seu banco de dados 'loja_vinil'
                $host = 'localhost';
                $dbname = 'loja_vinil';
                $user = 'root';        // Altere se o seu usuário do MySQL for diferente
                $password = '';        // Altere se o seu MySQL possuir senha

                // Inicializa a conexão PDO com codificação UTF-8 estável
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $password,
                    [
                        // Configura o PDO para lançar exceções em caso de erros no SQL
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        
                        // Define que o retorno padrão das consultas será um array associativo
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        
                        // Desativa a emulação de comandos preparados para mitigar ataques de SQL Injection
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                // Em ambiente de produção, salve o erro em um arquivo de log.
                // Exibimos a mensagem amigável para interromper a execução com segurança.
                die("Erro crítico de infraestrutura: Não foi possível conectar ao banco de dados `loja_vinil`. Detalhes: " . $e->getMessage());
            }
        }
        
        // Retorna a conexão ativa
        return self::$instance;
    }
}