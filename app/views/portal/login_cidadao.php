<?php
$pageTitle = 'Entrar';
require __DIR__ . '/../shared/header.php';
?>
<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <span class="navbar-brand-icon">🏛️</span>
            <span>Prefeitura Municipal</span>
        </a>
        <a href="<?= BASE_URL ?>/login" class="btn btn-outline btn-sm on-dark">Acesso administrativo</a>
    </div>
</header>

<main class="login-page">
    <div class="login-card">
        <div class="login-brand">
            <span class="login-brand-icon">👤</span>
            <h1>Área do Cidadão</h1>
            <p>Entre com seu e-mail ou CPF</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/entrar" class="login-form">
            <div class="form-group">
                <label for="identificador">E-mail ou CPF</label>
                <input type="text" id="identificador" name="identificador"
                    placeholder="seu@email.com ou 000.000.000-00"
                    value="<?= htmlspecialchars($_POST['identificador'] ?? '') ?>"
                    required autofocus>
            </div>
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <div class="login-back" style="margin-top:.75rem">
            Não tem conta? <a href="<?= BASE_URL ?>/cadastro">Cadastre-se</a>
        </div>
        <div class="login-back">
            <a href="<?= BASE_URL ?>/">← Voltar ao portal</a>
        </div>
    </div>
</main>
<?php require __DIR__ . '/../shared/footer.php'; ?>