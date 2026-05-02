<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Feedback.php';
require_once __DIR__ . '/../models/Historico.php';

class PortalController {

    public function index(): void {
        $chamado = new Chamado();
        $categoria = new Categoria();
        $resolvidos = $chamado->listarResolvidos();
        $categorias = $categoria->listarTodas();
        require __DIR__ . '/../views/portal/index.php';
    }

    public function novoChamado(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome      = sanitize($_POST['nome'] ?? '');
            $email     = sanitize($_POST['email'] ?? '');
            $titulo    = sanitize($_POST['titulo'] ?? '');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $localizacao = sanitize($_POST['localizacao'] ?? '');
            $categoriaId = (int)($_POST['categoria_id'] ?? 0);

            if (!$nome || !$email || !$titulo || !$descricao || !$categoriaId) {
                jsonResponse(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.'], 422);
            }

            // Cria ou encontra usuário cidadão
            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->buscarPorEmail($email);
            if (!$usuario) {
                $usuarioModel->criar($nome, $email, bin2hex(random_bytes(8)));
                $usuario = $usuarioModel->buscarPorEmail($email);
            }

            $chamadoModel = new Chamado();
            $chamadoId = $chamadoModel->criar($titulo, $descricao, $localizacao, $usuario['id'], $categoriaId);

            if ($chamadoId) {
                $historico = new Historico();
                $historico->registrar('Abertura', 'Chamado aberto pelo cidadão via portal.', $usuario['id'], $chamadoId);
                jsonResponse(['success' => true, 'message' => 'Chamado enviado com sucesso! Número: #' . $chamadoId, 'id' => $chamadoId]);
            } else {
                jsonResponse(['success' => false, 'message' => 'Erro ao enviar chamado.'], 500);
            }
        }
    }
}
