<?php

require_once __DIR__ . '/../../config/database.php';

class Empresa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarTodas(bool $somenteAtivas = false, ?int $limit = null, int $offset = 0): array {
        $sql = "SELECT * FROM empresas";
        if ($somenteAtivas) {
            $sql .= " WHERE ativo = 1";
        }
        $sql .= " ORDER BY nome";
        if ($limit !== null) {
            $limit = max(1, $limit);
            $offset = max(0, $offset);
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }

        return $this->db->query($sql)->fetchAll();
    }

    public function contarTodas(bool $somenteAtivas = false): int {
        $sql = "SELECT COUNT(*) FROM empresas";
        if ($somenteAtivas) {
            $sql .= " WHERE ativo = 1";
        }

        return (int) $this->db->query($sql)->fetchColumn();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function criar(
        string $nome,
        string $cnpj = '',
        string $email = '',
        string $telefone = '',
        string $responsavel = '',
        string $areaAtuacao = '',
        int $ativo = 1
    ): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO empresas (nome, cnpj, email, telefone, responsavel, area_atuacao, ativo)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $nome,
            $cnpj ?: null,
            $email ?: null,
            $telefone ?: null,
            $responsavel ?: null,
            $areaAtuacao ?: null,
            $ativo,
        ]);
    }

    public function atualizar(
        int $id,
        string $nome,
        string $cnpj = '',
        string $email = '',
        string $telefone = '',
        string $responsavel = '',
        string $areaAtuacao = '',
        int $ativo = 1
    ): bool {
        $stmt = $this->db->prepare(
            "UPDATE empresas
             SET nome = ?, cnpj = ?, email = ?, telefone = ?, responsavel = ?, area_atuacao = ?, ativo = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $nome,
            $cnpj ?: null,
            $email ?: null,
            $telefone ?: null,
            $responsavel ?: null,
            $areaAtuacao ?: null,
            $ativo,
            $id,
        ]);
    }

    public function excluir(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM empresas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function alterarAtivo(int $id, int $ativo): bool {
        $stmt = $this->db->prepare("UPDATE empresas SET ativo = ? WHERE id = ?");
        return $stmt->execute([$ativo, $id]);
    }
}
