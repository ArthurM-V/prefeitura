<?php

$pageTitle = 'Gerenciar Chamados';
require __DIR__ . '/../shared/header.php';

$statusColors = [
    'Aberto'       => 'badge-warning',
    'Em Análise'   => 'badge-info',
    'Em Andamento' => 'badge-primary',
    'Resolvido'    => 'badge-success',
    'Encerrado'    => 'badge-neutral',
];
?>

<?php require __DIR__ . '/navbar.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="page-header">
            <h2>Chamados</h2>
            <p>Gerencie, filtre e acompanhe todos os chamados dos cidadãos.</p>
        </div>

        <!-- ===== FILTROS ===== -->
        <form method="GET" action="<?= BASE_URL ?>/admin/chamados" class="filter-bar">
            <select name="status_id" onchange="this.form.submit()">
                <option value="">Todos os status</option>
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s['id'] ?>"
                        <?= ($filtros['status_id'] ?? 0) == $s['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="categoria_id" onchange="this.form.submit()">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= ($filtros['categoria_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="orgao_id" onchange="this.form.submit()">
                <option value="">Todos os órgãos</option>
                <?php foreach ($orgaos as $o): ?>
                    <option value="<?= $o['id'] ?>"
                        <?= ($filtros['orgao_id'] ?? 0) == $o['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($o['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (array_filter($filtros)): ?>
                <a href="<?= BASE_URL ?>/admin/chamados" class="btn btn-outline btn-sm">
                    Limpar filtros
                </a>
            <?php endif; ?>
        </form>

        <!-- ===== TABELA ===== -->
        <?php if (empty($chamados)): ?>
            <div class="empty-state">
                <p>Nenhum chamado encontrado com os filtros selecionados.</p>
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
                            <th>Abertura</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamados as $c): ?>
                            <tr>
                                <td class="text-muted"><?= $c['id'] ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/chamado/<?= $c['id'] ?>" class="link-primary">
                                        <?= htmlspecialchars($c['titulo']) ?>
                                    </a>
                                    <div class="text-muted text-sm"><?= htmlspecialchars($c['usuario_nome']) ?></div>
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
                                    <div class="table-actions">
                                        <a href="<?= BASE_URL ?>/admin/chamado/<?= $c['id'] ?>"
                                           class="btn btn-outline btn-xs">
                                            Ver
                                        </a>
                                        <button
                                            class="btn btn-danger btn-xs"
                                            onclick="confirmarExclusao(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['titulo'])) ?>')"
                                        >
                                            Excluir
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p class="table-count"><?= count($chamados) ?> chamado(s) encontrado(s).</p>
        <?php endif; ?>

    </div>
</main>

<!-- Modal de confirmação de exclusão -->
<div id="modal-exclusao" class="modal-overlay" style="display:none;"
     x-data x-show="$store.modal.aberto" x-cloak>
    <div class="modal-box">
        <h3>Confirmar exclusão</h3>
        <p id="modal-texto">Tem certeza que deseja excluir este chamado? Esta ação não pode ser desfeita.</p>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="fecharModal()">Cancelar</button>
            <button class="btn btn-danger" id="btn-confirmar-exclusao">Excluir</button>
        </div>
    </div>
</div>

<script>
let chamadoParaExcluir = null;

function confirmarExclusao(id, titulo) {
    chamadoParaExcluir = id;
    document.getElementById('modal-texto').textContent =
        `Tem certeza que deseja excluir o chamado "${titulo}"? Esta ação não pode ser desfeita.`;
    document.getElementById('modal-exclusao').style.display = 'flex';
}

function fecharModal() {
    document.getElementById('modal-exclusao').style.display = 'none';
    chamadoParaExcluir = null;
}

document.getElementById('btn-confirmar-exclusao').addEventListener('click', async () => {
    if (!chamadoParaExcluir) return;
    const resp = await fetch('<?= BASE_URL ?>/admin/excluir-chamado', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `chamado_id=${chamadoParaExcluir}`,
    });
    const data = await resp.json();
    if (data.success) {
        location.reload();
    } else {
        alert(data.message || 'Erro ao excluir.');
        fecharModal();
    }
});

document.getElementById('modal-exclusao').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) fecharModal();
});
</script>

<?php require __DIR__ . '/../shared/footer.php'; ?>
