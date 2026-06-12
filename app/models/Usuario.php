<?php

require_once __DIR__ . '/../../config/database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    private function somenteDigitosCpf(string $cpf): string {
        return preg_replace('/\D/', '', $cpf);
    }

    public function autenticar(string $identificador, string $senha): ?array {
        $cpfLimpo = $this->somenteDigitosCpf($identificador);
        $stmt = $this->db->prepare(
            "SELECT *
             FROM usuarios
             WHERE email = ?
                OR cpf = ?
                OR REPLACE(REPLACE(cpf, '.', ''), '-', '') = ?
             LIMIT 1"
        );
        $stmt->execute([$identificador, $identificador, $cpfLimpo]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return null;
    }

    public function criar(string $nome, string $email, string $senha, string $tipo = 'cidadao', string $cpf = '', string $telefone = ''): bool {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nome, email, telefone, cpf, senha, tipo) VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nome, $email, $telefone ?: null, $cpf ?: null, $hash, $tipo]);
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT id, nome, email, telefone, cpf, tipo, criado_em FROM usuarios WHERE id = ?"
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
        $cpfLimpo = $this->somenteDigitosCpf($cpf);
        $stmt = $this->db->prepare("
            SELECT *
            FROM usuarios
            WHERE cpf = ?
               OR REPLACE(REPLACE(cpf, '.', ''), '-', '') = ?
            LIMIT 1
        ");
        $stmt->execute([$cpf, $cpfLimpo]);
        return $stmt->fetch() ?: null;
    }

    public function emailOuCpfExiste(string $email, string $cpf, int $excluirId = 0): bool {
        $cpfLimpo = $this->somenteDigitosCpf($cpf);
        $stmt = $this->db->prepare(
            "SELECT id
             FROM usuarios
             WHERE (
                email = ?
                OR cpf = ?
                OR REPLACE(REPLACE(cpf, '.', ''), '-', '') = ?
             )
             AND id != ?
             LIMIT 1"
        );
        $stmt->execute([$email, $cpf, $cpfLimpo, $excluirId]);
        return (bool) $stmt->fetch();
    }

    public function listarTodos(?int $limit = null, int $offset = 0): array {
        $sql = "SELECT id, nome, email, telefone, cpf, tipo, criado_em FROM usuarios ORDER BY criado_em DESC";
        if ($limit !== null) {
            $limit = max(1, $limit);
            $offset = max(0, $offset);
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function contarTodos(): int {
        return (int) $this->db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    }

    public function atualizar(int $id, string $nome, string $email, string $telefone, string $cpf, string $tipo): bool {
        $stmt = $this->db->prepare(
            "UPDATE usuarios SET nome = ?, email = ?, telefone = ?, cpf = ?, tipo = ? WHERE id = ?"
        );
        return $stmt->execute([$nome, $email ?: null, $telefone ?: null, $cpf ?: null, $tipo, $id]);
    }

    public function atualizarSenha(int $id, string $senha): bool {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
