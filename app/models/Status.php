<?php

require_once __DIR__ . '/../../config/database.php';

class Status {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarTodos(): array {
        return $this->db->query("SELECT * FROM status ORDER BY ordem")->fetchAll();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM status WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function proximoStatus(int $atualId): ?array {
        $stmt = $this->db->prepare("
            SELECT * FROM status WHERE ordem > (SELECT ordem FROM status WHERE id = ?)
            ORDER BY ordem ASC LIMIT 1
        ");
        $stmt->execute([$atualId]);
        return $stmt->fetch() ?: null;
    }
}
