<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function login(): void {
        if (isLoggedIn() && isAdmin()) redirect('/admin/dashboard');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identificador = sanitize($_POST['identificador'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (!$identificador || !$senha) {
                $erro = 'Preencha e-mail e senha.';
                require __DIR__ . '/../views/shared/login.php';
                return;
            }

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->autenticar($identificador, $senha);

            if ($usuario && $usuario['tipo'] === 'admin') {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome']       = $usuario['nome'];
                $_SESSION['tipo']       = $usuario['tipo'];
                redirect('/admin/dashboard');
            } else {
                $erro = 'Credenciais inválidas ou acesso não autorizado.';
                require __DIR__ . '/../views/shared/login.php';
            }
            return;
        }

        require __DIR__ . '/../views/shared/login.php';
    }

    public function loginCidadao(): void {
        if (isLoggedIn() && !isAdmin()) redirect('/cidadao/dashboard');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identificador = sanitize($_POST['identificador'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (!$identificador || !$senha) {
                $erro = 'Preencha o e-mail ou CPF e a senha.';
                require __DIR__ . '/../views/portal/login_cidadao.php';
                return;
            }

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->autenticar($identificador, $senha);

            if ($usuario && $usuario['tipo'] === 'cidadao') {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome']       = $usuario['nome'];
                $_SESSION['tipo']       = $usuario['tipo'];
                redirect('/cidadao/dashboard');
            } else {
                $erro = 'Credenciais inválidas.';
                require __DIR__ . '/../views/portal/login_cidadao.php';
            }
            return;
        }

        require __DIR__ . '/../views/portal/login_cidadao.php';
    }

    public function cadastro(): void {
        if (isLoggedIn() && !isAdmin()) redirect('/cidadao/dashboard');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome     = sanitize($_POST['nome'] ?? '');
            $email    = sanitize($_POST['email'] ?? '');
            $telefone = sanitize($_POST['telefone'] ?? '');
            $cpf      = sanitize($_POST['cpf'] ?? '');
            $senha    = $_POST['senha'] ?? '';
            $confirma = $_POST['confirma_senha'] ?? '';

            $erros = [];
            if (!$telefone)           $erros[] = 'Telefone é obrigatório.';
            if (!$nome)               $erros[] = 'Nome é obrigatório.';
            if (!$email)              $erros[] = 'E-mail é obrigatório.';
            if (!$cpf)                $erros[] = 'CPF é obrigatório.';
            if (strlen($senha) < 6)   $erros[] = 'Senha deve ter no mínimo 6 caracteres.';
            if ($senha !== $confirma) $erros[] = 'As senhas não coincidem.';

            if (empty($erros)) {
                $usuarioModel = new Usuario();
                if ($usuarioModel->emailOuCpfExiste($email, $cpf)) {
                    $erros[] = 'E-mail ou CPF já cadastrado.';
                }
            }

            if (!empty($erros)) {
                $erro = implode(' ', $erros);
                require __DIR__ . '/../views/portal/cadastro.php';
                return;
            }

            $usuarioModel = new Usuario();
            if ($usuarioModel->criar($nome, $email, $senha, 'cidadao', $cpf, $telefone)) {
                $usuario = $usuarioModel->buscarPorEmail($email);
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome']       = $usuario['nome'];
                $_SESSION['tipo']       = 'cidadao';
                redirect('/cidadao/dashboard');
            } else {
                $erro = 'Erro ao criar conta. Tente novamente.';
                require __DIR__ . '/../views/portal/cadastro.php';
            }
            return;
        }

        require __DIR__ . '/../views/portal/cadastro.php';
    }

    public function logout(): void {
        $tipo = $_SESSION['tipo'] ?? 'cidadao';
        session_destroy();
        redirect($tipo === 'admin' ? '/login' : '/entrar');
    }
}
