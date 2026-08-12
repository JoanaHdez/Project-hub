<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<?= view('App\Modules\APIs\Views\partials\encabezado') ?>

<div class="modulo-grid">

    <?= view(
        'App\Modules\APIs\Views\partials\catalogo',
        ['apis' => $apis]
    ) ?>

    <?= view('App\Modules\APIs\Views\partials\documentacion') ?>

</div>

<?= view('App\Modules\APIs\Views\modals\ficha_tecnica') ?>
<?= view(
    'App\Modules\APIs\Views\modals\nueva_api',
    [
        'proyectos' => $proyectos ?? [],
    ]
) ?>

<?= $this->endSection() ?>