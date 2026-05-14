<?php
?>
<header class="admin-navbar">
    <div class="container admin-navbar-inner">
        <a href="<?= BASE_URL ?>/admin/dashboard" class="navbar-brand">
            <span class="navbar-brand-icon">🏛️</span>
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
        </nav>

        <div class="admin-navbar-user">
            <span class="admin-user-name">👤 <?= htmlspecialchars($_SESSION['nome'] ?? 'Admin') ?></span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm on-dark">Sair</a>
        </div>
    </div>
</header>