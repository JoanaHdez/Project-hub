<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<div class="dashboard">

    <?= $this->include('App\Modules\Dashboard\Views\sections\bienvenida') ?>

    <?= $this->include('App\Modules\Dashboard\Views\sections\tarjetas_resumen') ?>

    <?= $this->include('App\Modules\Dashboard\Views\sections\accesos_rapidos') ?>

    <?= $this->include('App\Modules\Dashboard\Views\sections\analitica') ?>

    <?= $this->include('App\Modules\Dashboard\Views\sections\actividad_reciente') ?>

</div>

<?= $this->endSection() ?>