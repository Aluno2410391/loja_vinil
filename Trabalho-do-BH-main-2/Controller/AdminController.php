<?php
require_once dirname(__DIR__) . '/Model/AdminModel.php';

class AdminController {
    private $pdo;
    private $model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new AdminModel($pdo);
    }

    /**
     * Renderiza o painel administrativo alimentando as tabelas com dados do banco
     */
    public function index() {
        // SEGURANÇA REFORÇADA: Impede o acesso se o usuário não for um administrador autenticado
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }

        // Consulta os dados reais diretamente do banco de dados utilizando o Model
        $movimentacoes = $this->model->getUltimasMovimentacoes(5);
        $vendasRecentes = $this->model->getVendasRecentes(5);

        // Carrega a view administrativa disponibilizando as variáveis acima para o HTML/PHP
        include dirname(__DIR__) . '/views/adm_view.php';
    }
}