<?php

define('BASE_URL', '/prefeitura');
define('APP_NAME', 'Portal do Cidadão');
define('SESSION_TIMEOUT', 3600);

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

function isCidadao(): bool {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'cidadao';
}

function requireAdmin(): void {
    if (!isLoggedIn() || !isAdmin()) {
        redirect('/login');
    }
}

function requireCidadao(): void {
    if (!isLoggedIn() || !isCidadao()) {
        redirect('/entrar');
    }
}

function sanitize(string $str): string {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function formatarTelefone(?string $telefone): string {
    $telefone = trim((string)$telefone);
    if ($telefone === '') {
        return '';
    }

    $digitos = preg_replace('/\D/', '', $telefone);
    if (strlen($digitos) === 11) {
        return sprintf(
            '(%s) %s-%s',
            substr($digitos, 0, 2),
            substr($digitos, 2, 5),
            substr($digitos, 7, 4)
        );
    }

    if (strlen($digitos) === 10) {
        return sprintf(
            '(%s) %s-%s',
            substr($digitos, 0, 2),
            substr($digitos, 2, 4),
            substr($digitos, 6, 4)
        );
    }

    return $telefone;
}

function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
