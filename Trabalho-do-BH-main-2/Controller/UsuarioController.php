<?php
class UsuarioController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Processa a criação de contas no sistema
     */
    public function salvar() {
        $erro = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $senha_confirmar = $_POST['senha_confirmar'] ?? '';

            if (empty($nome) || empty($email) || empty($senha) || empty($senha_confirmar)) {
                $erro = "Por favor, preencha todos os campos obrigatórios.";
                include dirname(__DIR__) . '/views/cadastro_view.php';
                return;
            }

            if ($senha !== $senha_confirmar) {
                $erro = "As senhas digitadas não coincidem.";
                include dirname(__DIR__) . '/views/cadastro_view.php';
                return;
            }

            if (strlen($senha) < 6) {
                $erro = "A senha deve conter no mínimo 6 caracteres.";
                include dirname(__DIR__) . '/views/cadastro_view.php';
                return;
            }

            require_once dirname(__DIR__) . '/Model/UsuarioModel.php';
            $usuarioModel = new UsuarioModel($this->pdo);

            if ($usuarioModel->emailExiste($email)) {
                $erro = "Este endereço de e-mail já está sendo utilizado.";
                include dirname(__DIR__) . '/views/cadastro_view.php';
                return;
            }

            if ($usuarioModel->cadastrar($nome, $email, $senha)) {
                header("Location: index.php?action=login&sucesso=1");
                exit;
            } else {
                $erro = "Ocorreu um erro interno ao salvar o usuário. Verifique as restrições do banco.";
                include dirname(__DIR__) . '/views/cadastro_view.php';
                return;
            }
        }
    }

    /**
     * Processa a autenticação híbrida (Texto puro para contas legadas e HASH para novas)
     */
   public function autenticar() {
    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? ''; // Senha que o usuário digitou no formulário

        if (empty($email) || empty($senha)) {
            $erro = "Por favor, preencha o e-mail e a senha.";
            include dirname(__DIR__) . '/views/login_view.php';
            return;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = ? AND ativo = 1");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
        } catch (PDOException $e) {
            $erro = "Erro de Execução no Banco: " . $e->getMessage();
            include dirname(__DIR__) . '/views/login_view.php';
            return;
        }

        if ($usuario) {
            $loginSucesso = false;
            $precisaAtualizarHash = false;
            
            $senhaBanco = $usuario['senha'];

            // VERIFICAÇÃO VISUAL COMPACTA E INFALÍVEL:
            // Um hash gerado pelo password_hash() do PHP sempre começa com '$2y$' e tem exatamente 60 caracteres.
            if (strpos($senhaBanco, '$2y$') === 0 && strlen($senhaBanco) === 60) {
                // CASO A: É um HASH moderno seguro
                if (password_verify($senha, $senhaBanco)) {
                    $loginSucesso = true;
                }
            } else {
                // CASO B: É TEXTO PURO (Como o '123456' que está no seu SQL)
                // Usamos trim() para garantir que espaços invisíveis não quebrem a comparação
                if (trim($senha) === trim($senhaBanco)) {
                    $loginSucesso = true;
                    $precisaAtualizarHash = true; // Força a virar hash criptografado
                }
            }

            // SE COINCIDIR POR QUALQUER UM DOS DOIS MÉTODOS:
            if ($loginSucesso) {
                
                // Atualiza o banco para o formato moderno de forma invisível
                if ($precisaAtualizarHash) {
                    try {
                        $novoHash = password_hash($senha, PASSWORD_DEFAULT);
                        $update = $this->pdo->prepare("UPDATE usuario SET senha = ? WHERE id = ?");
                        $update->execute([$novoHash, $usuario['id']]);
                    } catch (PDOException $e) {
                        // Silencia o erro para não travar o fluxo do usuário
                    }
                }

                // Inicia a sessão com os dados do banco
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_perfil'] = $usuario['perfil']; // 'admin' ou 'vendedor'

                header("Location: index.php?action=home");
                exit;
            }
        }

        // Se falhar em todas as verificações
        $erro = "E-mail ou senha incorretos. Tente novamente.";
        include dirname(__DIR__) . '/views/login_view.php';
        return;
    }
}
}