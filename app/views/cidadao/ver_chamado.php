<?php
$pageTitle = 'Chamado #' . $chamado['id'];
require __DIR__ . '/../shared/header.php';

$statusColors = [
    'Aberto'       => 'badge-warning',
    'Em Análise'   => 'badge-info',
    'Em Andamento' => 'badge-primary',
    'Resolvido'    => 'badge-success',
    'Encerrado'    => 'badge-neutral',
];
?>

<header class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <span>Prefeitura Municipal</span>
        </a>
        <div style="display:flex;gap:.5rem;align-items:center">
            <span style="font-size:.85rem;color:rgba(255,255,255,.8)">
                <?= htmlspecialchars($_SESSION['nome']) ?>
            </span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-outline btn-sm on-dark">Sair</a>
        </div>
    </div>
</header>

<main class="admin-main">
    <div class="container" style="max-width:780px">

        <div class="page-header">
            <a href="<?= BASE_URL ?>/cidadao/dashboard" class="back-link">← Meus chamados</a>
            <h2>
                Chamado #<?= $chamado['id'] ?>
                <span class="badge <?= $statusColors[$chamado['status_nome']] ?? 'badge-neutral' ?>">
                    <?= htmlspecialchars($chamado['status_nome']) ?>
                </span>
            </h2>
        </div>

        <!-- Dados do chamado -->
        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin">
                <h3><?= htmlspecialchars($chamado['titulo']) ?></h3>
            </div>
            <div class="card-body">
                <dl class="info-list">
                    <dt>Descrição</dt>
                    <dd><?= nl2br(htmlspecialchars($chamado['descricao'])) ?></dd>

                    <dt>Categoria</dt>
                    <dd><?= htmlspecialchars($chamado['categoria_nome']) ?></dd>

                    <?php if (!empty($chamado['localizacao'])): ?>
                        <dt>Localização</dt>
                        <dd>📍 <?= htmlspecialchars($chamado['localizacao']) ?></dd>
                    <?php endif; ?>

                    <dt>Aberto em</dt>
                    <dd><?= date('d/m/Y H:i', strtotime($chamado['data_abertura'])) ?></dd>

                    <dt>Órgão responsável</dt>
                    <dd><?= $chamado['orgao_nome']
                        ? htmlspecialchars($chamado['orgao_nome'])
                        : '<em class="text-muted">Ainda não atribuído</em>' ?></dd>

                    <dt>Empresa parceira</dt>
                    <dd><?= $chamado['empresa_nome']
                        ? htmlspecialchars($chamado['empresa_nome'])
                        : '<em class="text-muted">Ainda não atribuída</em>' ?></dd>
                </dl>

                <!-- Imagem enviada pelo cidadão -->
                <?php
                $imgCidadao = array_filter($imagens, fn($i) => $i['tipo'] === 'cidadao');
                $imgCidadao = reset($imgCidadao);
                ?>
                <?php if ($imgCidadao): ?>
                    <div style="margin-top:1rem">
                        <p class="text-sm text-muted" style="margin-bottom:.4rem">Foto enviada por você:</p>
                        <img src="<?= BASE_URL ?>/imgs/uploads/<?= htmlspecialchars($imgCidadao['caminho']) ?>"
                             alt="Foto do problema"
                             style="max-width:100%;border-radius:8px;border:1px solid var(--border)">
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Respostas da prefeitura -->
        <section class="card" style="margin-bottom:1.25rem">
            <div class="card-header-admin">
                <h3>Respostas da prefeitura</h3>
            </div>
            <div class="card-body">
                <?php if (empty($feedbacks)): ?>
                    <p class="text-muted">Nenhuma resposta ainda. Aguarde a análise da sua solicitação.</p>
                <?php else: ?>
                    <?php
                    $imgAdmin = array_filter($imagens, fn($i) => $i['tipo'] === 'admin');
                    $imgAdmin = reset($imgAdmin);
                    ?>
                    <ul class="feedback-list">
                        <?php foreach ($feedbacks as $f): ?>
                            <li class="feedback-item">
                                <p class="feedback-msg"><?= nl2br(htmlspecialchars($f['mensagem'])) ?></p>
                                <?php if ($imgAdmin): ?>
                                    <div style="margin-top:.75rem">
                                        <p class="text-sm text-muted" style="margin-bottom:.4rem">Foto da solução:</p>
                                        <img src="<?= BASE_URL ?>/imgs/uploads/<?= htmlspecialchars($imgAdmin['caminho']) ?>"
                                             alt="Foto da solução"
                                             style="max-width:100%;border-radius:8px;border:1px solid var(--border)">
                                    </div>
                                <?php endif; ?>
                                <p class="feedback-meta">
                                    Prefeitura Municipal · <?= date('d/m/Y H:i', strtotime($f['data'])) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>

        <!-- Histórico -->
        <section class="card">
            <div class="card-header-admin">
                <h3>Histórico de atualizações</h3>
            </div>
            <div class="card-body">
                <?php if (empty($historicos)): ?>
                    <p class="text-muted">Nenhum registro ainda.</p>
                <?php else: ?>
                    <ul class="activity-list">
                        <?php foreach ($historicos as $h): ?>
                            <li class="activity-item">
                                <div class="activity-dot"></div>
                                <div class="activity-body">
                                    <p class="activity-desc">
                                        <strong><?= htmlspecialchars($h['tipo_acao']) ?></strong>
                                        — <?= htmlspecialchars($h['descricao']) ?>
                                    </p>
                                    <p class="activity-meta">
                                        <?= date('d/m/Y H:i', strtotime($h['data'])) ?>
                                    </p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </section>

    </div>
</main>

<?php require __DIR__ . '/../shared/footer.php'; ?>
