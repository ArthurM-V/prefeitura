<?php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? APP_NAME) ?> — Prefeitura Municipal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="<?= BASE_URL ?>/js/app.js"></script>
</head>
<body>
<div class="gov-stripe"></div>