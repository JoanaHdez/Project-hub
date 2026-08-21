<?= $this->extend('layouts/head') ?>

<?= $this->section('title') ?>
Mis sistemas | Project Hub
<?= $this->endSection() ?>

<?= $this->section('styles') ?>

<link
    rel="stylesheet"
    href="<?= base_url(
        'assets/css/mis_sistemas/mis_sistemas.css'
    ) ?>"
>

<?= $this->endSection() ?>


<?= $this->section('content') ?>

<section class="mis-sistemas-page">


    <!--==============================================
    =                 ENCABEZADO                    =
    ===============================================-->

    <header class="mis-sistemas-header">

        <div class="mis-sistemas-header__contenido">

            <span class="mis-sistemas-header__eyebrow">
                Acceso a sistemas
            </span>

            <h1 class="mis-sistemas-header__titulo">
                Mis sistemas
            </h1>

            <p class="mis-sistemas-header__descripcion">
                Selecciona un sistema para acceder.
            </p>

        </div>

    </header>


    <!--==============================================
    =             SISTEMAS DISPONIBLES              =
    ===============================================-->

    <section class="mis-sistemas-contenido">

        <div class="mis-sistemas-contenido__encabezado">

            <h2>
                Sistemas disponibles
            </h2>

            <p>
                Aquí aparecen únicamente los sistemas activos
                disponibles para tu usuario.
            </p>

        </div>


        <?php if (!empty($sistemas)): ?>

            <div class="mis-sistemas-grid">

                <?php foreach ($sistemas as $sistema): ?>

                    <?php

                    $urlSistema =
                        trim(
                            (string) (
                                $sistema['url']
                                ?? ''
                            )
                        );

                    $nombreSistema =
                        $sistema['nombre']
                        ?? 'Sistema';

                    $proyectoNombre =
                        $sistema['proyecto_nombre']
                        ?? 'Sin proyecto';

                    $tipoSistema =
                        strtoupper(
                            trim(
                                (string) (
                                    $sistema['tipo']
                                    ?? 'Sistema'
                                )
                            )
                        );

                    ?>

                    <a
                        href="<?= $urlSistema !== ''
                            ? esc(
                                $urlSistema,
                                'attr'
                            )
                            : '#'
                        ?>"
                        class="mis-sistema-card"
                        <?= $urlSistema !== ''
                            ? 'target="_blank" rel="noopener noreferrer"'
                            : 'aria-disabled="true" tabindex="-1"'
                        ?>
                    >

                        <!--==================================
                        =              ESTADO               =
                        ===================================-->

                        <span
                            class="mis-sistema-card__estado"
                            aria-hidden="true"
                        ></span>


                        <!--==================================
                        =              BANNER               =
                        ===================================-->

                        <?php

$imagenModulo =
    trim(
        (string) (
            $sistema['imagen']
            ?? ''
        )
    );

?>

<div
    class="
        mis-sistema-card__banner
        <?= $imagenModulo !== ''
            ? 'mis-sistema-card__banner--con-imagen'
            : ''
        ?>
    "
>

    <?php if ($imagenModulo !== ''): ?>

        <img
            src="<?= esc(
                $imagenModulo,
                'attr'
            ) ?>"
            alt="<?= esc(
                'Vista previa de ' . $nombreSistema,
                'attr'
            ) ?>"
            class="mis-sistema-card__imagen"
        >

        <div class="mis-sistema-card__overlay"></div>

    <?php endif; ?>


    <span class="mis-sistema-card__tipo">
        <?= esc(
            $tipoSistema
        ) ?>
    </span>

</div>


                        <!--==================================
                        =             CONTENIDO             =
                        ===================================-->

                        <div class="mis-sistema-card__contenido">

                            <span class="mis-sistema-card__proyecto">
                                <?= esc(
                                    $proyectoNombre
                                ) ?>
                            </span>

                            <h3 class="mis-sistema-card__titulo">
                                <?= esc(
                                    $nombreSistema
                                ) ?>
                            </h3>

                            <span class="mis-sistema-card__accion">

                                <?= $urlSistema !== ''
                                    ? 'Abrir sistema →'
                                    : 'URL no disponible'
                                ?>

                            </span>

                        </div>

                    </a>

                <?php endforeach; ?>

            </div>


        <?php else: ?>

            <div class="mis-sistemas-vacio">

                <span aria-hidden="true">
                    🖥️
                </span>

                <strong>
                    No hay sistemas disponibles
                </strong>

                <p>
                    Actualmente no tienes sistemas activos
                    disponibles para consultar.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>

<?= $this->endSection() ?>