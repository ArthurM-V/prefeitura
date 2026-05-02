<?php

require_once __DIR__ . '/../../config/database.php';

class Categoria {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarTodas(): array {
        return $this->db->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
