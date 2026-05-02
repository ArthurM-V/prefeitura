<?php

require_once __DIR__ . '/../../config/database.php';

class Feedback {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function criar(string $mensagem, int $usuarioId, int $chamadoId): bool {
        $stmt = $this->db->prepare("INSERT INTO feedbacks (mensagem, usuario_id, chamado_id) VALUES (?, ?, ?)");
        return $stmt->execute([$mensagem, $usuarioId, $chamadoId]);
    }

    public function buscarPorChamado(int $chamadoId): array {
        $stmt = $this->db->prepare("
            SELECT f.*, u.nome as autor
            FROM feedbacks f
            JOIN usuarios u ON f.usuario_id = u.id
            WHERE f.chamado_id = ?
            ORDER BY f.data DESC
        ");
        $stmt->execute([$chamadoId]);
        return $stmt->fetchAll();
    }
}
