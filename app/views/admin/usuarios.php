<?php
$pageTitle = 'Usuários';
require __DIR__ . '/../shared/header.php';
require __DIR__ . '/navbar.php';
?>

<main class="admin-main">
    <div class="container">
        <div class="page-header">
            <h2>Usuários</h2>
            <p>Gerencie cidadãos e administradores do sistema.</p>
        </div>

        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin"><h3 id="form-title">Novo usuário</h3></div>
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
                            <option value="cidadao">Cidadão</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="usuario-senha">Senha</label>
                        <input type="password" name="senha" id="usuario-senha" placeholder="Obrigatória para novo usuário">
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
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td class="text-muted"><?= $usuario['id'] ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars(formatarTelefone($usuario['telefone'] ?? '')) ?></td>
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
        <?php
            $primeiroItem = (($paginaAtual - 1) * $porPagina) + 1;
            $ultimoItem = min($paginaAtual * $porPagina, $totalItens);
        ?>
        <p class="table-count">
            Exibindo <?= $primeiroItem ?>-<?= $ultimoItem ?> de <?= $totalItens ?> usuário(s).
        </p>

        <?php if ($totalPaginas > 1): ?>
            <nav class="pagination" aria-label="Paginação de usuários">
                <?php if ($paginaAtual > 1): ?>
                    <a href="<?= BASE_URL ?>/admin/usuarios?page=<?= $paginaAtual - 1 ?>" class="pagination-link">Anterior</a>
                <?php else: ?>
                    <span class="pagination-link disabled">Anterior</span>
                <?php endif; ?>

                <?php for ($page = 1; $page <= $totalPaginas; $page++): ?>
                    <a
                        href="<?= BASE_URL ?>/admin/usuarios?page=<?= $page ?>"
                        class="pagination-link <?= $page === $paginaAtual ? 'active' : '' ?>"
                        <?= $page === $paginaAtual ? 'aria-current="page"' : '' ?>
                    >
                        <?= $page ?>
                    </a>
                <?php endfor; ?>

                <?php if ($paginaAtual < $totalPaginas): ?>
                    <a href="<?= BASE_URL ?>/admin/usuarios?page=<?= $paginaAtual + 1 ?>" class="pagination-link">Próxima</a>
                <?php else: ?>
                    <span class="pagination-link disabled">Próxima</span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</main>

<script>
const form = document.getElementById('form-usuario');

function editar(usuario) {
    document.getElementById('form-title').textContent = 'Editar usuário';
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
    document.getElementById('usuario-senha').placeholder = 'Obrigatória para novo usuário';
    document.getElementById('form-title').textContent = 'Novo usuário';
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
    if (!confirm('Deseja excluir este usuário?')) return;
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
