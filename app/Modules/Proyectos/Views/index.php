<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<div class="proyectos">

    <?= $this->include('App\Modules\Proyectos\Views\sections\encabezado') ?>

    <?= $this->include('App\Modules\Proyectos\Views\sections\proyectos_destacados') ?>

    <?= $this->include('App\Modules\Proyectos\Views\sections\listado') ?>

</div>

<?= $this->include('App\Modules\Proyectos\Views\modals\nuevo_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\editar_proyecto') ?>
<?= $this->include('App\Modules\Proyectos\Views\modals\ficha_tecnica') ?>

<?= $this->endSection() ?>