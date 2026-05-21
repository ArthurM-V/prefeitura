<?php
$pageTitle = 'Empresas';
require __DIR__ . '/../shared/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="admin-main">
    <div class="container">
        <div class="page-header">
            <h2>Empresas parceiras</h2>
            <p>Gerencie terceirizadas que auxiliam na execucao dos chamados.</p>
        </div>

        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin"><h3 id="form-title">Nova empresa</h3></div>
            <div class="card-body">
                <form id="form-empresa" class="admin-form-grid">
                    <input type="hidden" name="id" id="empresa-id">
                    <div class="form-group">
                        <label for="empresa-nome">Nome</label>
                        <input type="text" name="nome" id="empresa-nome" required>
                    </div>
                    <div class="form-group">
                        <label for="empresa-cnpj">CNPJ</label>
                        <input type="text" name="cnpj" id="empresa-cnpj">
                    </div>
                    <div class="form-group">
                        <label for="empresa-email">E-mail</label>
                        <input type="email" name="email" id="empresa-email">
                    </div>
                    <div class="form-group">
                        <label for="empresa-telefone">Telefone</label>
                        <input type="text" name="telefone" id="empresa-telefone">
                    </div>
                    <div class="form-group">
                        <label for="empresa-responsavel">Responsavel</label>
                        <input type="text" name="responsavel" id="empresa-responsavel">
                    </div>
                    <div class="form-group">
                        <label for="empresa-area">Area de atuacao</label>
                        <input type="text" name="area_atuacao" id="empresa-area">
                    </div>
                    <div class="form-group form-check-line">
                        <label>
                            <input type="checkbox" name="ativo" id="empresa-ativo" checked>
                            Ativa
                        </label>
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
                        <th>CNPJ</th>
                        <th>Contato</th>
                        <th>Area</th>
                        <th>Status</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresas as $empresa): ?>
                        <tr>
                            <td class="text-muted"><?= $empresa['id'] ?></td>
                            <td>
                                <?= htmlspecialchars($empresa['nome']) ?>
                                <?php if (!empty($empresa['responsavel'])): ?>
                                    <div class="text-muted text-sm"><?= htmlspecialchars($empresa['responsavel']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($empresa['cnpj'] ?? '') ?></td>
                            <td>
                                <?= htmlspecialchars($empresa['email'] ?? '') ?>
                                <?php if (!empty($empresa['telefone'])): ?>
                                    <div class="text-muted text-sm"><?= htmlspecialchars($empresa['telefone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($empresa['area_atuacao'] ?? '') ?></td>
                            <td>
                                <span class="badge <?= $empresa['ativo'] ? 'badge-success' : 'badge-neutral' ?>">
                                    <?= $empresa['ativo'] ? 'Ativa' : 'Inativa' ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-outline btn-xs" onclick='editar(<?= json_encode($empresa, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Editar</button>
                                    <button class="btn btn-danger btn-xs" onclick="excluir(<?= $empresa['id'] ?>)">Excluir</button>
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
const form = document.getElementById('form-empresa');

function editar(empresa) {
    document.getElementById('form-title').textContent = 'Editar empresa';
    document.getElementById('empresa-id').value = empresa.id;
    document.getElementById('empresa-nome').value = empresa.nome || '';
    document.getElementById('empresa-cnpj').value = empresa.cnpj || '';
    document.getElementById('empresa-email').value = empresa.email || '';
    document.getElementById('empresa-telefone').value = empresa.telefone || '';
    document.getElementById('empresa-responsavel').value = empresa.responsavel || '';
    document.getElementById('empresa-area').value = empresa.area_atuacao || '';
    document.getElementById('empresa-ativo').checked = Number(empresa.ativo) === 1;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function limparFormulario() {
    form.reset();
    document.getElementById('empresa-id').value = '';
    document.getElementById('empresa-ativo').checked = true;
    document.getElementById('form-title').textContent = 'Nova empresa';
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const resp = await fetch('<?= BASE_URL ?>/admin/empresas/salvar', {
        method: 'POST',
        body: new FormData(form),
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Erro ao salvar.');
});

async function excluir(id) {
    if (!confirm('Deseja excluir esta empresa?')) return;
    const body = new URLSearchParams({ id });
    const resp = await fetch('<?= BASE_URL ?>/admin/empresas/excluir', {
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
