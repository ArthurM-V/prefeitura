<?php
$pageTitle = 'Entrar';
require __DIR__ . '/../shared/header.php';
?>

<main class="login-page">
    <div class="login-card">
        <div class="login-brand">
            <span class="login-brand-icon">ID</span>
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
                <input type="password" id="senha" name="senha" placeholder="********" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <div class="login-back" style="margin-top:.75rem">
            Nao tem conta? <a href="<?= BASE_URL ?>/cadastro">Cadastre-se</a>
        </div>
        <div class="login-back">
            <a href="<?= BASE_URL ?>/">Voltar ao portal</a>
            <span style="color:var(--text-muted);margin:0 .35rem">|</span>
            <a href="<?= BASE_URL ?>/login">Acesso administrativo</a>
        </div>
    </div>
</main>

</body>
</html>
