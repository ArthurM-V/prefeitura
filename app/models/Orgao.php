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

    public function criar(string $nome, string $descricao = ''): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO orgaos (nome, descricao) VALUES (?, ?)"
        );
        return $stmt->execute([$nome, $descricao ?: null]);
    }

    public function atualizar(int $id, string $nome, string $descricao = ''): bool {
        $stmt = $this->db->prepare(
            "UPDATE orgaos SET nome = ?, descricao = ? WHERE id = ?"
        );
        return $stmt->execute([$nome, $descricao ?: null, $id]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM orgaos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
