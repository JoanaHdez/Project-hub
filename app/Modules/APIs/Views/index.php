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

<?= view('App\Modules\APIs\Views\modals\estado_api') ?>

<?= view('App\Modules\APIs\Views\modals\administrar_api') ?>

<?= view('App\Modules\APIs\Views\modals\confirmar_accion_api') ?>

<?= view('App\Modules\APIs\Views\modals\completar_arquitectura') ?>

<?= view('App\Modules\APIs\Views\modals\completar_dependencia') ?>

<?= view('App\Modules\APIs\Views\modals\completar_observacion') ?>

<?= view('App\Modules\APIs\Views\modals\completar_historial') ?>

<?= view(
    'App\Modules\APIs\Views\modals\nueva_api',
    [
        'proyectos' => $proyectos ?? [],
    ]
) ?>

<?= view(
    'App\Modules\APIs\Views\modals\editar_api',
    [
        'proyectos' => $proyectos ?? [],
    ]
) ?>


<?= $this->endSection() ?>