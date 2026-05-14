<?php

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Categoria.php';

class PortalController {

    public function index(): void {
        $chamado    = new Chamado();
        $categoria  = new Categoria();
        $resolvidos = $chamado->listarResolvidos();
        $categorias = $categoria->listarTodas();
        require __DIR__ . '/../views/portal/index.php';
    }
}