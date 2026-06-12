<?php

$pageTitle = 'Dashboard';
require __DIR__ . '/../shared/header.php';
?>

<?php require __DIR__ . '/navbar.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="page-header">
            <h2>Dashboard</h2>
            <p>Visão geral dos chamados e atividades recentes.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card stat-card-primary">
                <span class="stat-label">Total de chamados</span>
                <span class="stat-value"><?= $totalGeral ?></span>
            </div>

            <?php foreach ($totalStatus as $s): ?>
                <div class="stat-card">
                    <span class="stat-label"><?= htmlspecialchars($s['nome']) ?></span>
                    <span class="stat-value"><?= $s['total'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="dashboard-grid">

            <section class="card">
                <div class="card-header-admin">
                    <h3>Chamados por órgão</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($totalOrgao)): ?>
                        <p class="text-muted">Nenhum dado disponível.</p>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Órgão</th>
                                    <th class="text-right">Chamados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($totalOrgao as $o): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($o['nome']) ?></td>
                                        <td class="text-right">
                                            <span class="badge badge-neutral"><?= $o['total'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </section>

            <section class="card">
                <div class="card-header-admin">
                    <h3>Atividades recentes</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($atividadesRecentes)): ?>
                        <p class="text-muted">Nenhuma atividade registrada.</p>
                    <?php else: ?>
                        <ul class="activity-list">
                            <?php foreach ($atividadesRecentes as $h): ?>
                                <li class="activity-item">
                                    <div class="activity-dot"></div>
                                    <div class="activity-body">
                                        <p class="activity-desc">
                                            <strong><?= htmlspecialchars($h['tipo_acao']) ?></strong>
                                            - <?= htmlspecialchars($h['chamado_titulo']) ?>
                                        </p>
                                        <p class="activity-meta">
                                            <?= htmlspecialchars($h['usuario_nome']) ?>
                                            · <?= date('d/m/Y H:i', strtotime($h['data'])) ?>
                                        </p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>

        </div>

        <div class="dashboard-actions">
            <a href="<?= BASE_URL ?>/admin/chamados" class="btn btn-primary">
                Ver todos os chamados →
            </a>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../shared/footer.php'; ?>
