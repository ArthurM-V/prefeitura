<?php

define('BASE_URL', '/prefeitura/public');
define('APP_NAME', 'Portal do Cidadão');
define('SESSION_TIMEOUT', 3600); // 1 hora

function redirect(string $url): void {
    header("Location: " . BASE_URL . $url);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['usuario_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
}

function requireAdmin(): void {
    if (!isLoggedIn() || !isAdmin()) {
        redirect('/login');
    }
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

function sanitize(string $str): string {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
