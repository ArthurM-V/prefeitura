<?php

ob_start();
session_start();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
foreach (['/prefeitura/public', '/prefeitura'] as $prefix) {
    if (str_starts_with($uri, $prefix)) { $uri = substr($uri, strlen($prefix)); break; }
}
$uri = rtrim($uri, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/' && $method === 'GET') {
    require_once __DIR__ . '/../app/controllers/PortalController.php';
    (new PortalController())->index(); exit;
}

if ($uri === '/login') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    (new AuthController())->login(); exit;
}

if ($uri === '/entrar') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    (new AuthController())->loginCidadao(); exit;
}

if ($uri === '/cadastro') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    (new AuthController())->cadastro(); exit;
}

if ($uri === '/logout') {
    require_once __DIR__ . '/../app/controllers/AuthController.php';
    (new AuthController())->logout(); exit;
}

if (str_starts_with($uri, '/cidadao')) {
    require_once __DIR__ . '/../app/controllers/CidadaoController.php';
    $ctrl = new CidadaoController();

    if ($uri === '/cidadao/dashboard')       { $ctrl->dashboard(); exit; }
    if ($uri === '/cidadao/abrir-chamado')   { $ctrl->abrirChamado(); exit; }

    if (preg_match('/^\/cidadao\/chamado\/(\d+)$/', $uri, $m)) {
        $ctrl->verChamado((int)$m[1]); exit;
    }
}

if (str_starts_with($uri, '/admin')) {
    require_once __DIR__ . '/../app/controllers/AdminController.php';
    $ctrl = new AdminController();

    if ($uri === '/admin/dashboard')        { $ctrl->dashboard(); exit; }
    if ($uri === '/admin/chamados')         { $ctrl->chamados(); exit; }
    if ($uri === '/admin/atribuir-orgao')   { $ctrl->atribuirOrgao(); exit; }
    if ($uri === '/admin/atribuir-empresa') { $ctrl->atribuirEmpresa(); exit; }
    if ($uri === '/admin/atualizar-chamado'){ $ctrl->atualizarChamado(); exit; }
    if ($uri === '/admin/alterar-status')   { $ctrl->alterarStatus(); exit; }
    if ($uri === '/admin/enviar-feedback')  { $ctrl->enviarFeedback(); exit; }
    if ($uri === '/admin/excluir-chamado')  { $ctrl->excluirChamado(); exit; }

    if ($uri === '/admin/usuarios')          { $ctrl->usuarios(); exit; }
    if ($uri === '/admin/usuarios/salvar')   { $ctrl->salvarUsuario(); exit; }
    if ($uri === '/admin/usuarios/excluir')  { $ctrl->excluirUsuario(); exit; }

    if ($uri === '/admin/categorias')         { $ctrl->categorias(); exit; }
    if ($uri === '/admin/categorias/salvar')  { $ctrl->salvarCategoria(); exit; }
    if ($uri === '/admin/categorias/excluir') { $ctrl->excluirCategoria(); exit; }

    if ($uri === '/admin/orgaos')             { $ctrl->orgaos(); exit; }
    if ($uri === '/admin/orgaos/salvar')      { $ctrl->salvarOrgao(); exit; }
    if ($uri === '/admin/orgaos/excluir')     { $ctrl->excluirOrgao(); exit; }

    if ($uri === '/admin/empresas')           { $ctrl->empresas(); exit; }
    if ($uri === '/admin/empresas/salvar')    { $ctrl->salvarEmpresa(); exit; }
    if ($uri === '/admin/empresas/excluir')   { $ctrl->excluirEmpresa(); exit; }

    if (preg_match('/^\/admin\/chamado\/(\d+)$/', $uri, $m)) {
        $ctrl->verChamado((int)$m[1]); exit;
    }
}

http_response_code(404);
echo "<h1>Página não encontrada</h1><a href='" . BASE_URL . "/'>Voltar ao portal</a>";
