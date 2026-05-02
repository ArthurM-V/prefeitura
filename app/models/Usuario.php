<?php

require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function autenticar(string $email, string $senha): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return null;
    }

    public function criar(string $nome, string $email, string $senha, string $tipo = 'cidadao'): bool {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nome, $email, $hash, $tipo]);
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, nome, email, tipo, criado_em FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function buscarPorEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function listarTodos(): array {
        $stmt = $this->db->query("SELECT id, nome, email, tipo, criado_em FROM usuarios ORDER BY criado_em DESC");
        return $stmt->fetchAll();
    }
}
