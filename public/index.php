<?php

ob_start();
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

foreach (['/prefeitura/public', '/prefeitura'] as $prefix) {
    if (str_starts_with($uri, $prefix)) {
        $uri = substr($uri, strlen($prefix));
        break;
    }
}
$uri = rtrim($uri, '/') ?: '/';

$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/' && $method === 'GET') {
    require_once __DIR__ . '/../app/controllers/PortalController.php';
    (new PortalController())->index();
    exit;
}

if ($uri === '/novo-chamado' && $method === 'POST') {
    require_once __DIR__ . '/../app/controllers/PortalController.php';
    (new PortalController())->novoChamado();
    exit;
}

if ($uri === '/login') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    (new AuthController())->login();
    exit;
}

if ($uri === '/logout') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    (new AuthController())->logout();
    exit;
}

if (str_starts_with($uri, '/admin')) {
    require_once __DIR__ . '/../app/controllers/AdminController.php';
    $ctrl = new AdminController();

    if ($uri === '/admin/dashboard') { $ctrl->dashboard(); exit; }
    if ($uri === '/admin/chamados')  { $ctrl->chamados();  exit; }

    if (preg_match('/^\/admin\/chamado\/(\d+)$/', $uri, $m)) {
        $ctrl->verChamado((int)$m[1]); exit;
    }

    if ($uri === '/admin/atribuir-orgao')  { $ctrl->atribuirOrgao();   exit; }
    if ($uri === '/admin/alterar-status')  { $ctrl->alterarStatus();   exit; }
    if ($uri === '/admin/enviar-feedback') { $ctrl->enviarFeedback();  exit; }
    if ($uri === '/admin/excluir-chamado') { $ctrl->excluirChamado();  exit; }
}

http_response_code(404);
echo "<h1>Página não encontrada</h1><a href='" . BASE_URL . "/'>Voltar ao portal</a>";