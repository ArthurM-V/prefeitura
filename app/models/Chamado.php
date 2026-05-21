<?php

require_once __DIR__ . '/../../config/database.php';

class Chamado {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function criar(string $titulo, string $descricao, string $localizacao, int $usuarioId, int $categoriaId): int|false {
        $stmt = $this->db->prepare(
            "INSERT INTO chamados (titulo, descricao, localizacao, usuario_id, categoria_id, status_id) VALUES (?, ?, ?, ?, ?, 1)"
        );
        $stmt->execute([$titulo, $descricao, $localizacao, $usuarioId, $categoriaId]);
        return $this->db->lastInsertId();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nome as usuario_nome, u.email as usuario_email,
                   cat.nome as categoria_nome, s.nome as status_nome, s.ordem as status_ordem,
                   o.nome as orgao_nome, e.nome as empresa_nome
            FROM chamados c
            JOIN usuarios u ON c.usuario_id = u.id
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN status s ON c.status_id = s.id
            LEFT JOIN orgaos o ON c.orgao_id = o.id
            LEFT JOIN empresas e ON c.empresa_id = e.id
            WHERE c.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function listarTodos(array $filtros = []): array {
        $sql = "
            SELECT c.*, u.nome as usuario_nome, cat.nome as categoria_nome,
                   s.nome as status_nome, o.nome as orgao_nome, e.nome as empresa_nome
            FROM chamados c
            JOIN usuarios u ON c.usuario_id = u.id
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN status s ON c.status_id = s.id
            LEFT JOIN orgaos o ON c.orgao_id = o.id
            LEFT JOIN empresas e ON c.empresa_id = e.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filtros['status_id'])) {
            $sql .= " AND c.status_id = ?";
            $params[] = $filtros['status_id'];
        }
        if (!empty($filtros['categoria_id'])) {
            $sql .= " AND c.categoria_id = ?";
            $params[] = $filtros['categoria_id'];
        }
        if (!empty($filtros['orgao_id'])) {
            $sql .= " AND c.orgao_id = ?";
            $params[] = $filtros['orgao_id'];
        }
        if (!empty($filtros['empresa_id'])) {
            $sql .= " AND c.empresa_id = ?";
            $params[] = $filtros['empresa_id'];
        }

        $sql .= " ORDER BY c.data_abertura DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function listarPorUsuario(int $usuarioId): array {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.nome as categoria_nome, s.nome as status_nome,
                   o.nome as orgao_nome, e.nome as empresa_nome
            FROM chamados c
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN status s ON c.status_id = s.id
            LEFT JOIN orgaos o ON c.orgao_id = o.id
            LEFT JOIN empresas e ON c.empresa_id = e.id
            WHERE c.usuario_id = ?
            ORDER BY c.data_abertura DESC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    public function listarResolvidos(): array {
        $stmt = $this->db->query("
            SELECT c.*, cat.nome as categoria_nome, s.nome as status_nome,
                   o.nome as orgao_nome, e.nome as empresa_nome,
                   f.mensagem as feedback_mensagem, i.caminho as imagem_admin
            FROM chamados c
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN status s ON c.status_id = s.id
            LEFT JOIN orgaos o ON c.orgao_id = o.id
            LEFT JOIN empresas e ON c.empresa_id = e.id
            LEFT JOIN feedbacks f ON f.chamado_id = c.id
            LEFT JOIN imagens i ON i.chamado_id = c.id AND i.tipo = 'admin'
            WHERE c.status_id = 4
            ORDER BY c.data_fechamento DESC
            LIMIT 10
        ");
        return $stmt->fetchAll();
    }

    public function alterarStatus(int $id, int $statusId): bool {
        $fechamento = ($statusId == 4 || $statusId == 5) ? ", data_fechamento = NOW()" : "";
        $stmt = $this->db->prepare("UPDATE chamados SET status_id = ? $fechamento WHERE id = ?");
        return $stmt->execute([$statusId, $id]);
    }

    public function atribuirOrgao(int $id, int $orgaoId): bool {
        $stmt = $this->db->prepare("UPDATE chamados SET orgao_id = ? WHERE id = ?");
        return $stmt->execute([$orgaoId, $id]);
    }

    public function atribuirEmpresa(int $id, ?int $empresaId): bool {
        $stmt = $this->db->prepare("UPDATE chamados SET empresa_id = ? WHERE id = ?");
        return $stmt->execute([$empresaId ?: null, $id]);
    }

    public function atualizar(
        int $id,
        string $titulo,
        string $descricao,
        string $localizacao,
        int $categoriaId,
        int $statusId,
        ?int $orgaoId = null,
        ?int $empresaId = null
    ): bool {
        $fechamento = ($statusId == 4 || $statusId == 5) ? ", data_fechamento = NOW()" : ", data_fechamento = NULL";
        $stmt = $this->db->prepare(
            "UPDATE chamados
             SET titulo = ?, descricao = ?, localizacao = ?, categoria_id = ?, status_id = ?,
                 orgao_id = ?, empresa_id = ? $fechamento
             WHERE id = ?"
        );
        return $stmt->execute([
            $titulo,
            $descricao,
            $localizacao ?: null,
            $categoriaId,
            $statusId,
            $orgaoId ?: null,
            $empresaId ?: null,
            $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM chamados WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function buscarSemelhantes(int $id): array {
        $chamado = $this->buscarPorId($id);
        if (!$chamado) return [];

        $stmt = $this->db->prepare("
            SELECT c.*, cat.nome as categoria_nome, s.nome as status_nome
            FROM chamados c
            JOIN categorias cat ON c.categoria_id = cat.id
            JOIN status s ON c.status_id = s.id
            WHERE c.categoria_id = ? AND c.id != ?
            ORDER BY c.data_abertura DESC
            LIMIT 5
        ");
        $stmt->execute([$chamado['categoria_id'], $id]);
        return $stmt->fetchAll();
    }

    public function totalPorStatus(): array {
        $stmt = $this->db->query("
            SELECT s.nome, COUNT(c.id) as total
            FROM status s
            LEFT JOIN chamados c ON c.status_id = s.id
            GROUP BY s.id, s.nome
            ORDER BY s.ordem
        ");
        return $stmt->fetchAll();
    }

    public function totalPorOrgao(): array {
        $stmt = $this->db->query("
            SELECT o.nome, COUNT(c.id) as total
            FROM orgaos o
            LEFT JOIN chamados c ON c.orgao_id = o.id
            GROUP BY o.id, o.nome
            ORDER BY total DESC
        ");
        return $stmt->fetchAll();
    }

    public function totalGeral(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM chamados")->fetchColumn();
    }
}
