<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Feedback.php';
require_once __DIR__ . '/../models/Historico.php';
require_once __DIR__ . '/../models/Imagem.php';

class CidadaoController {

    private function requireCidadao(): void {
        if (!isLoggedIn() || isAdmin()) redirect('/entrar');
    }

    public function dashboard(): void {
        $this->requireCidadao();

        $chamadoModel = new Chamado();
        $chamados = $chamadoModel->listarPorUsuario($_SESSION['usuario_id']);

        require __DIR__ . '/../views/cidadao/dashboard.php';
    }

    public function verChamado(int $id): void {
        $this->requireCidadao();

        $chamadoModel   = new Chamado();
        $feedbackModel  = new Feedback();
        $historicoModel = new Historico();
        $imagemModel    = new Imagem();

        $chamado = $chamadoModel->buscarPorId($id);

        if (!$chamado || $chamado['usuario_id'] != $_SESSION['usuario_id']) {
            redirect('/cidadao/dashboard');
        }

        $feedbacks  = $feedbackModel->buscarPorChamado($id);
        $historicos = $historicoModel->listarPorChamado($id);
        $imagens    = $imagemModel->buscarPorChamado($id);

        require __DIR__ . '/../views/cidadao/ver_chamado.php';
    }

    public function abrirChamado(): void {
        $this->requireCidadao();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(['error' => 'Método inválido.'], 405);
        }

        $titulo      = sanitize($_POST['titulo'] ?? '');
        $descricao   = sanitize($_POST['descricao'] ?? '');
        $localizacao = sanitize($_POST['localizacao'] ?? '');
        $categoriaId = (int)($_POST['categoria_id'] ?? 0);

        if (!$titulo || !$descricao || !$categoriaId) {
            jsonResponse(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.'], 422);
        }

        $chamadoModel = new Chamado();
        $chamadoId = $chamadoModel->criar($titulo, $descricao, $localizacao, $_SESSION['usuario_id'], $categoriaId);

        if (!$chamadoId) {
            jsonResponse(['success' => false, 'message' => 'Erro ao abrir chamado.'], 500);
        }

        if (!empty($_FILES['imagem']['name'])) {
            $imagemModel = new Imagem();
            $resultado = $imagemModel->salvar($_FILES['imagem'], $chamadoId, 'cidadao');
            if (!$resultado['success']) {
                
                $avisoImagem = $resultado['message'];
            }
        }

        $historico = new Historico();
        $historico->registrar('Abertura', 'Chamado aberto pelo cidadão.', $_SESSION['usuario_id'], $chamadoId);

        $msg = 'Chamado aberto com sucesso!';
        if (!empty($avisoImagem)) $msg .= ' Aviso de imagem: ' . $avisoImagem;

        jsonResponse(['success' => true, 'message' => $msg, 'id' => $chamadoId]);
    }
}