<?php

require_once __DIR__ . '/../../config/database.php';

class Categoria {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarTodas(?int $limit = null, int $offset = 0): array {
        $sql = "SELECT * FROM categorias ORDER BY nome";
        if ($limit !== null) {
            $limit = max(1, $limit);
            $offset = max(0, $offset);
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return $this->db->query($sql)->fetchAll();
    }

    public function contarTodas(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
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
