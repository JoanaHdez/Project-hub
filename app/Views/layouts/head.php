<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Project Hub' ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <link rel="stylesheet" href="<?= base_url('assets/css/apis/ficha_tecnica.css') ?>">
</head>

<body>

    <?= $this->include('components/header') ?>

    <div class="app-container">
        <?= $this->include('components/sidebar') ?>

        <main class="main-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <?= $this->include('components/footer') ?>

    <script src="<?= base_url('assets/js/apis/documentacion.js') ?>" defer></script>

    <script src="<?= base_url('assets/js/apis/ficha_tecnica.js') ?>" defer></script>

    <script src="<?= base_url('assets/js/core/modales.js') ?>" defer></script>
    
    <script src="<?= base_url('assets/js/core/tablas.js') ?>" defer></script>
    
    <script src="<?= base_url('assets/js/core/util.js') ?>" defer></script>

    <script src="<?= base_url('assets/js/modulos/modulos.js') ?>" defer></script>

    <script src="<?= base_url('assets/js/sistemas/sistemas.js') ?>" defer></script>
    
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>

</html>