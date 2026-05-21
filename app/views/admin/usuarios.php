<?php
$pageTitle = 'Usuarios';
require __DIR__ . '/../shared/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="admin-main">
    <div class="container">
        <div class="page-header">
            <h2>Usuarios</h2>
            <p>Gerencie cidadaos e administradores do sistema.</p>
        </div>

        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin"><h3 id="form-title">Novo usuario</h3></div>
            <div class="card-body">
                <form id="form-usuario" class="admin-form-grid">
                    <input type="hidden" name="id" id="usuario-id">
                    <div class="form-group">
                        <label for="usuario-nome">Nome</label>
                        <input type="text" name="nome" id="usuario-nome" required>
                    </div>
                    <div class="form-group">
                        <label for="usuario-email">E-mail</label>
                        <input type="email" name="email" id="usuario-email" required>
                    </div>
                    <div class="form-group">
                        <label for="usuario-telefone">Telefone</label>
                        <input type="text" name="telefone" id="usuario-telefone">
                    </div>
                    <div class="form-group">
                        <label for="usuario-cpf">CPF</label>
                        <input type="text" name="cpf" id="usuario-cpf" maxlength="14">
                    </div>
                    <div class="form-group">
                        <label for="usuario-tipo">Tipo</label>
                        <select name="tipo" id="usuario-tipo" required>
                            <option value="cidadao">Cidadao</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usuario-senha">Senha</label>
                        <input type="password" name="senha" id="usuario-senha" placeholder="Obrigatoria para novo usuario">
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
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                        <th>Tipo</th>
                        <th>Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td class="text-muted"><?= $usuario['id'] ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['telefone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['cpf'] ?? '') ?></td>
                            <td><span class="badge badge-neutral"><?= htmlspecialchars($usuario['tipo']) ?></span></td>
                            <td>
                                <div class="table-actions">
                                    <button class="btn btn-outline btn-xs" onclick='editar(<?= json_encode($usuario, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>Editar</button>
                                    <?php if ((int)$usuario['id'] !== (int)$_SESSION['usuario_id']): ?>
                                        <button class="btn btn-danger btn-xs" onclick="excluir(<?= $usuario['id'] ?>)">Excluir</button>
                                    <?php endif; ?>
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
const form = document.getElementById('form-usuario');

function editar(usuario) {
    document.getElementById('form-title').textContent = 'Editar usuario';
    document.getElementById('usuario-id').value = usuario.id;
    document.getElementById('usuario-nome').value = usuario.nome || '';
    document.getElementById('usuario-email').value = usuario.email || '';
    document.getElementById('usuario-telefone').value = usuario.telefone || '';
    document.getElementById('usuario-cpf').value = usuario.cpf || '';
    document.getElementById('usuario-tipo').value = usuario.tipo || 'cidadao';
    document.getElementById('usuario-senha').value = '';
    document.getElementById('usuario-senha').placeholder = 'Preencha apenas para trocar';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function limparFormulario() {
    form.reset();
    document.getElementById('usuario-id').value = '';
    document.getElementById('usuario-senha').placeholder = 'Obrigatoria para novo usuario';
    document.getElementById('form-title').textContent = 'Novo usuario';
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const resp = await fetch('<?= BASE_URL ?>/admin/usuarios/salvar', {
        method: 'POST',
        body: new FormData(form),
    });
    const data = await resp.json();
    if (data.success) location.reload();
    else alert(data.message || 'Erro ao salvar.');
});

async function excluir(id) {
    if (!confirm('Deseja excluir este usuario?')) return;
    const body = new URLSearchParams({ id });
    const resp = await fetch('<?= BASE_URL ?>/admin/usuarios/excluir', {
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
