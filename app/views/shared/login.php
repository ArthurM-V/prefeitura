<?php
$pageTitle = 'Acesso Administrativo';
require __DIR__ . '/header.php';
?>

<main class="login-page">
    <div class="login-card">

        <div class="login-brand">
            <span class="login-brand-icon">ADM</span>
            <h1>Prefeitura Municipal</h1>
            <p>Painel Administrativo</p>
        </div>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($erro) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login" class="login-form">
            <div class="form-group">
                <label for="identificador">E-mail</label>
                <input
                    type="email"
                    id="identificador"
                    name="identificador"
                    value="<?= htmlspecialchars($_POST['identificador'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input
                    type="password"
                    id="senha"
                    name="senha"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Entrar
            </button>
        </form>

        <div class="login-back">
            <a href="<?= BASE_URL ?>/">Voltar ao portal</a>
        </div>

    </div>
</main>

</body>
</html>
