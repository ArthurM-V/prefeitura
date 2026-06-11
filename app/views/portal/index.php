<?php

$pageTitle = 'Portal do Cidadão';
require __DIR__ . '/../shared/header.php';
$logado = isLoggedIn() && !isAdmin();
?>

<!-- ========== NAVBAR ========== -->
<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <span>Prefeitura Municipal</span>
        </a>
        <div class="navbar-actions">
            <?php if ($logado): ?>
                <a href="<?= BASE_URL ?>/cidadao/dashboard" class="btn btn-outline btn-sm on-dark">Minha área</a>
                <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm on-dark">Sair</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/entrar" class="btn btn-outline btn-sm on-dark">Entrar</a>
                <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm" style="opacity:.5;font-size:.75rem">Admin</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- ========== HERO ========== -->
<section class="hero">
    <div class="container">
        <p class="hero-eyebrow">Prefeitura Municipal</p>
        <h2 class="hero-title">Portal do Cidadão</h2>
        <p class="hero-subtitle">Registre sua solicitação e acompanhe as melhorias realizadas em nossa cidade.</p>
        <div class="hero-actions">
        <?php if ($logado): ?>
            <a href="#novo-chamado" class="btn btn-primary btn-lg">Fazer uma solicitação</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>/entrar" class="btn btn-primary btn-lg">Entrar para abrir chamado</a>
            <a href="<?= BASE_URL ?>/cadastro" class="btn btn-outline btn-lg on-dark">Criar conta</a>
        <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== SOLUÇÕES RECENTES ========== -->
<section class="section" id="solucoes">
    <div class="container">
        <h2 class="section-title">Últimas soluções realizadas</h2>
        <p class="section-sub">Veja o que a prefeitura resolveu recentemente para os cidadãos.</p>

        <?php if (empty($resolvidos)): ?>
            <div class="empty-state"><p>Nenhuma solução publicada ainda.</p></div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($resolvidos as $chamado): ?>
                    <?php $categoriaClasse = 'card-media-cat-' . (int)($chamado['categoria_id'] ?? 0); ?>
                    <article class="card card-solucao">
                        <div class="card-media <?= $categoriaClasse ?>">
                            <?php if (!empty($chamado['imagem_admin'])): ?>
                                <img
                                    src="<?= BASE_URL ?>/imgs/uploads/<?= htmlspecialchars($chamado['imagem_admin']) ?>"
                                    alt="Foto da solucao"
                                >
                            <?php endif; ?>
                        </div>

                        <div class="card-solucao-body">
                        <div class="card-header">
                            <span class="badge badge-success">Resolvido</span>
                            <span class="card-categoria"><?= htmlspecialchars($chamado['categoria_nome']) ?></span>
                        </div>
                        <h3 class="card-title"><?= htmlspecialchars($chamado['titulo']) ?></h3>
                        <p class="card-desc"><?= htmlspecialchars($chamado['descricao']) ?></p>

                        <?php if (!empty($chamado['localizacao'])): ?>
                            <p class="card-meta">📍 <?= htmlspecialchars($chamado['localizacao']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($chamado['empresa_nome'])): ?>
                            <p class="card-meta">Empresa parceira: <?= htmlspecialchars($chamado['empresa_nome']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($chamado['feedback_mensagem'])): ?>
                            <div class="card-feedback">
                                <strong>Resposta da prefeitura:</strong>
                                <p><?= htmlspecialchars($chamado['feedback_mensagem']) ?></p>
                            </div>
                        <?php endif; ?>

                        <p class="card-date">
                            Resolvido em <?= $chamado['data_fechamento'] ? date('d/m/Y', strtotime($chamado['data_fechamento'])) : '—' ?>
                        </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== FORMULÁRIO (só para logados) ========== -->
<?php if ($logado): ?>
<section class="section section-alt" id="novo-chamado">
    <div class="container container-narrow">
        <h2 class="section-title">Registrar uma solicitação</h2>
        <p class="section-sub">Preencha o formulário abaixo. Nossa equipe analisará seu pedido.</p>

        <div x-data="chamadoForm()" class="form-card">
            <div x-show="sucesso" x-cloak class="alert alert-success" x-transition>
                <strong>Solicitação enviada!</strong> <span x-text="mensagemSucesso"></span>
            </div>
            <div x-show="erro" x-cloak class="alert alert-danger" x-transition>
                <span x-text="mensagemErro"></span>
            </div>

            <form x-show="!sucesso" @submit.prevent="enviar()" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="categoria_id">Categoria <span class="required">*</span></label>
                    <select id="categoria_id" x-model="form.categoria_id" :class="{'input-error':errors.categoria_id}" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span x-show="errors.categoria_id" class="field-error" x-text="errors.categoria_id"></span>
                </div>

                <div class="form-group">
                    <label for="titulo">Título <span class="required">*</span></label>
                    <input type="text" id="titulo" x-model="form.titulo"
                        placeholder="Descreva brevemente o problema"
                        :class="{'input-error':errors.titulo}" required>
                    <span x-show="errors.titulo" class="field-error" x-text="errors.titulo"></span>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição <span class="required">*</span></label>
                    <textarea id="descricao" x-model="form.descricao" rows="4"
                        placeholder="Descreva o problema com detalhes..."
                        :class="{'input-error':errors.descricao}" required></textarea>
                    <span x-show="errors.descricao" class="field-error" x-text="errors.descricao"></span>
                </div>

                <div class="form-group">
                    <label for="localizacao">Localização</label>
                    <input type="text" id="localizacao" x-model="form.localizacao"
                        placeholder="Ex: Rua das Flores, 123 — Centro">
                </div>

                <div class="form-group">
                    <label for="imagem">Foto do problema (opcional)</label>
                    <input type="file" id="imagem" name="imagem" accept="image/*"
                        @change="form.imagem = $event.target.files[0]">
                    <span class="text-sm text-muted">JPG, PNG ou WEBP — máximo 5MB</span>
                </div>

                <button type="submit" class="btn btn-primary btn-block" :disabled="enviando">
                    <span x-show="!enviando">Enviar solicitação</span>
                    <span x-show="enviando">Enviando...</span>
                </button>
            </form>
        </div>
    </div>
</section>
<?php else: ?>
<!-- CTA para não logados -->
<section class="section section-alt">
    <div class="container" style="text-align:center">
        <h2 class="section-title">Quer registrar uma solicitação?</h2>
        <p class="section-sub">Crie sua conta gratuitamente ou entre para abrir um chamado.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="<?= BASE_URL ?>/cadastro" class="btn btn-primary btn-lg">Criar conta</a>
            <a href="<?= BASE_URL ?>/entrar" class="btn btn-outline btn-lg">Já tenho conta</a>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function chamadoForm() {
    return {
        form: { categoria_id: '', titulo: '', descricao: '', localizacao: '', imagem: null },
        errors: {},
        enviando: false,
        sucesso: false,
        erro: false,
        mensagemSucesso: '',
        mensagemErro: '',

        validar() {
            this.errors = {};
            if (!this.form.categoria_id)       this.errors.categoria_id = 'Selecione uma categoria.';
            if (!this.form.titulo.trim())      this.errors.titulo = 'Título é obrigatório.';
            if (!this.form.descricao.trim())   this.errors.descricao = 'Descrição é obrigatória.';
            return Object.keys(this.errors).length === 0;
        },

        async enviar() {
            if (!this.validar()) return;
            this.enviando = true;
            this.erro = false;

            // FormData para suportar upload de arquivo
            const fd = new FormData();
            fd.append('categoria_id', this.form.categoria_id);
            fd.append('titulo',       this.form.titulo);
            fd.append('descricao',    this.form.descricao);
            fd.append('localizacao',  this.form.localizacao);
            if (this.form.imagem) fd.append('imagem', this.form.imagem);

            try {
                const resp = await fetch('<?= BASE_URL ?>/cidadao/abrir-chamado', {
                    method: 'POST',
                    body: fd,
                });
                const data = await resp.json();
                if (data.success) {
                    this.sucesso = true;
                    this.mensagemSucesso = data.message;
                    setTimeout(() => {
                        this.sucesso = false;
                        this.mensagemSucesso = '';
                        this.form = { categoria_id: '', titulo: '', descricao: '', localizacao: '', imagem: null };
                        this.errors = {};
                    }, 4000);
                } else {
                    this.erro = true;
                    this.mensagemErro = data.message || 'Erro ao enviar. Tente novamente.';
                }
            } catch (e) {
                this.erro = true;
                this.mensagemErro = 'Erro de conexão. Tente novamente.';
            } finally {
                this.enviando = false;
            }
        }
    };
}
</script>

<?php require __DIR__ . '/../shared/footer.php'; ?>
