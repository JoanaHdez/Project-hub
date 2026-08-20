<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<?= $this->section('title') ?>
Documentos | Project Hub
<?= $this->endSection() ?>

<section class="documentos-page">


    <!--==============================================
    =                 ENCABEZADO                    =
    ===============================================-->

    <header class="documentos-header">

        <div class="documentos-header__contenido">

            <span class="documentos-header__eyebrow">
                Repositorio
            </span>

            <h1 class="documentos-header__titulo">
                Documentos
            </h1>

            <p class="documentos-header__descripcion">
                Administra los archivos asociados a los
                sistemas registrados en Project Hub.
            </p>

        </div>


        <div class="documentos-header__acciones">

            <a
                href="<?= base_url(
                    'documentos/nuevo'
                ) ?>"
                class="boton boton--primario"
            >
                + Subir documento
            </a>

        </div>

    </header>


    <!--==============================================
    =              SISTEMAS CON ARCHIVOS            =
    ===============================================-->

    <section class="documentos-sistemas">

        <div class="documentos-sistemas__encabezado">

            <div>

                <h2>
                    Sistemas con documentos
                </h2>

                <p>
                    Selecciona un sistema para consultar
                    los archivos almacenados.
                </p>

            </div>

        </div>


        <?php if (!empty($sistemas)): ?>


            <!--==========================================
            =                 TARJETAS                   =
            ===========================================-->

            <div class="documentos-grid">

                <?php foreach ($sistemas as $sistema): ?>

                    <?php

                    $idSistema =
                        (int) (
                            $sistema['id_sistema']
                            ?? 0
                        );

                    $nombreSistema =
                        $sistema['nombre']
                        ?? 'Sistema sin nombre';

                    $nombreProyecto =
                        $sistema['proyecto_nombre']
                        ?? 'Sin proyecto';

                    $totalDocumentos =
                        (int) (
                            $sistema['total_documentos']
                            ?? 0
                        );

                    ?>


                    <a
                        href="<?= base_url(
                            'documentos/sistema/'
                            . $idSistema
                        ) ?>"
                        class="documento-sistema-card"
                    >

                        <div class="documento-sistema-card__icono">

                            <span aria-hidden="true">
                                📁
                            </span>

                        </div>


                        <div class="documento-sistema-card__contenido">

                            <span class="documento-sistema-card__proyecto">
                                <?= esc($nombreProyecto) ?>
                            </span>


                            <h3>
                                <?= esc($nombreSistema) ?>
                            </h3>


                            <div class="documento-sistema-card__meta">

                                <span>
                                    <?= $totalDocumentos ?>

                                    <?= $totalDocumentos === 1
                                        ? 'documento'
                                        : 'documentos'
                                    ?>
                                </span>

                            </div>

                        </div>


                        <div
                            class="documento-sistema-card__flecha"
                            aria-hidden="true"
                        >
                            →
                        </div>

                    </a>

                <?php endforeach; ?>

            </div>


        <?php else: ?>


            <!--==========================================
            =                ESTADO VACÍO                =
            ===========================================-->

            <div class="documentos-vacio">

                <div class="documentos-vacio__icono">
                    📂
                </div>

                <h3>
                    Aún no hay documentos
                </h3>

                <p>
                    Cuando agregues el primer archivo a un
                    sistema, aparecerá aquí automáticamente.
                </p>

                <a
                    href="<?= base_url(
                        'documentos/nuevo'
                    ) ?>"
                    class="boton boton--primario"
                >
                    Subir primer documento
                </a>

            </div>

        <?php endif; ?>

    </section>

</section>

<?= $this->endSection() ?>