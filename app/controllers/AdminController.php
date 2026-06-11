<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Orgao.php';
require_once __DIR__ . '/../models/Status.php';
require_once __DIR__ . '/../models/Feedback.php';
require_once __DIR__ . '/../models/Historico.php';
require_once __DIR__ . '/../models/Imagem.php';
require_once __DIR__ . '/../models/Empresa.php';
require_once __DIR__ . '/../models/Usuario.php';

class AdminController {

    private function calcularPaginacao(int $totalItens, int $porPagina = 10): array {
        $paginaAtual = max(1, (int)($_GET['page'] ?? 1));
        $totalPaginas = max(1, (int)ceil($totalItens / $porPagina));
        $paginaAtual = min($paginaAtual, $totalPaginas);
        $offset = ($paginaAtual - 1) * $porPagina;

        return [$porPagina, $paginaAtual, $totalPaginas, $offset];
    }

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
        $empresaModel   = new Empresa();

        $filtros = [
            'status_id'    => (int)($_GET['status_id'] ?? 0),
            'categoria_id' => (int)($_GET['categoria_id'] ?? 0),
            'orgao_id'     => (int)($_GET['orgao_id'] ?? 0),
            'empresa_id'   => (int)($_GET['empresa_id'] ?? 0),
        ];
        $filtrosAtivos = array_filter($filtros);

        $porPagina = 10;
        $paginaAtual = max(1, (int)($_GET['page'] ?? 1));
        $totalChamados = $chamadoModel->contarTodos($filtrosAtivos);
        $totalPaginas = max(1, (int)ceil($totalChamados / $porPagina));
        $paginaAtual = min($paginaAtual, $totalPaginas);
        $offset = ($paginaAtual - 1) * $porPagina;

        $chamados   = $chamadoModel->listarTodos($filtrosAtivos, $porPagina, $offset);
        $statuses   = $statusModel->listarTodos();
        $categorias = $categoriaModel->listarTodas();
        $orgaos     = $orgaoModel->listarTodos();
        $empresas   = $empresaModel->listarTodas(true);

        require __DIR__ . '/../views/admin/chamados.php';
    }

    public function verChamado(int $id): void {
        requireAdmin();
        $chamadoModel   = new Chamado();
        $statusModel    = new Status();
        $categoriaModel = new Categoria();
        $orgaoModel     = new Orgao();
        $empresaModel   = new Empresa();
        $feedbackModel  = new Feedback();
        $historicoModel = new Historico();
        $imagemModel    = new Imagem();

        $chamado = $chamadoModel->buscarPorId($id);
        if (!$chamado) redirect('/admin/chamados');

        $statuses    = $statusModel->listarTodos();
        $categorias  = $categoriaModel->listarTodas();
        $orgaos      = $orgaoModel->listarTodos();
        $empresas    = $empresaModel->listarTodas(true);
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

    public function atribuirEmpresa(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $chamadoId = (int)($_POST['chamado_id'] ?? 0);
        $empresaId = (int)($_POST['empresa_id'] ?? 0);
        if (!$chamadoId) jsonResponse(['success' => false, 'message' => 'Chamado inválido.'], 422);

        $chamadoModel   = new Chamado();
        $empresaModel   = new Empresa();
        $historicoModel = new Historico();

        $empresa = $empresaId ? $empresaModel->buscarPorId($empresaId) : null;
        if ($empresaId && !$empresa) {
            jsonResponse(['success' => false, 'message' => 'Empresa não encontrada.'], 404);
        }

        if ($chamadoModel->atribuirEmpresa($chamadoId, $empresaId ?: null)) {
            $descricao = $empresa
                ? "Chamado atribuído à empresa parceira: {$empresa['nome']}."
                : 'Empresa parceira removida do chamado.';
            $historicoModel->registrar('Empresa', $descricao, $_SESSION['usuario_id'], $chamadoId);
            jsonResponse(['success' => true, 'message' => 'Empresa parceira atualizada com sucesso.']);
        }
        jsonResponse(['success' => false, 'message' => 'Erro ao atualizar empresa parceira.'], 500);
    }

    public function atualizarChamado(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $chamadoId   = (int)($_POST['chamado_id'] ?? 0);
        $titulo      = sanitize($_POST['titulo'] ?? '');
        $descricao   = sanitize($_POST['descricao'] ?? '');
        $localizacao = sanitize($_POST['localizacao'] ?? '');
        $categoriaId = (int)($_POST['categoria_id'] ?? 0);
        $statusId    = (int)($_POST['status_id'] ?? 0);
        $orgaoId     = (int)($_POST['orgao_id'] ?? 0);
        $empresaId   = (int)($_POST['empresa_id'] ?? 0);

        if (!$chamadoId || !$titulo || !$descricao || !$categoriaId || !$statusId) {
            jsonResponse(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.'], 422);
        }

        $chamadoModel   = new Chamado();
        $historicoModel = new Historico();

        if ($chamadoModel->atualizar($chamadoId, $titulo, $descricao, $localizacao, $categoriaId, $statusId, $orgaoId ?: null, $empresaId ?: null)) {
            $historicoModel->registrar('Edição', 'Dados do chamado atualizados pelo administrador.', $_SESSION['usuario_id'], $chamadoId);
            jsonResponse(['success' => true, 'message' => 'Chamado atualizado com sucesso.']);
        }
        jsonResponse(['success' => false, 'message' => 'Erro ao atualizar chamado.'], 500);
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

    public function usuarios(): void {
        requireAdmin();
        $usuarioModel = new Usuario();
        $totalItens = $usuarioModel->contarTodos();
        [$porPagina, $paginaAtual, $totalPaginas, $offset] = $this->calcularPaginacao($totalItens);
        $usuarios = $usuarioModel->listarTodos($porPagina, $offset);
        require __DIR__ . '/../views/admin/usuarios.php';
    }

    public function salvarUsuario(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id       = (int)($_POST['id'] ?? 0);
        $nome     = sanitize($_POST['nome'] ?? '');
        $email    = sanitize($_POST['email'] ?? '');
        $telefone = sanitize($_POST['telefone'] ?? '');
        $cpf      = sanitize($_POST['cpf'] ?? '');
        $tipo     = sanitize($_POST['tipo'] ?? 'cidadao');
        $senha    = $_POST['senha'] ?? '';

        if (!$nome || !$email || !in_array($tipo, ['cidadao', 'admin'], true)) {
            jsonResponse(['success' => false, 'message' => 'Dados inválidos.'], 422);
        }

        $usuarioModel = new Usuario();
        if ($usuarioModel->emailOuCpfExiste($email, $cpf, $id)) {
            jsonResponse(['success' => false, 'message' => 'E-mail ou CPF ja cadastrado.'], 422);
        }

        if ($id) {
            $ok = $usuarioModel->atualizar($id, $nome, $email, $telefone, $cpf, $tipo);
            if ($ok && $senha) $ok = $usuarioModel->atualizarSenha($id, $senha);
        } else {
            if (strlen($senha) < 6) {
                jsonResponse(['success' => false, 'message' => 'Senha deve ter no mínimo 6 caracteres.'], 422);
            }
            $ok = $usuarioModel->criar($nome, $email, $senha, $tipo, $cpf, $telefone);
        }

        jsonResponse([
            'success' => (bool)$ok,
            'message' => $ok ? 'Usuário salvo com sucesso.' : 'Erro ao salvar usuário.',
        ], $ok ? 200 : 500);
    }

    public function excluirUsuario(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id = (int)($_POST['id'] ?? 0);
        if (!$id || $id === (int)$_SESSION['usuario_id']) {
            jsonResponse(['success' => false, 'message' => 'Usuário inválido para exclusão.'], 422);
        }

        $usuarioModel = new Usuario();
        $ok = $usuarioModel->excluir($id);
        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Usuário excluído com sucesso.' : 'Erro ao excluir usuário.',
        ], $ok ? 200 : 500);
    }

    public function categorias(): void {
        requireAdmin();
        $categoriaModel = new Categoria();
        $totalItens = $categoriaModel->contarTodas();
        [$porPagina, $paginaAtual, $totalPaginas, $offset] = $this->calcularPaginacao($totalItens);
        $categorias = $categoriaModel->listarTodas($porPagina, $offset);
        require __DIR__ . '/../views/admin/categorias.php';
    }

    public function salvarCategoria(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id        = (int)($_POST['id'] ?? 0);
        $nome      = sanitize($_POST['nome'] ?? '');
        $descricao = sanitize($_POST['descricao'] ?? '');
        if (!$nome) jsonResponse(['success' => false, 'message' => 'Nome é obrigatório.'], 422);

        $categoriaModel = new Categoria();
        $ok = $id
            ? $categoriaModel->atualizar($id, $nome, $descricao)
            : $categoriaModel->criar($nome, $descricao);

        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Categoria salva com sucesso.' : 'Erro ao salvar categoria.',
        ], $ok ? 200 : 500);
    }

    public function excluirCategoria(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) jsonResponse(['success' => false, 'message' => 'Categoria inválida.'], 422);

        $categoriaModel = new Categoria();
        $ok = $categoriaModel->excluir($id);
        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Categoria excluída com sucesso.' : 'Erro ao excluir categoria.',
        ], $ok ? 200 : 500);
    }

    public function orgaos(): void {
        requireAdmin();
        $orgaoModel = new Orgao();
        $totalItens = $orgaoModel->contarTodos();
        [$porPagina, $paginaAtual, $totalPaginas, $offset] = $this->calcularPaginacao($totalItens);
        $orgaos = $orgaoModel->listarTodos($porPagina, $offset);
        require __DIR__ . '/../views/admin/orgaos.php';
    }

    public function salvarOrgao(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id        = (int)($_POST['id'] ?? 0);
        $nome      = sanitize($_POST['nome'] ?? '');
        $descricao = sanitize($_POST['descricao'] ?? '');
        if (!$nome) jsonResponse(['success' => false, 'message' => 'Nome é obrigatório.'], 422);

        $orgaoModel = new Orgao();
        $ok = $id
            ? $orgaoModel->atualizar($id, $nome, $descricao)
            : $orgaoModel->criar($nome, $descricao);

        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Órgão salvo com sucesso.' : 'Erro ao salvar órgão.',
        ], $ok ? 200 : 500);
    }

    public function excluirOrgao(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) jsonResponse(['success' => false, 'message' => 'Órgão inválido.'], 422);

        $orgaoModel = new Orgao();
        $ok = $orgaoModel->excluir($id);
        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Órgão excluído com sucesso.' : 'Erro ao excluir órgão.',
        ], $ok ? 200 : 500);
    }

    public function empresas(): void {
        requireAdmin();
        $empresaModel = new Empresa();
        $totalItens = $empresaModel->contarTodas();
        [$porPagina, $paginaAtual, $totalPaginas, $offset] = $this->calcularPaginacao($totalItens);
        $empresas = $empresaModel->listarTodas(false, $porPagina, $offset);
        require __DIR__ . '/../views/admin/empresas.php';
    }

    public function salvarEmpresa(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id          = (int)($_POST['id'] ?? 0);
        $nome        = sanitize($_POST['nome'] ?? '');
        $cnpj        = sanitize($_POST['cnpj'] ?? '');
        $email       = sanitize($_POST['email'] ?? '');
        $telefone    = sanitize($_POST['telefone'] ?? '');
        $responsavel = sanitize($_POST['responsavel'] ?? '');
        $areaAtuacao = sanitize($_POST['area_atuacao'] ?? '');
        $ativo       = isset($_POST['ativo']) ? 1 : 0;

        if (!$nome) jsonResponse(['success' => false, 'message' => 'Nome é obrigatório.'], 422);

        $empresaModel = new Empresa();
        $ok = $id
            ? $empresaModel->atualizar($id, $nome, $cnpj, $email, $telefone, $responsavel, $areaAtuacao, $ativo)
            : $empresaModel->criar($nome, $cnpj, $email, $telefone, $responsavel, $areaAtuacao, $ativo);

        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Empresa salva com sucesso.' : 'Erro ao salvar empresa.',
        ], $ok ? 200 : 500);
    }

    public function excluirEmpresa(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonResponse(['error' => 'Método inválido.'], 405);

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) jsonResponse(['success' => false, 'message' => 'Empresa inválida.'], 422);

        $empresaModel = new Empresa();
        $ok = $empresaModel->excluir($id);
        jsonResponse([
            'success' => $ok,
            'message' => $ok ? 'Empresa excluída com sucesso.' : 'Erro ao excluir empresa.',
        ], $ok ? 200 : 500);
    }
}
