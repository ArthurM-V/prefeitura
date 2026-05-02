<?php

require_once __DIR__ . '/../../config/database.php';

class Historico {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function registrar(string $tipoAcao, string $descricao, int $usuarioId, int $chamadoId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO historico (tipo_acao, descricao, usuario_id, chamado_id) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$tipoAcao, $descricao, $usuarioId, $chamadoId]);
    }

    public function listarPorChamado(int $chamadoId): array {
        $stmt = $this->db->prepare("
            SELECT h.*, u.nome as usuario_nome
            FROM historico h
            JOIN usuarios u ON h.usuario_id = u.id
            WHERE h.chamado_id = ?
            ORDER BY h.data ASC
        ");
        $stmt->execute([$chamadoId]);
        return $stmt->fetchAll();
    }

    public function listarRecentes(int $limit = 10): array {
        $stmt = $this->db->prepare("
            SELECT h.*, u.nome as usuario_nome, c.titulo as chamado_titulo
            FROM historico h
            JOIN usuarios u ON h.usuario_id = u.id
            JOIN chamados c ON h.chamado_id = c.id
            ORDER BY h.data DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
