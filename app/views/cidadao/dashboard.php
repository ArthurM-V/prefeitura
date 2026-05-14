<?php
$pageTitle = 'Minha Área';
require __DIR__ . '/../shared/header.php';

$statusColors = [
    'Aberto'       => 'badge-warning',
    'Em Análise'   => 'badge-info',
    'Em Andamento' => 'badge-primary',
    'Resolvido'    => 'badge-success',
    'Encerrado'    => 'badge-neutral',
];
?>

<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <span class="navbar-brand-icon">🏛️</span>
            <span>Prefeitura Municipal</span>
        </a>
        <div style="display:flex;gap:.5rem;align-items:center">
            <span style="font-size:.85rem;color:rgba(255,255,255,.8)">
                Olá, <?= htmlspecialchars($_SESSION['nome']) ?>
            </span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm on-dark">Sair</a>
        </div>
    </div>
</header>

<main class="admin-main">
    <div class="container">

        <div class="page-header">
            <h2>Meus chamados</h2>
            <p>Acompanhe suas solicitações e as respostas da prefeitura.</p>
        </div>

        <div style="margin-bottom:1.5rem">
            <a href="<?= BASE_URL ?>/#novo-chamado" class="btn btn-primary">
                + Nova solicitação
            </a>
        </div>

        <?php if (empty($chamados)): ?>
            <div class="empty-state">
                <p>Você ainda não abriu nenhum chamado.</p>
                <a href="<?= BASE_URL ?>/#novo-chamado" class="btn btn-primary" style="margin-top:1rem">
                    Abrir primeiro chamado
                </a>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table class="table table-hoverable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>Categoria</th>
                            <th>Órgão</th>
                            <th>Status</th>
                            <th>Aberto em</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamados as $c): ?>
                            <tr>
                                <td class="text-muted"><?= $c['id'] ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/cidadao/chamado/<?= $c['id'] ?>" class="link-primary">
                                        <?= htmlspecialchars($c['titulo']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($c['categoria_nome']) ?></td>
                                <td><?= $c['orgao_nome'] ? htmlspecialchars($c['orgao_nome']) : '<span class="text-muted">—</span>' ?></td>
                                <td>
                                    <span class="badge <?= $statusColors[$c['status_nome']] ?? 'badge-neutral' ?>">
                                        <?= htmlspecialchars($c['status_nome']) ?>
                                    </span>
                                </td>
                                <td class="text-sm text-muted">
                                    <?= date('d/m/Y', strtotime($c['data_abertura'])) ?>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/cidadao/chamado/<?= $c['id'] ?>"
                                       class="btn btn-outline btn-xs">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="table-count"><?= count($chamados) ?> chamado(s) no total.</p>
        <?php endif; ?>

    </div>
</main>

<?php require __DIR__ . '/../shared/footer.php'; ?>