<?php

require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function autenticar(string $identificador, string $senha): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE email = ? OR cpf = ? LIMIT 1"
        );
        $stmt->execute([$identificador, $identificador]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return null;
    }

    public function criar(string $nome, string $email, string $senha, string $tipo = 'cidadao', string $cpf = ''): bool {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nome, email, cpf, senha, tipo) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nome, $email, $cpf ?: null, $hash, $tipo]);
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, nome, email, cpf, tipo, criado_em FROM usuarios WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function buscarPorEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function buscarPorCpf(string $cpf): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE cpf = ? LIMIT 1");
        $stmt->execute([$cpf]);
        return $stmt->fetch() ?: null;
    }

    public function emailOuCpfExiste(string $email, string $cpf, int $excluirId = 0): bool {
        $stmt = $this->db->prepare(
            "SELECT id FROM usuarios WHERE (email = ? OR cpf = ?) AND id != ? LIMIT 1"
        );
        $stmt->execute([$email, $cpf, $excluirId]);
        return (bool) $stmt->fetch();
    }

    public function listarTodos(): array {
        $stmt = $this->db->query(
            "SELECT id, nome, email, cpf, tipo, criado_em FROM usuarios ORDER BY criado_em DESC"
        );
        return $stmt->fetchAll();
    }
}