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

    public function criar(string $nome, string $descricao = ''): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO categorias (nome, descricao) VALUES (?, ?)"
        );
        return $stmt->execute([$nome, $descricao ?: null]);
    }

    public function atualizar(int $id, string $nome, string $descricao = ''): bool {
        $stmt = $this->db->prepare(
            "UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?"
        );
        return $stmt->execute([$nome, $descricao ?: null, $id]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
