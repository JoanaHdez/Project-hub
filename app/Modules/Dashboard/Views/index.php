<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<section class="dashboard">
    <div class="dashboard__header">
        <div>
            <h2>Dashboard</h2>
            <p>Resumen general de los proyectos y sistemas registrados.</p>
        </div>
    </div>

    <div class="dashboard__cards">
        <article class="summary-card">
            <span class="summary-card__label">Proyectos</span>
            <strong class="summary-card__value">0</strong>
        </article>

        <article class="summary-card">
            <span class="summary-card__label">Sistemas</span>
            <strong class="summary-card__value">0</strong>
        </article>

        <article class="summary-card">
            <span class="summary-card__label">Módulos</span>
            <strong class="summary-card__value">0</strong>
        </article>

        <article class="summary-card">
            <span class="summary-card__label">APIs documentadas</span>
            <strong class="summary-card__value">0</strong>
        </article>
    </div>

    <div class="dashboard__grid">
        <section class="dashboard-panel">
            <h3>Analítica</h3>
            <p>En este espacio se integrará el reporte de Power BI.</p>
        </section>

        <section class="dashboard-panel">
            <h3>Accesos rápidos</h3>

            <div class="quick-links">
                <a href="#">Registrar proyecto</a>
                <a href="#">Consultar sistemas</a>
                <a href="#">Ver módulos</a>
                <a href="#">Consultar APIs</a>
            </div>
        </section>
    </div>
</section>

<?= $this->endSection() ?>