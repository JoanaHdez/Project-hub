<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<div class="proyectos">

    <?= $this->include('App\Modules\Proyectos\Views\sections\encabezado') ?>

    <?= $this->include('App\Modules\Proyectos\Views\sections\proyectos_destacados') ?>

    <?= $this->include('App\Modules\Proyectos\Views\sections\listado') ?>

    <?= view(
        'App\Modules\Proyectos\Views\sections\especificaciones',
        [
            'especificaciones' =>
            $especificaciones ?? [],

            'proyectos' =>
            $proyectos ?? [],
        ]
    ) ?>

</div>

<?= $this->include('App\Modules\Proyectos\Views\modals\nuevo_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\editar_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\detalle_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\ficha_tecnica') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\eliminar_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\confirmar_accion_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\sistemas_asociados') ?>
<?= $this->include('App\Modules\Sistemas\Views\modals\nuevo_sistema') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\confirmar_eliminar_especificacion') ?>

<?= view('App\Modules\Sistemas\Views\modals\nuevo_sistema') ?>
<?= view('App\Modules\Sistemas\Views\modals\detalle_sistema') ?>
<?= view('App\Modules\Sistemas\Views\modals\editar_sistema') ?>
<?= view('App\Modules\Sistemas\Views\modals\eliminar_sistema') ?>
<?= view('App\Modules\Sistemas\Views\modals/confirmar_accion_sistema') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\nueva_ficha_tecnica') ?>

<?= $this->endSection() ?>