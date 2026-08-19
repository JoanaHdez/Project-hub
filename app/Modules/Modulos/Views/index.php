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

            <?php foreach (($sistemas ?? []) as $sistema): ?>

            <article class="sistema-card" data-sistema-id="<?= esc(
        (string) (
            $sistema['id_sistema']
            ?? ''
        ),
        'attr'
    ) ?>" data-sistema-nombre="<?= esc(
        $sistema['nombre']
        ?? '',
        'attr'
    ) ?>" data-sistema-proyecto="<?= esc(
        $sistema['proyecto_nombre']
        ?? '',
        'attr'
    ) ?>" data-sistema-descripcion-valor="<?= esc(
    $sistema['descripcion']
    ?? '',
    'attr'
) ?>" tabindex="0" role="button" aria-label="Explorar <?= esc(
        $sistema['nombre']
        ?? 'Sistema',
        'attr'
    ) ?>">

                <span class="sistema-card__estado sistema-card__estado--azul"></span>


                <div class="sistema-card__imagen sistema-card__imagen--extorsion">

                    <div class="sistema-card__overlay"></div>

                    <div class="sistema-card__titulo-banner">

                        <span>
                            <?= esc(
                            strtoupper(
                                $sistema['tipo']
                                ?? 'SISTEMA'
                            )
                        ) ?>
                        </span>

                    </div>

                </div>


                <div class="sistema-card__contenido">

                    <h3>
                        <?= esc(
                        $sistema['nombre']
                        ?? 'Sistema sin nombre'
                    ) ?>
                    </h3>

                    <span class="sistema-card__proyecto">
                        <?= esc(
                        $sistema['proyecto_nombre']
                        ?? 'Sin proyecto'
                    ) ?>
                    </span>

                    <div class="sistema-card__meta">

                        <span class="sistema-card__badge">
                            <?= (int) (
                            $sistema['total_modulos']
                            ?? 0
                        ) ?>
                            módulos
                        </span>

                    </div>

                </div>

            </article>

            <?php endforeach; ?>

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

<script type="application/json" id="datos-modulos">
<?= json_encode(
    $modulos ?? [],
    JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
) ?>
</script>

<?= $this->endSection() ?>