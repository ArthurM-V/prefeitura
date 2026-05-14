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

$imgCidadao = null;
$imgAdmin   = null;
foreach ($imagens as $img) {
    if ($img['tipo'] === 'cidadao') $imgCidadao = $img;
    if ($img['tipo'] === 'admin')   $imgAdmin   = $img;
}
?>

<?php require __DIR__ . '/navbar.php'; ?>

<main class="admin-main">
    <div class="container">

        <div class="page-header">
            <div>
                <a href="<?= BASE_URL ?>/admin/chamados" class="back-link">← Voltar aos chamados</a>
                <h2>
                    Chamado #<?= $chamado['id'] ?>
                    <span class="badge <?= $statusColors[$chamado['status_nome']] ?? 'badge-neutral' ?>">
                        <?= htmlspecialchars($chamado['status_nome']) ?>
                    </span>
                </h2>
            </div>
        </div>

        <div class="detail-grid">

            <!-- ===== COLUNA PRINCIPAL ===== -->
            <div class="detail-main">

                <!-- Info do chamado -->
                <section class="card">
                    <div class="card-header-admin"><h3><?= htmlspecialchars($chamado['titulo']) ?></h3></div>
                    <div class="card-body">
                        <dl class="info-list">
                            <dt>Descrição</dt>
                            <dd><?= nl2br(htmlspecialchars($chamado['descricao'])) ?></dd>
                            <dt>Cidadão</dt>
                            <dd>
                                <?= htmlspecialchars($chamado['usuario_nome']) ?>
                                <span class="text-muted">(<?= htmlspecialchars($chamado['usuario_email']) ?>)</span>
                            </dd>
                            <dt>Categoria</dt>
                            <dd><?= htmlspecialchars($chamado['categoria_nome']) ?></dd>
                            <?php if (!empty($chamado['localizacao'])): ?>
                                <dt>Localização</dt>
                                <dd>📍 <?= htmlspecialchars($chamado['localizacao']) ?></dd>
                            <?php endif; ?>
                            <dt>Abertura</dt>
                            <dd><?= date('d/m/Y H:i', strtotime($chamado['data_abertura'])) ?></dd>
                            <?php if ($chamado['data_fechamento']): ?>
                                <dt>Encerramento</dt>
                                <dd><?= date('d/m/Y H:i', strtotime($chamado['data_fechamento'])) ?></dd>
                            <?php endif; ?>
                            <dt>Órgão</dt>
                            <dd><?= $chamado['orgao_nome']
                                ? htmlspecialchars($chamado['orgao_nome'])
                                : '<em class="text-muted">Não atribuído</em>' ?></dd>
                        </dl>

                        <?php if ($imgCidadao): ?>
                            <div style="margin-top:1rem">
                                <p class="text-sm text-muted" style="margin-bottom:.4rem">Foto enviada pelo cidadão:</p>
                                <img src="<?= BASE_URL ?>/imgs/uploads/<?= htmlspecialchars($imgCidadao['caminho']) ?>"
                                     alt="Foto do problema"
                                     style="max-width:100%;border-radius:8px;border:1px solid var(--border)">
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Feedbacks -->
                <section class="card">
                    <div class="card-header-admin"><h3>Feedbacks enviados</h3></div>
                    <div class="card-body">
                        <?php if (empty($feedbacks)): ?>
                            <p class="text-muted">Nenhum feedback enviado ainda.</p>
                        <?php else: ?>
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
                                            <?= htmlspecialchars($f['autor']) ?>
                                            · <?= date('d/m/Y H:i', strtotime($f['data'])) ?>
                                        </p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Histórico -->
                <section class="card">
                    <div class="card-header-admin"><h3>Histórico de ações</h3></div>
                    <div class="card-body">
                        <?php if (empty($historicos)): ?>
                            <p class="text-muted">Nenhum registro no histórico.</p>
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
                                                <?= htmlspecialchars($h['usuario_nome']) ?>
                                                · <?= date('d/m/Y H:i', strtotime($h['data'])) ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Chamados semelhantes -->
                <?php if (!empty($semelhantes)): ?>
                    <section class="card">
                        <div class="card-header-admin">
                            <h3>Chamados semelhantes</h3>
                            <span class="text-muted text-sm"><?= htmlspecialchars($chamado['categoria_nome']) ?></span>
                        </div>
                        <div class="card-body">
                            <ul class="similar-list">
                                <?php foreach ($semelhantes as $s): ?>
                                    <li class="similar-item">
                                        <a href="<?= BASE_URL ?>/admin/chamado/<?= $s['id'] ?>" class="link-primary">
                                            #<?= $s['id'] ?> — <?= htmlspecialchars($s['titulo']) ?>
                                        </a>
                                        <span class="badge <?= $statusColors[$s['status_nome']] ?? 'badge-neutral' ?> badge-sm">
                                            <?= htmlspecialchars($s['status_nome']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>
                <?php endif; ?>

            </div><!-- /.detail-main -->

            <!-- ===== COLUNA LATERAL (AÇÕES) ===== -->
            <aside class="detail-aside">

                <!-- Alterar status -->
                <section class="card" x-data="acoes(<?= $chamado['id'] ?>)">
                    <div class="card-header-admin"><h3>Alterar status</h3></div>
                    <div class="card-body">
                        <div x-show="msg" x-cloak class="alert" :class="msgTipo==='sucesso'?'alert-success':'alert-danger'" x-text="msg" x-transition></div>
                        <select x-model="novoStatus" class="mb-2">
                            <option value="">Selecione o status</option>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $s['id'] == $chamado['status_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary btn-block" @click="alterarStatus()" :disabled="carregando">
                            <span x-show="!carregando">Atualizar status</span>
                            <span x-show="carregando">Salvando...</span>
                        </button>
                    </div>
                </section>

                <!-- Atribuir órgão -->
                <section class="card" x-data="acoes(<?= $chamado['id'] ?>)">
                    <div class="card-header-admin"><h3>Atribuir órgão</h3></div>
                    <div class="card-body">
                        <div x-show="msg" x-cloak class="alert" :class="msgTipo==='sucesso'?'alert-success':'alert-danger'" x-text="msg" x-transition></div>
                        <select x-model="novoOrgao" class="mb-2">
                            <option value="">Selecione o órgão</option>
                            <?php foreach ($orgaos as $o): ?>
                                <option value="<?= $o['id'] ?>" <?= $o['id'] == $chamado['orgao_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($o['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary btn-block" @click="atribuirOrgao()" :disabled="carregando">
                            <span x-show="!carregando">Atribuir</span>
                            <span x-show="carregando">Salvando...</span>
                        </button>
                    </div>
                </section>

                <!-- Enviar feedback -->
                <section class="card" x-data="acoesComImagem(<?= $chamado['id'] ?>)">
                    <div class="card-header-admin"><h3>Enviar feedback ao cidadão</h3></div>
                    <div class="card-body">
                        <div x-show="msg" x-cloak class="alert" :class="msgTipo==='sucesso'?'alert-success':'alert-danger'" x-text="msg" x-transition></div>
                        <div class="form-group">
                            <textarea x-model="mensagemFeedback" rows="4"
                                placeholder="Escreva a resposta para o cidadão..."></textarea>
                        </div>
                        <div class="form-group">
                            <label style="font-size:.8rem;font-weight:500">Foto da solução (opcional)</label>
                            <input type="file" accept="image/*" @change="imagemFile = $event.target.files[0]">
                            <span class="text-sm text-muted">JPG, PNG ou WEBP — máx. 5MB</span>
                        </div>
                        <button class="btn btn-success btn-block" @click="enviarFeedback()" :disabled="carregando">
                            <span x-show="!carregando">Enviar e marcar como resolvido</span>
                            <span x-show="carregando">Enviando...</span>
                        </button>
                    </div>
                </section>

                <!-- Excluir -->
                <section class="card card-danger">
                    <div class="card-header-admin"><h3>Zona de risco</h3></div>
                    <div class="card-body">
                        <p class="text-sm text-muted mb-2">A exclusão remove permanentemente o chamado e todo seu histórico.</p>
                        <button class="btn btn-danger btn-block"
                            onclick="confirmarExclusao(<?= $chamado['id'] ?>, '<?= htmlspecialchars(addslashes($chamado['titulo'])) ?>')">
                            Excluir chamado
                        </button>
                    </div>
                </section>

            </aside>
        </div>
    </div>
</main>

<!-- Modal exclusão -->
<div id="modal-exclusao" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <h3>Confirmar exclusão</h3>
        <p id="modal-texto"></p>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="document.getElementById('modal-exclusao').style.display='none'">Cancelar</button>
            <button class="btn btn-danger" id="btn-confirmar-exclusao">Excluir</button>
        </div>
    </div>
</div>

<script>
function acoes(chamadoId) {
    return {
        chamadoId,
        novoStatus: '',
        novoOrgao: '',
        carregando: false,
        msg: '',
        msgTipo: 'sucesso',

        async post(url, body) {
            this.carregando = true;
            this.msg = '';
            try {
                const resp = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(body),
                });
                return await resp.json();
            } catch {
                return { success: false, message: 'Erro de conexão.' };
            } finally {
                this.carregando = false;
            }
        },

        async alterarStatus() {
            if (!this.novoStatus) return;
            const data = await this.post('<?= BASE_URL ?>/admin/alterar-status', {
                chamado_id: this.chamadoId, status_id: this.novoStatus
            });
            this.msg = data.message;
            this.msgTipo = data.success ? 'sucesso' : 'erro';
            if (data.success) setTimeout(() => location.reload(), 1200);
        },

        async atribuirOrgao() {
            if (!this.novoOrgao) return;
            const data = await this.post('<?= BASE_URL ?>/admin/atribuir-orgao', {
                chamado_id: this.chamadoId, orgao_id: this.novoOrgao
            });
            this.msg = data.message;
            this.msgTipo = data.success ? 'sucesso' : 'erro';
            if (data.success) setTimeout(() => location.reload(), 1200);
        }
    };
}

function acoesComImagem(chamadoId) {
    return {
        chamadoId,
        mensagemFeedback: '',
        imagemFile: null,
        carregando: false,
        msg: '',
        msgTipo: 'sucesso',

        async enviarFeedback() {
            if (!this.mensagemFeedback.trim()) {
                this.msg = 'A mensagem não pode estar vazia.';
                this.msgTipo = 'erro';
                return;
            }
            this.carregando = true;
            this.msg = '';

            const fd = new FormData();
            fd.append('chamado_id', this.chamadoId);
            fd.append('mensagem', this.mensagemFeedback);
            if (this.imagemFile) fd.append('imagem', this.imagemFile);

            try {
                const resp = await fetch('<?= BASE_URL ?>/admin/enviar-feedback', {
                    method: 'POST',
                    body: fd,
                });
                const data = await resp.json();
                this.msg = data.message;
                this.msgTipo = data.success ? 'sucesso' : 'erro';
                if (data.success) setTimeout(() => location.reload(), 1500);
            } catch {
                this.msg = 'Erro de conexão.';
                this.msgTipo = 'erro';
            } finally {
                this.carregando = false;
            }
        }
    };
}

let chamadoParaExcluir = null;
function confirmarExclusao(id, titulo) {
    chamadoParaExcluir = id;
    document.getElementById('modal-texto').textContent =
        `Tem certeza que deseja excluir o chamado "${titulo}"?`;
    document.getElementById('modal-exclusao').style.display = 'flex';
}
document.getElementById('btn-confirmar-exclusao').addEventListener('click', async () => {
    if (!chamadoParaExcluir) return;
    const resp = await fetch('<?= BASE_URL ?>/admin/excluir-chamado', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `chamado_id=${chamadoParaExcluir}`,
    });
    const data = await resp.json();
    if (data.success) window.location.href = '<?= BASE_URL ?>/admin/chamados';
    else alert(data.message || 'Erro ao excluir.');
});
document.getElementById('modal-exclusao').addEventListener('click', (e) => {
    if (e.target === e.currentTarget) e.currentTarget.style.display = 'none';
});
</script>

<?php require __DIR__ . '/../shared/footer.php'; ?>