<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Orgao.php';
require_once __DIR__ . '/../models/Status.php';
require_once __DIR__ . '/../models/Feedback.php';
require_once __DIR__ . '/../models/Historico.php';
require_once __DIR__ . '/../models/Imagem.php';

class AdminController {

    public function dashboard(): void {
        requireAdmin();
        $chamadoModel = new Chamado();
        $historico    = new Historico();
        $totalGeral         = $chamadoModel->totalGeral();
        $totalStatus        = $chamadoModel->totalPorStatus();
        $totalOrgao         = $chamadoModel->totalPorOrgao();
        $atividadesRecentes = $historico->listarRecentes(8);
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    public function chamados(): void {
        requireAdmin();
        $chamadoModel   = new Chamado();
        $statusModel    = new Status();
        $categoriaModel = new Categoria();
        $orgaoModel     = new Orgao();

        $filtros = [
            'status_id'    => (int)($_GET['status_id'] ?? 0),
            'categoria_id' => (int)($_GET['categoria_id'] ?? 0),
            'orgao_id'     => (int)($_GET['orgao_id'] ?? 0),
        ];

        $chamados   = $chamadoModel->listarTodos(array_filter($filtros));
        $statuses   = $statusModel->listarTodos();
        $categorias = $categoriaModel->listarTodas();
        $orgaos     = $orgaoModel->listarTodos();

        require __DIR__ . '/../views/admin/chamados.php';
    }

    public function verChamado(int $id): void {
        requireAdmin();
        $chamadoModel   = new Chamado();
        $statusModel    = new Status();
        $orgaoModel     = new Orgao();
        $feedbackModel  = new Feedback();
        $historicoModel = new Historico();
        $imagemModel    = new Imagem();

        $chamado = $chamadoModel->buscarPorId($id);
        if (!$chamado) redirect('/admin/chamados');

        $statuses    = $statusModel->listarTodos();
        $orgaos      = $orgaoModel->listarTodos();
        $feedbacks   = $feedbackModel->buscarPorChamado($id);
        $historicos  = $historicoModel->listarPorChamado($id);
        $semelhantes = $chamadoModel->buscarSemelhantes($id);
        $imagens     = $imagemModel->buscarPorChamado($id);

        require __DIR__ . '/../views/admin/ver_chamado.php';
    }

    public function atribuirOrgao(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $chamadoId = (int)($_POST['chamado_id'] ?? 0);
        $orgaoId   = (int)($_POST['orgao_id'] ?? 0);
        if (!$chamadoId || !$orgaoId) jsonResponse(['success' => false, 'message' => 'Dados inválidos.'], 422);

        $chamadoModel   = new Chamado();
        $orgaoModel     = new Orgao();
        $historicoModel = new Historico();

        $chamado = $chamadoModel->buscarPorId($chamadoId);
        $orgao   = $orgaoModel->buscarPorId($orgaoId);

        if ($chamadoModel->atribuirOrgao($chamadoId, $orgaoId)) {
            if ($chamado['status_id'] == 1) $chamadoModel->alterarStatus($chamadoId, 2);
            $historicoModel->registrar('Atribuição', "Chamado atribuído ao órgão: {$orgao['nome']}.", $_SESSION['usuario_id'], $chamadoId);
            jsonResponse(['success' => true, 'message' => 'Órgão atribuído com sucesso.']);
        }
        jsonResponse(['success' => false, 'message' => 'Erro ao atribuir órgão.'], 500);
    }

    public function alterarStatus(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $chamadoId = (int)($_POST['chamado_id'] ?? 0);
        $statusId  = (int)($_POST['status_id'] ?? 0);
        if (!$chamadoId || !$statusId) jsonResponse(['success' => false, 'message' => 'Dados inválidos.'], 422);

        $chamadoModel   = new Chamado();
        $statusModel    = new Status();
        $historicoModel = new Historico();

        $status = $statusModel->buscarPorId($statusId);

        if ($chamadoModel->alterarStatus($chamadoId, $statusId)) {
            $historicoModel->registrar('Status', "Status alterado para: {$status['nome']}.", $_SESSION['usuario_id'], $chamadoId);
            jsonResponse(['success' => true, 'message' => 'Status atualizado.']);
        }
        jsonResponse(['success' => false, 'message' => 'Erro ao alterar status.'], 500);
    }

    public function enviarFeedback(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $chamadoId = (int)($_POST['chamado_id'] ?? 0);
        $mensagem  = sanitize($_POST['mensagem'] ?? '');
        if (!$chamadoId || !$mensagem) jsonResponse(['success' => false, 'message' => 'Mensagem não pode ser vazia.'], 422);

        $feedbackModel  = new Feedback();
        $chamadoModel   = new Chamado();
        $historicoModel = new Historico();

        if (!$feedbackModel->criar($mensagem, $_SESSION['usuario_id'], $chamadoId)) {
            jsonResponse(['success' => false, 'message' => 'Erro ao enviar feedback.'], 500);
        }

        // Upload de imagem do admin (opcional)
        $avisoImagem = null;
        if (!empty($_FILES['imagem']['name'])) {
            $imagemModel = new Imagem();
            $resultado = $imagemModel->salvar($_FILES['imagem'], $chamadoId, 'admin');
            if (!$resultado['success']) $avisoImagem = $resultado['message'];
        }

        $chamadoModel->alterarStatus($chamadoId, 4); // Resolvido
        $historicoModel->registrar('Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', $_SESSION['usuario_id'], $chamadoId);

        $msg = 'Feedback enviado e chamado marcado como resolvido.';
        if ($avisoImagem) $msg .= ' Aviso de imagem: ' . $avisoImagem;
        jsonResponse(['success' => true, 'message' => $msg]);
    }

    public function excluirChamado(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $chamadoId = (int)($_POST['chamado_id'] ?? 0);
        if (!$chamadoId) jsonResponse(['success' => false, 'message' => 'ID inválido.'], 422);

        $chamadoModel = new Chamado();
        if ($chamadoModel->excluir($chamadoId)) {
            jsonResponse(['success' => true, 'message' => 'Chamado excluído com sucesso.']);
        }
        jsonResponse(['success' => false, 'message' => 'Erro ao excluir chamado.'], 500);
    }
}