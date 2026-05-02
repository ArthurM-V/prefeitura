<?php

$pageTitle = 'Portal do Cidadão';
require __DIR__ . '/../shared/header.php';
?>

<!-- ========== NAVBAR ========== -->
<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <span class="navbar-brand-icon">🏛️</span>
            <span>Prefeitura Municipal</span>
        </a>
        <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm">
            Entrar
        </a>
    </div>
</header>

<!-- ========== HERO ========== -->
<section class="hero">
    <div class="container">
        <h2 class="hero-title">Portal do Cidadão</h2>
        <p class="hero-subtitle">Registre sua solicitação e acompanhe as melhorias realizadas em nossa cidade.</p>
        <a href="#novo-chamado" class="btn btn-primary btn-lg">
            Fazer uma solicitação
        </a>
    </div>
</section>

<!-- ========== SOLUÇÕES RECENTES ========== -->
<section class="section" id="solucoes">
    <div class="container">
        <h2 class="section-title">Últimas soluções realizadas</h2>
        <p class="section-sub">Veja o que a prefeitura resolveu recentemente para os cidadãos.</p>

        <?php if (empty($resolvidos)): ?>
            <div class="empty-state">
                <p>Nenhuma solução publicada ainda.</p>
            </div>
        <?php else: ?>
            <div class="cards-grid">
                <?php foreach ($resolvidos as $chamado): ?>
                    <article class="card card-solucao">
                        <div class="card-header">
                            <span class="badge badge-success">Resolvido</span>
                            <span class="card-categoria"><?= htmlspecialchars($chamado['categoria_nome']) ?></span>
                        </div>
                        <h3 class="card-title"><?= htmlspecialchars($chamado['titulo']) ?></h3>
                        <p class="card-desc"><?= htmlspecialchars($chamado['descricao']) ?></p>

                        <?php if (!empty($chamado['localizacao'])): ?>
                            <p class="card-meta">
                                📍 <?= htmlspecialchars($chamado['localizacao']) ?>
                            </p>
                        <?php endif; ?>

                        <?php if (!empty($chamado['feedback_mensagem'])): ?>
                            <div class="card-feedback">
                                <strong>Resposta da prefeitura:</strong>
                                <p><?= htmlspecialchars($chamado['feedback_mensagem']) ?></p>
                            </div>
                        <?php endif; ?>

                        <p class="card-date">
                            Resolvido em
                            <?= $chamado['data_fechamento']
                                ? date('d/m/Y', strtotime($chamado['data_fechamento']))
                                : '—' ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ========== FORMULÁRIO DE CHAMADO ========== -->
<section class="section section-alt" id="novo-chamado">
    <div class="container container-narrow">
        <h2 class="section-title">Registrar uma solicitação</h2>
        <p class="section-sub">Preencha o formulário abaixo. Nossa equipe analisará seu pedido e entrará em contato.</p>

        <div
            x-data="chamadoForm()"
            class="form-card"
        >
            <!-- Mensagem de sucesso -->
            <div x-show="sucesso" x-cloak class="alert alert-success" x-transition>
                <strong>Solicitação enviada!</strong>
                <span x-text="mensagemSucesso"></span>
            </div>

            <!-- Mensagem de erro -->
            <div x-show="erro" x-cloak class="alert alert-danger" x-transition>
                <span x-text="mensagemErro"></span>
            </div>

            <form x-show="!sucesso" @submit.prevent="enviar()">

                <div class="form-row">
                    <div class="form-group">
                        <label for="nome">Nome completo <span class="required">*</span></label>
                        <input
                            type="text"
                            id="nome"
                            x-model="form.nome"
                            placeholder="Seu nome"
                            :class="{ 'input-error': errors.nome }"
                            required
                        >
                        <span x-show="errors.nome" class="field-error" x-text="errors.nome"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail <span class="required">*</span></label>
                        <input
                            type="email"
                            id="email"
                            x-model="form.email"
                            placeholder="seu@email.com"
                            :class="{ 'input-error': errors.email }"
                            required
                        >
                        <span x-show="errors.email" class="field-error" x-text="errors.email"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoria <span class="required">*</span></label>
                    <select
                        id="categoria_id"
                        x-model="form.categoria_id"
                        :class="{ 'input-error': errors.categoria_id }"
                        required
                    >
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span x-show="errors.categoria_id" class="field-error" x-text="errors.categoria_id"></span>
                </div>

                <div class="form-group">
                    <label for="titulo">Título da solicitação <span class="required">*</span></label>
                    <input
                        type="text"
                        id="titulo"
                        x-model="form.titulo"
                        placeholder="Descreva brevemente o problema"
                        :class="{ 'input-error': errors.titulo }"
                        required
                    >
                    <span x-show="errors.titulo" class="field-error" x-text="errors.titulo"></span>
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição detalhada <span class="required">*</span></label>
                    <textarea
                        id="descricao"
                        x-model="form.descricao"
                        rows="4"
                        placeholder="Descreva o problema com o máximo de detalhes possível..."
                        :class="{ 'input-error': errors.descricao }"
                        required
                    ></textarea>
                    <span x-show="errors.descricao" class="field-error" x-text="errors.descricao"></span>
                </div>

                <div class="form-group">
                    <label for="localizacao">Localização</label>
                    <input
                        type="text"
                        id="localizacao"
                        x-model="form.localizacao"
                        placeholder="Ex: Rua das Flores, 123 — Bairro Centro"
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-primary btn-block"
                    :disabled="enviando"
                >
                    <span x-show="!enviando">Enviar solicitação</span>
                    <span x-show="enviando">Enviando...</span>
                </button>

            </form>
        </div>
    </div>
</section>

<script>
function chamadoForm() {
    return {
        form: {
            nome: '',
            email: '',
            categoria_id: '',
            titulo: '',
            descricao: '',
            localizacao: '',
        },
        errors: {},
        enviando: false,
        sucesso: false,
        erro: false,
        mensagemSucesso: '',
        mensagemErro: '',

        validar() {
            this.errors = {};
            if (!this.form.nome.trim())        this.errors.nome = 'Nome é obrigatório.';
            if (!this.form.email.trim())       this.errors.email = 'E-mail é obrigatório.';
            if (!this.form.categoria_id)       this.errors.categoria_id = 'Selecione uma categoria.';
            if (!this.form.titulo.trim())      this.errors.titulo = 'Título é obrigatório.';
            if (!this.form.descricao.trim())   this.errors.descricao = 'Descrição é obrigatória.';
            return Object.keys(this.errors).length === 0;
        },

        async enviar() {
            if (!this.validar()) return;
            this.enviando = true;
            this.erro = false;

            try {
                const resp = await fetch('<?= BASE_URL ?>/novo-chamado', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(this.form),
                });
                const data = await resp.json();

                if (data.success) {
                    this.sucesso = true;
                    this.mensagemSucesso = data.message;
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
