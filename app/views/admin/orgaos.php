<?php
$pageTitle = 'Orgaos';
require __DIR__ . '/../shared/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="admin-main">
    <div class="container">
        <div class="page-header">
            <h2>Orgaos</h2>
            <p>Gerencie secretarias e setores responsaveis pelos chamados.</p>
        </div>

        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin"><h3 id="form-title">Novo orgao</h3></div>
            <div class="card-body">
                <form id="form-orgao" class="admin-form-grid">
                    <input type="hidden" name="id" id="orgao-id">
                    <div class="form-group">
                        <label for="orgao-nome">Nome</label>
                        <input type="text" name="nome" id="orgao-nome" required>
                    </div>
                    <div class="form-group form-group-wide">
                        <label for="orgao-descricao">Descricao</label>
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
                        <th>Descricao</th>
                        <th>Acoes</th>
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
    </div>
</main>

<script>
const form = document.getElementById('form-orgao');

function editar(orgao) {
    document.getElementById('form-title').textContent = 'Editar orgao';
    document.getElementById('orgao-id').value = orgao.id;
    document.getElementById('orgao-nome').value = orgao.nome || '';
    document.getElementById('orgao-descricao').value = orgao.descricao || '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function limparFormulario() {
    form.reset();
    document.getElementById('orgao-id').value = '';
    document.getElementById('form-title').textContent = 'Novo orgao';
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
    if (!confirm('Deseja excluir este orgao?')) return;
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
