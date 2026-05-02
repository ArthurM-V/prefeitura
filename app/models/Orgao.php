<?php

require_once __DIR__ . '/../../config/database.php';

class Orgao {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarTodos(): array {
        return $this->db->query("SELECT * FROM orgaos ORDER BY nome")->fetchAll();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM orgaos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
