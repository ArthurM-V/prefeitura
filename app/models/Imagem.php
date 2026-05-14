<?php

require_once __DIR__ . '/../../config/database.php';

class Imagem {
    private PDO $db;

    const UPLOAD_DIR  = __DIR__ . '/../../public/imgs/uploads/';
    const UPLOAD_URL  = '/imgs/uploads/';
    const MAX_SIZE    = 5 * 1024 * 1024; // 5 MB
    const TIPOS_VALIDOS = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    public function __construct() {
        $this->db = Database::getConnection();
        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }
    }

    public function salvar(array $arquivo, int $chamadoId, string $tipo): array {
        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Erro no upload do arquivo.'];
        }
        if ($arquivo['size'] > self::MAX_SIZE) {
            return ['success' => false, 'message' => 'Arquivo muito grande. Máximo: 5MB.'];
        }
        $mime = mime_content_type($arquivo['tmp_name']);
        if (!in_array($mime, self::TIPOS_VALIDOS)) {
            return ['success' => false, 'message' => 'Tipo de arquivo inválido. Use JPG, PNG, WEBP ou GIF.'];
        }

        $ext      = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeUnico = uniqid("img_{$chamadoId}_{$tipo}_") . '.' . strtolower($ext);
        $destino  = self::UPLOAD_DIR . $nomeUnico;

        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            return ['success' => false, 'message' => 'Falha ao salvar o arquivo no servidor.'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO imagens (chamado_id, tipo, caminho, nome_original) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$chamadoId, $tipo, $nomeUnico, $arquivo['name']]);

        return ['success' => true, 'caminho' => $nomeUnico];
    }

    public function buscarPorChamado(int $chamadoId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM imagens WHERE chamado_id = ? ORDER BY tipo, criado_em ASC"
        );
        $stmt->execute([$chamadoId]);
        return $stmt->fetchAll();
    }

    public function buscarPorChamadoETipo(int $chamadoId, string $tipo): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM imagens WHERE chamado_id = ? AND tipo = ? LIMIT 1"
        );
        $stmt->execute([$chamadoId, $tipo]);
        return $stmt->fetch() ?: null;
    }

    public function excluir(int $id): bool {
        $stmt = $this->db->prepare("SELECT caminho FROM imagens WHERE id = ?");
        $stmt->execute([$id]);
        $img = $stmt->fetch();
        if ($img && file_exists(self::UPLOAD_DIR . $img['caminho'])) {
            unlink(self::UPLOAD_DIR . $img['caminho']);
        }
        $stmt = $this->db->prepare("DELETE FROM imagens WHERE id = ?");
        return $stmt->execute([$id]);
    }
}