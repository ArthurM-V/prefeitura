<?php
$pageTitle = 'Categorias';
require __DIR__ . '/../shared/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="admin-main">
    <div class="container">
        <div class="page-header">
            <h2>Categorias</h2>
            <p>Gerencie os tipos de chamados disponiveis no portal.</p>
        </div>

        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin"><h3 id="form-title">Nova categoria</h3></div>
            <div class="card-body">
                <form id="form-categoria" class="admin-form-grid">
                    <input type="hidden" name="id" id="categoria-id">
                    <div class="form-group">
                        <label for="categoria-nome">Nome</label>
                        <input type="text" name="nome" id="categoria-nome" required>
                    </div>
                    <div class="form-group form-group-wide">
                        <label for="categoria-descricao">Descricao</label>
                        <textarea name="descricao" id="categoria-descricao" rows="3"></textarea>
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
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td class="text-muted"><?= $categoria['id'] ?></td>
                            <td><?= htmlspecialchars($categoria['nome']) ?></td>
                            <td><?= htmlspecialchars($categoria['descricao'] ?? '') ?></td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-outline btn-xs" onclick='editar(<?= json_encode($categoria, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Editar</button>
                                    <button class="btn btn-danger btn-xs" onclick="excluir(<?= $categoria['id'] ?>)">Excluir</button>
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
const form = document.getElementById('form-categoria');

function editar(categoria) {
    document.getElementById('form-title').textContent = 'Editar categoria';
    document.getElementById('categoria-id').value = categoria.id;
    document.getElementById('categoria-nome').value = categoria.nome || '';
    document.getElementById('categoria-descricao').value = categoria.descricao || '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function limparFormulario() {
    form.reset();
    document.getElementById('categoria-id').value = '';
    document.getElementById('form-title').textContent = 'Nova categoria';
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const resp = await fetch('<?= BASE_URL ?>/admin/categorias/salvar', {
        method: 'POST',
        body: new FormData(form),
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Erro ao salvar.');
});

async function excluir(id) {
    if (!confirm('Deseja excluir esta categoria?')) return;
    const body = new URLSearchParams({ id });
    const resp = await fetch('<?= BASE_URL ?>/admin/categorias/excluir', {
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
