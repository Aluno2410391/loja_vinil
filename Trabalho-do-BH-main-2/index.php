<?php
// Inicializa a sessão global para controle de login, carrinho e estados do sistema
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================================================
// MÁGICA DO MVC: DEFINE A URL BASE AUTOMATICAMENTE (Resolve o erro do CSS)
// ==========================================================================
// Descobre em qual pasta do XAMPP o projeto está rodando (ex: /Trabalho-do-BH-main/Trabalho-do-BH-main/)
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = rtrim($scriptName, '/') . '/';
define('BASE_URL', $baseUrl);

// ==========================================================================
// 1. CONEXÃO COM O BANCO DE DADOS (PDO)
// ==========================================================================
$host = 'localhost';
$dbname = 'loja_vinil';
$username = 'root';
$password = ''; // Ajuste a senha de acordo com o seu ambiente local (ex: 'root' ou vazia)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Erro crítico de conexão com o banco de dados: " . $e->getMessage());
}

// ==========================================================================
// 2. CAPTURA DA ROTA (ACTION)
// ==========================================================================
// Captura a ação enviada pela URL. Se nenhuma for informada, o padrão é a vitrine de discos ('home')
$action = $_GET['action'] ?? 'home';

// Inicializa variáveis de feedback globais que as Views podem renderizar
$erro = '';
$sucesso = '';

// ==========================================================================
// 3. ROTEADOR DO SISTEMA (DIRECIONAMENTO MVC BLINDADO)
// ==========================================================================
switch ($action) {

    // --- VITRINE: DISCOS DE VINIL ---
    case 'home':
        // SEGURANÇA: Se não houver dados de usuário na sessão, redireciona para o login
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/Controller/ProdutoController.php';
        $controller = new ProdutoController($pdo);
        $controller->listarDiscos();
        break;

    // --- VITRINE: EQUIPAMENTOS E ACESSÓRIOS ---
    case 'acessorios':
        // SEGURANÇA: Se não houver dados de usuário na sessão, redireciona para o login
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/Controller/ProdutoController.php';
        $controller = new ProdutoController($pdo);
        $controller->listarAcessorios();
        break;

    // --- FORMULÁRIO DE CADASTRO DE USUÁRIO ---
    case 'cadastro':
        include __DIR__ . '/views/cadastro_view.php';
        break;

    // --- PROCESSAMENTO DO CRUD (SALVAR NOVO USUÁRIO NO BANCO) ---
    case 'cadastro_salvar':
        require_once __DIR__ . '/Controller/UsuarioController.php';
        $controller = new UsuarioController($pdo);
        $controller->salvar();
        break;

    // --- TELA DE LOGIN ---
    case 'login':
        // Se veio um parâmetro de sucesso via GET (ex: após se cadastrar), captura a mensagem
        if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
            $sucesso = "Cadastro realizado com sucesso! Faça o seu login.";
        }
        include __DIR__ . '/views/login_view.php';
        break;

    // --- PROCESSAMENTO DA AUTENTICAÇÃO (LOGIN) ---
    case 'autenticar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'] ?? '';

            if ($email && !empty($senha)) {
                // Procura o usuário correspondente na tabela (no singular conforme ajustado por você)
                $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $usuario = $stmt->fetch();

                // Verifica se o usuário existe e se a senha criptografada confere
                if ($usuario && password_verify($senha, $usuario['senha'])) {
                    
                    // Alimenta as variáveis essenciais de sessão exigidas pelos validadores
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_perfil'] = $usuario['perfil']; // Ex: 'cliente', 'admin' ou 'administrador'

                    // Login autorizado com sucesso! Vai para a vitrine principal
                    header("Location: index.php?action=home");
                    exit;
                } else {
                    $erro = "E-mail ou senha incorretos.";
                }
            } else {
                $erro = "Por favor, insira um e-mail válido e a senha.";
            }
        }
        // Se a validação falhar, inclui a view de login mantendo o alerta de $erro preenchido
        include __DIR__ . '/views/login_view.php';
        break;

    // --- LOGOUT DO SISTEMA ---
    case 'logout':
        // Limpa a memória da sessão e destrói o cookie de autenticação
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?action=login");
        exit;

    // --- TELA DO SUPORTE ---
    case 'suporte':
        // SEGURANÇA: Bloqueia acesso anônimo
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }
        include __DIR__ . '/views/suporte_view.php';
        break;

    // --- PROCESSAMENTO DO FORMULÁRIO DE SUPORTE ---
    case 'suporte_enviar':
        // SEGURANÇA: Impede submissão de suporte sem login ativo
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $assunto = filter_input(INPUT_POST, 'assunto', FILTER_DEFAULT);
            $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_DEFAULT);

            if (!empty($assunto) && !empty($mensagem)) {
                $sucesso = "Mensagem enviada com sucesso!";
            } else {
                $erro = "Por favor, preencha todos os campos.";
            }
        }
        include __DIR__ . '/views/suporte_view.php';
        break;

    // --- TELA DO CARRINHO DE COMPRAS ---
    case 'carrinho':
        // SEGURANÇA: Bloqueia acesso anônimo
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/Controller/CarrinhoController.php';
        $controller = new CarrinhoController($pdo);
        $controller->exibir();
        break;

    // --- ROTA PARA ADICIONAR ITEM AO CARRINHO ---
    case 'carrinho_adicionar':
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/Controller/CarrinhoController.php';
        $controller = new CarrinhoController($pdo);
        $controller->adicionar();
        break;

    // --- ROTA PARA ATUALIZAR QUANTIDADE ---
    case 'carrinho_atualizar':
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/Controller/CarrinhoController.php';
        $controller = new CarrinhoController($pdo);
        $controller->atualizar();
        break;

    // --- ROTA PARA REMOVER ITEM DO CARRINHO ---
    case 'carrinho_remover':
        if (!isset($_SESSION['usuario_id']) && !isset($_SESSION['usuario_perfil'])) {
            header("Location: index.php?action=login");
            exit;
        }

        require_once __DIR__ . '/Controller/CarrinhoController.php';
        $controller = new CarrinhoController($pdo);
        $controller->remover();
        break;

    // --- PAINEL ADMINISTRATIVO (ADM) INTEGRADO ---
    case 'admin':
    case 'adm':
        // SEGURANÇA REFORÇADA: Tolera tanto 'admin' quanto 'administrador'
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }
        // MODIFICADO: Chama o controller para buscar movimentações e vendas direto do banco
        require_once __DIR__ . '/Controller/AdminController.php';
        $controller = new AdminController($pdo);
        $controller->index();
        break;

    // --- FORMULÁRIO DE CADASTRO DE PRODUTOS (ADMIN) ---
    case 'admin_produto_novo':
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }
        include __DIR__ . '/views/cadastro_produto_view.php';
        break;

    // --- PROCESSAMENTO DO CADASTRO DE PRODUTO ---
    case 'admin_produto_salvar':
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }
        require_once __DIR__ . '/Controller/ProdutoController.php';
        $controller = new ProdutoController($pdo);
        $controller->salvar();
        break;

    // ==========================================================================
    // INTEGRADO: INTEGRANTE DO NOVO ECOSSISTEMA DE GESTÃO (CRUD DE PRODUTOS)
    // ==========================================================================
    
    // --- INTEGRADO: TELA DE GESTÃO / LISTAGEM DA TABELA (CRUD) ---
    case 'admin_gestao_produtos':
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }
        require_once __DIR__ . '/Controller/ProdutoController.php';
        $controller = new ProdutoController($pdo);
        $controller->listarGestao();
        break;

    // --- INTEGRADO: EDITAR PRODUTO (CARREGA DADOS OU SALVA ALTERAÇÃO) ---
    case 'admin_produto_editar':
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }
        require_once __DIR__ . '/Controller/ProdutoController.php';
        $controller = new ProdutoController($pdo);
        $controller->editar();
        break;

    // --- INTEGRADO: EXCLUIR PRODUTO (SOFT DELETE SEGURO) ---
    case 'admin_produto_excluir':
        if (!isset($_SESSION['usuario_perfil']) || ($_SESSION['usuario_perfil'] !== 'administrador' && $_SESSION['usuario_perfil'] !== 'admin')) {
            header("Location: index.php?action=home");
            exit;
        }
        require_once __DIR__ . '/Controller/ProdutoController.php';
        $controller = new ProdutoController($pdo);
        $controller->excluir();
        break;

    // --- TELA DE PAGAMENTO / FINALIZAÇÃO ---
    case 'pagamento':
        // MODIFICADO: Agora chama o método do controlador para computar o valor total do carrinho
        require_once __DIR__ . '/Controller/PagamentoController.php';
        $controller = new PagamentoController($pdo);
        $controller->exibirTelaPagamento();
        break;

    // --- ECOSSISTEMA DE CHECKOUT: PROCESSAR O PAGAMENTO DE FATO (POST) ---
    case 'processar_pagamento':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        require_once __DIR__ . '/Controller/PagamentoController.php';
        $controller = new PagamentoController($pdo);
        $controller->processar();
        break;

    // --- ECOSSISTEMA DE CHECKOUT: TELA DE SUCESSO PÓS-COMPRA ---
    case 'pagamento_sucesso':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
        // Captura opcional do ID do pedido via GET para exibição na View de Sucesso
        $pedidoId = filter_input(INPUT_GET, 'pedido_id', FILTER_VALIDATE_INT);
        include __DIR__ . '/views/pagamento_sucesso_view.php';
        break;

    // --- ROTA PADRÃO DE SEGURANÇA ---
    default:
        // Caso digitem uma rota inexistente na URL, redireciona para a página principal
        header("Location: index.php?action=home");
        exit;
}