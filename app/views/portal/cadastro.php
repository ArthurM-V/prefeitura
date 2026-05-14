<?php
$pageTitle = 'Criar conta';
require __DIR__ . '/../shared/header.php';
?>

<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <span class="navbar-brand-icon">🏛️</span>
            <span>Prefeitura Municipal</span>
        </a>
        <a href="<?= BASE_URL ?>/entrar" class="btn btn-outline btn-sm on-dark">Já tenho conta</a>
    </div>
</header>

<main class="login-page">
    <div class="login-card" style="max-width:480px">

        <div class="login-brand">
            <span class="login-brand-icon">📋</span>
            <h1>Criar conta</h1>
            <p>Cadastre-se para abrir e acompanhar chamados</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/cadastro">

            <div class="form-group">
                <label for="nome">Nome completo <span class="required">*</span></label>
                <input type="text" id="nome" name="nome"
                    placeholder="Seu nome completo"
                    value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                    required autofocus>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-mail <span class="required">*</span></label>
                    <input type="email" id="email" name="email"
                        placeholder="seu@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF <span class="required">*</span></label>
                    <input type="text" id="cpf" name="cpf"
                        placeholder="000.000.000-00"
                        value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>"
                        maxlength="14"
                        required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="senha">Senha <span class="required">*</span></label>
                    <input type="password" id="senha" name="senha"
                        placeholder="Mínimo 6 caracteres"
                        required>
                </div>

                <div class="form-group">
                    <label for="confirma_senha">Confirmar senha <span class="required">*</span></label>
                    <input type="password" id="confirma_senha" name="confirma_senha"
                        placeholder="Repita a senha"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Criar conta
            </button>
        </form>

        <div class="login-back" style="margin-top:.75rem">
            Já tem conta? <a href="<?= BASE_URL ?>/entrar">Entrar</a>
        </div>
        <div class="login-back">
            <a href="<?= BASE_URL ?>/">← Voltar ao portal</a>
        </div>

    </div>
</main>

<?php require __DIR__ . '/../shared/footer.php'; ?>