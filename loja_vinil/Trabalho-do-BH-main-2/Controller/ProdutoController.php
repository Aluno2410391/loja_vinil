<?php
class ProdutoController {
    private $pdo;

    /**
     * O construtor recebe a conexão PDO mapeada no index.php
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * ====================================================================
     * ROTA: Lista os Discos de Vinil na Home
     * ====================================================================
     */
    public function listarDiscos() {
        try {
            // p.foto busca direto a coluna criada na tabela produto
            $sql = "SELECT 
                        p.id, 
                        p.nome, 
                        p.descricao, 
                        p.foto,
                        c.nome AS categoria, 
                        v.preco AS preco_venda
                    FROM produto p
                    INNER JOIN variacao v ON p.id = v.produto_id
                    INNER JOIN categoria c ON p.categoria_id = c.id
                    WHERE p.tipo = 'vinil' AND p.ativo = 1
                    GROUP BY p.id";

            $stmt = $this->pdo->query($sql);
            $produtos = $stmt->fetchAll();
        } catch (PDOException $e) {
            $produtos = [];
        }

        // Carrega a view da Home passando a lista de produtos encontrada
        include dirname(__DIR__) . '/views/home_view.php';
    }

    /**
     * ====================================================================
     * ROTA: Lista os Equipamentos e Acessórios na Vitrine correspondente
     * ====================================================================
     */
    public function listarAcessorios() {
        try {
            // p.foto busca direto a coluna criada na tabela produto
            $sql = "SELECT 
                        p.id, 
                        p.nome, 
                        p.descricao, 
                        p.foto,
                        c.nome AS categoria, 
                        v.preco AS preco_venda
                    FROM produto p
                    INNER JOIN variacao v ON p.id = v.produto_id
                    INNER JOIN categoria c ON p.categoria_id = c.id
                    WHERE p.tipo = 'vitrola' AND p.ativo = 1
                    GROUP BY p.id";

            $stmt = $this->pdo->query($sql);
            $produtos = $stmt->fetchAll();
        } catch (PDOException $e) {
            $produtos = [];
        }

        // Carrega a view de acessórios passando os resultados
        include dirname(__DIR__) . '/views/acessorios_view.php';
    }

    /**
     * ====================================================================
     * ROTA: Processa o salvamento de um NOVO produto vindo do formulário
     * ====================================================================
     */
    public function salvar() {
        // SEGURANÇA: Bloqueia acesso se o perfil do usuário não for administrativo
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }

        $erro = "";
        $sucesso = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome        = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
            $categoria   = filter_input(INPUT_POST, 'categoria', FILTER_DEFAULT);
            $preco_venda = filter_input(INPUT_POST, 'preco_venda', FILTER_VALIDATE_FLOAT);
            $foto        = filter_input(INPUT_POST, 'foto', FILTER_VALIDATE_URL);
            $descricao   = filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT);

            // Validação simples de campos obrigatórios
            if (empty($nome) || empty($categoria) || $preco_venda === false || $preco_venda <= 0) {
                $erro = "Por favor, preencha todos os campos obrigatórios com valores válidos.";
                include dirname(__DIR__) . '/views/cadastro_produto_view.php';
                return;
            }

            $categoria_musical = filter_input(INPUT_POST, 'categoria_musical', FILTER_VALIDATE_INT);

            if ($categoria === 'Discos') {
                $tipo = 'vinil';
                $categoria_id = $categoria_musical;
            } else {
                $tipo = 'vitrola';
                $categoria_id = 5; // Eletrônica
            }

            try {
                $this->pdo->beginTransaction();

                // Incluída a coluna 'foto' no INSERT da tabela produto
                $sqlProduto = "INSERT INTO produto (nome, descricao, tipo, categoria_id, foto, ativo) VALUES (?, ?, ?, ?, ?, 1)";
                $stmtProd = $this->pdo->prepare($sqlProduto);
                $stmtProd->execute([$nome, $descricao, $tipo, $categoria_id, !empty($foto) ? $foto : null]);
                
                $produtoId = $this->pdo->lastInsertId();

                $sqlVariacao = "INSERT INTO variacao (produto_id, preco) VALUES (?, ?)";
                $stmtVar = $this->pdo->prepare($sqlVariacao);
                $stmtVar->execute([$produtoId, $preco_venda]);

                $this->pdo->commit();

                header("Location: index.php?action=adm&sucesso_cadastro=1");
                exit;

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                $erro = "Erro ao salvar no banco de dados: " . $e->getMessage();
                include dirname(__DIR__) . '/views/cadastro_produto_view.php';
            }
        } else {
            include dirname(__DIR__) . '/views/cadastro_produto_view.php';
        }
    }

    /**
     * ====================================================================
     * ROTA NOVA: Lista todos os produtos ativos na tabela de Gestão (CRUD)
     * ====================================================================
     */
    public function listarGestao() {
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }

        try {
            // Reúne dados cruciais para a listagem da gestao_view
            $sql = "SELECT p.id, p.nome, p.tipo, p.foto, v.preco AS preco_venda 
                    FROM produto p
                    LEFT JOIN variacao v ON p.id = v.produto_id
                    WHERE p.ativo = 1
                    GROUP BY p.id 
                    ORDER BY p.id DESC";
            $stmt = $this->pdo->query($sql);
            $todosProdutos = $stmt->fetchAll();
        } catch (PDOException $e) {
            $todosProdutos = [];
        }

        include dirname(__DIR__) . '/views/gestao_view.php';
    }

    /**
     * ====================================================================
     * OPERAÇÃO CRUD - EXCLUIR: Soft Delete para segurança de integridade
     * ====================================================================
     */
    public function excluir() {
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            try {
                // Modifica o estado 'ativo' para 0 (Evita quebrar históricos de vendas)
                $stmt = $this->pdo->prepare("UPDATE produto SET ativo = 0 WHERE id = ?");
                $stmt->execute([$id]);
                header("Location: index.php?action=admin_gestao_produtos&sucesso_exclusao=1");
                exit;
            } catch (PDOException $e) {
                die("Erro crítico ao desativar/excluir o produto: " . $e->getMessage());
            }
        }
        header("Location: index.php?action=admin_gestao_produtos");
        exit;
    }

    /**
     * ====================================================================
     * OPERAÇÃO CRUD - EDITAR: Carrega dados originais ou processa o UPDATE
     * ====================================================================
     */
    public function editar() {
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }

        // Se o formulário de edição foi submetido via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nome        = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
            $categoria   = filter_input(INPUT_POST, 'categoria', FILTER_DEFAULT);
            $preco_venda = filter_input(INPUT_POST, 'preco_venda', FILTER_VALIDATE_FLOAT);
            $foto        = filter_input(INPUT_POST, 'foto', FILTER_DEFAULT); // Permite receber a string/URL limpa
            $descricao   = filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT);

            if ($id && !empty($nome) && $preco_venda !== false && $preco_venda > 0) {
                $tipo = ($categoria === 'Discos') ? 'vinil' : 'vitrola';
                $categoria_id = ($categoria === 'Discos') ? 1 : 2;

                try {
                    $this->pdo->beginTransaction();

                    // 1. Atualiza a tabela principal 'produto'
                    $sqlProd = "UPDATE produto SET nome = ?, descricao = ?, tipo = ?, categoria_id = ?, foto = ? WHERE id = ?";
                    $stmtProd = $this->pdo->prepare($sqlProd);
                    $stmtProd->execute([$nome, $descricao, $tipo, $categoria_id, !empty($foto) ? $foto : null, $id]);

                    // 2. Atualiza o preço correspondente na tabela 'variacao'
                    $sqlVar = "UPDATE variacao SET preco = ? WHERE produto_id = ?";
                    $stmtVar = $this->pdo->prepare($sqlVar);
                    $stmtVar->execute([$preco_venda, $id]);

                    $this->pdo->commit();
                    header("Location: index.php?action=admin_gestao_produtos&sucesso_edicao=1");
                    exit;
                } catch (PDOException $e) {
                    $this->pdo->rollBack();
                    die("Erro crítico ao salvar as alterações do produto: " . $e->getMessage());
                }
            }
        }

        // Se for uma requisição GET convencional, busca as informações para popular a view
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $stmt = $this->pdo->prepare("
                SELECT p.*, v.preco AS preco_venda 
                FROM produto p 
                LEFT JOIN variacao v ON p.id = v.produto_id 
                WHERE p.id = ? AND p.ativo = 1
            ");
            $stmt->execute([$id]);
            $produtoOriginal = $stmt->fetch();

            if ($produtoOriginal) {
                // Abre a view reaproveitando os inputs mapeados dinamicamente para Edição
                include dirname(__DIR__) . '/views/cadastro_produto_view.php';
                return;
            }
        }
        header("Location: index.php?action=admin_gestao_produtos");
        exit;
    }
}