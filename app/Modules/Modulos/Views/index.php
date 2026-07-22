<?= $this->extend('layouts/head') ?>

<?= $this->section('title') ?>
Módulos | Project Hub
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/modulos/modulos.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="modulos-page">

    <header class="modulos-header">

        <div class="modulos-header__contenido">

            <span class="modulos-header__eyebrow">
                Explorador visual
            </span>

            <h1 class="modulos-header__titulo">
                Módulos
            </h1>

            <p class="modulos-header__descripcion">
                Selecciona un sistema para consultar sus pantallas y
                funcionalidades internas.
            </p>

        </div>

    </header>

    <section class="selector-sistemas" data-vista-sistemas>

        <div class="selector-sistemas__encabezado">

            <div>

                <span class="selector-sistemas__etiqueta">
                    Paso 1
                </span>

                <h2>
                    Selecciona un sistema
                </h2>

                <p>
                    Elige el sistema que deseas explorar.
                </p>

            </div>

            <div class="sistema-card__meta">

                <span class="sistema-card__badge">
                    4 módulos
                </span>

            </div>
        </div>

        <div class="sistemas-grid">

            <article class="sistema-card" data-sistema="extorsion" tabindex="0" role="button"
                aria-label="Explorar Registro de Pláticas">

                    <span class="sistema-card__estado sistema-card__estado--azul"></span>

                <div class="sistema-card__imagen">

                    <div class="sistema-card__placeholder">
                        EXT
                    </div>

                </div>

                <div class="sistema-card__contenido">

                    <h3>
                        Registro de Pláticas
                    </h3>

                    <span class="sistema-card__proyecto">
                        Proyecto Extorsión
                    </span>

                    <div class="sistema-card__meta">

                        <span class="sistema-card__badge">
                            4 módulos
                        </span>

                    </div>

                </div>

            </article>

            <article class="sistema-card" data-sistema="eventos" tabindex="0" role="button"
                aria-label="Explorar Sistema de Eventos">

                <span class="sistema-card__estado sistema-card__estado--morado"></span>
                
                <div class="sistema-card__imagen">

                    <div class="sistema-card__placeholder">
                        EVT
                    </div>

                </div>

                <div class="sistema-card__contenido">

                    <h3>
                        Sistema de Eventos
                    </h3>

                    <span class="sistema-card__proyecto">
                        Proyecto Eventos
                    </span>

                    <div class="sistema-card__meta">

                        <span class="sistema-card__badge">
                            4 módulos
                        </span>

                    </div>

                </div>

            </article>

            <article class="sistema-card" data-sistema="optica" tabindex="0" role="button"
                aria-label="Explorar Gestión Óptica">

                    <span class="sistema-card__estado sistema-card__estado--morado"></span>

                    <div class="sistema-card__imagen">

                    <div class="sistema-card__placeholder">
                        OPT
                    </div>

                </div>

                <div class="sistema-card__contenido">

                    <h3>
                        Gestión Óptica
                    </h3>

                    <span class="sistema-card__proyecto">
                        Proyecto Óptica
                    </span>

                    <div class="sistema-card__meta">

                        <span class="sistema-card__badge">
                            4 módulos
                        </span>

                    </div>

                </div>

            </article>

        </div>

    </section>

    <section class="explorador-modulos" data-vista-modulos hidden>

        <button type="button" class="explorador-modulos__volver" data-volver-sistemas>
            <span aria-hidden="true">←</span>
            Cambiar sistema
        </button>

        <div class="explorador-modulos__encabezado">

            <div>

                <span class="explorador-modulos__proyecto">
                    Proyecto Extorsión
                </span>

                <h2 data-sistema-titulo>
                    Registro de Pláticas
                </h2>

                <p data-sistema-descripcion>
                    Explora las pantallas internas disponibles en este sistema.
                </p>

            </div>

            <div class="explorador-modulos__contador">

                <strong data-total-modulos>
                    4
                </strong>

                <span>
                    módulos registrados
                </span>

            </div>

        </div>

        <div class="modulos-grid" data-contenedor-modulos>
        </div>

    </section>

</section>

<script src="<?= base_url('assets/js/modulos/modulos.js') ?>"></script>

<?= $this->endSection() ?>