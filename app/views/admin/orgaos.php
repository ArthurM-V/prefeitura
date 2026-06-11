<?php
$pageTitle = 'Órgãos';
require __DIR__ . '/../shared/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="admin-main">
    <div class="container">
        <div class="page-header">
            <h2>Órgãos</h2>
            <p>Gerencie secretarias e setores responsáveis pelos chamados.</p>
        </div>

        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin"><h3 id="form-title">Novo órgão</h3></div>
            <div class="card-body">
                <form id="form-orgao" class="admin-form-grid">
                    <input type="hidden" name="id" id="orgao-id">
                    <div class="form-group">
                        <label for="orgao-nome">Nome</label>
                        <input type="text" name="nome" id="orgao-nome" required>
                    </div>
                    <div class="form-group form-group-wide">
                        <label for="orgao-descricao">Descrição</label>
                        <textarea name="descricao" id="orgao-descricao" rows="3"></textarea>
                    </div>
                    <div class="form-actions form-group-wide">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-outline" onclick="limparFormulario()">Cancelar</button>
                    </div>
                </form>
            </div>
        </section>

        <div class="table-wrapper">
            <table class="table table-hoverable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orgaos as $orgao): ?>
                        <tr>
                            <td class="text-muted"><?= $orgao['id'] ?></td>
                            <td><?= htmlspecialchars($orgao['nome']) ?></td>
                            <td><?= htmlspecialchars($orgao['descricao'] ?? '') ?></td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-outline btn-xs" onclick='editar(<?= json_encode($orgao, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Editar</button>
                                    <button class="btn btn-danger btn-xs" onclick="excluir(<?= $orgao['id'] ?>)">Excluir</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
            $primeiroItem = (($paginaAtual - 1) * $porPagina) + 1;
            $ultimoItem = min($paginaAtual * $porPagina, $totalItens);
        ?>
        <p class="table-count">
            Exibindo <?= $primeiroItem ?>-<?= $ultimoItem ?> de <?= $totalItens ?> órgão(s).
        </p>

        <?php if ($totalPaginas > 1): ?>
            <nav class="pagination" aria-label="Paginação de órgãos">
                <?php if ($paginaAtual > 1): ?>
                    <a href="<?= BASE_URL ?>/admin/orgaos?page=<?= $paginaAtual - 1 ?>" class="pagination-link">Anterior</a>
                <?php else: ?>
                    <span class="pagination-link disabled">Anterior</span>
                <?php endif; ?>

                <?php for ($page = 1; $page <= $totalPaginas; $page++): ?>
                    <a
                        href="<?= BASE_URL ?>/admin/orgaos?page=<?= $page ?>"
                        class="pagination-link <?= $page === $paginaAtual ? 'active' : '' ?>"
                        <?= $page === $paginaAtual ? 'aria-current="page"' : '' ?>
                    >
                        <?= $page ?>
                    </a>
                <?php endfor; ?>

                <?php if ($paginaAtual < $totalPaginas): ?>
                    <a href="<?= BASE_URL ?>/admin/orgaos?page=<?= $paginaAtual + 1 ?>" class="pagination-link">Próxima</a>
                <?php else: ?>
                    <span class="pagination-link disabled">Próxima</span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</main>

<script>
const form = document.getElementById('form-orgao');

function editar(orgao) {
    document.getElementById('form-title').textContent = 'Editar órgão';
    document.getElementById('orgao-id').value = orgao.id;
    document.getElementById('orgao-nome').value = orgao.nome || '';
    document.getElementById('orgao-descricao').value = orgao.descricao || '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function limparFormulario() {
    form.reset();
    document.getElementById('orgao-id').value = '';
    document.getElementById('form-title').textContent = 'Novo órgão';
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const resp = await fetch('<?= BASE_URL ?>/admin/orgaos/salvar', {
        method: 'POST',
        body: new FormData(form),
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Erro ao salvar.');
});

async function excluir(id) {
    if (!confirm('Deseja excluir este órgão?')) return;
    const body = new URLSearchParams({ id });
    const resp = await fetch('<?= BASE_URL ?>/admin/orgaos/excluir', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body,
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Erro ao excluir.');
}
</script>

<?php require __DIR__ . '/../shared/footer.php'; ?>
