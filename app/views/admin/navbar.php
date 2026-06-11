<?php
?>
<header class="admin-navbar">
    <div class="container admin-navbar-inner">
        <a href="<?= BASE_URL ?>/admin/dashboard" class="navbar-brand">
            <span>Painel Admin</span>
        </a>

        <nav class="admin-nav">
            <a href="<?= BASE_URL ?>/admin/dashboard"
               class="admin-nav-link <?= str_ends_with($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>">
                Dashboard
            </a>
            <a href="<?= BASE_URL ?>/admin/chamados"
               class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'chamado') ? 'active' : '' ?>">
                Chamados
            </a>
            <a href="<?= BASE_URL ?>/admin/usuarios"
               class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'usuarios') ? 'active' : '' ?>">
                Usuários
            </a>
            <a href="<?= BASE_URL ?>/admin/categorias"
               class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'categorias') ? 'active' : '' ?>">
                Categorias
            </a>
            <a href="<?= BASE_URL ?>/admin/orgaos"
               class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'orgaos') ? 'active' : '' ?>">
                Órgãos
            </a>
            <a href="<?= BASE_URL ?>/admin/empresas"
               class="admin-nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'empresas') ? 'active' : '' ?>">
                Empresas
            </a>
        </nav>

        <div class="admin-navbar-user">
            <a href="<?= BASE_URL ?>/" class="btn btn-outline btn-sm on-dark">Portal</a>
            <span class="admin-user-name"><?= htmlspecialchars($_SESSION['nome'] ?? 'Admin') ?></span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm on-dark">Sair</a>
        </div>
    </div>
</header>
