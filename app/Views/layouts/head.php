<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Project Hub' ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
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

    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>