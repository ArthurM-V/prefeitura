<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function login(): void {
        if (isLoggedIn() && isAdmin()) {
            redirect('/admin/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (!$email || !$senha) {
                $erro = 'Preencha e-mail e senha.';
                require __DIR__ . '/../views/shared/login.php';
                return;
            }

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->autenticar($email, $senha);

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

    public function logout(): void {
        session_destroy();
        redirect('/');
    }
}
