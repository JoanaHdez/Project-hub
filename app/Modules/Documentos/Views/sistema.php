<?= $this->extend('layouts/head') ?>

<?= $this->section('title') ?>
Documentos del sistema | Project Hub
<?= $this->endSection() ?>


<?= $this->section('styles') ?>

<link rel="stylesheet" href="<?= base_url(
        'assets/css/documentos/documentos.css'
    ) ?>">

<?= $this->endSection() ?>


<?= $this->section('content') ?>

<section class="documentos-page">


    <!--==============================================
    =                 ENCABEZADO                    =
    ===============================================-->

    <header class="documentos-header">

        <div class="documentos-header__contenido">

            <span class="documentos-header__eyebrow">
                <?= esc(
                    $sistema['proyecto_nombre']
                    ?? 'Sin proyecto'
                ) ?>
            </span>

            <h1 class="documentos-header__titulo">
                <?= esc(
                    $sistema['nombre']
                    ?? 'Sistema'
                ) ?>
            </h1>

            <p class="documentos-header__descripcion">
                Consulta y administra los documentos
                asociados a este sistema.
            </p>

        </div>


        <div class="documentos-header__acciones">

            <a href="<?= base_url(
            'documentos/sistema/'
            . (int) (
                $sistema['id_sistema']
                ?? 0
            )
            . '/nuevo'
        ) ?>" class="boton boton--primario">
                + Subir documento
            </a>

            <a href="<?= base_url(
            'documentos'
        ) ?>" class="boton boton--secundario">
                ← Volver
            </a>

        </div>

    </header>


    <!--==============================================
    =              LISTADO DE DOCUMENTOS            =
    ===============================================-->

    <section class="documentos-sistemas">


        <?php if (!empty($documentos)): ?>


        <!--==========================================
            =                  TABLA                     =
            ===========================================-->

        <div class="tabla-contenedor">

            <table class="tabla">

                <thead>

                    <tr>

                        <th>
                            Nombre
                        </th>

                        <th>
                            Tipo
                        </th>

                        <th>
                            Tamaño
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php foreach ($documentos as $documento): ?>

                    <?php

                            $idDocumento =
                                (int) (
                                    $documento['id_documento']
                                    ?? 0
                                );

                            $nombreDocumento =
                                $documento['nombre_original']
                                ?? 'Documento';

                            $rutaDocumento =
                                $documento['ruta']
                                ?? '';

                            ?>


                    <tr data-documento-fila data-documento-id="<?= $idDocumento ?>">


                        <!--==============================
                                =              NOMBRE            =
                                ===============================-->

                        <td>

                            <?= esc(
                                        $nombreDocumento
                                    ) ?>

                        </td>


                        <!--==============================
                                =               TIPO             =
                                ===============================-->

                        <td>

                            <?= esc(
                                        $documento['tipo']
                                        ?? '—'
                                    ) ?>

                        </td>


                        <!--==============================
                                =              TAMAÑO            =
                                ===============================-->

                        <td>

                            <?= number_format(
                                        (
                                            (int) (
                                                $documento['tamano']
                                                ?? 0
                                            )
                                        ) / 1024,
                                        1
                                    ) ?>

                            KB

                        </td>


                        <!--==============================
                                =               FECHA            =
                                ===============================-->

                        <td>

                            <?= esc(
                                        $documento['fecha_subida']
                                        ?? '—'
                                    ) ?>

                        </td>


                        <!--==============================
                                =              ACCIONES          =
                                ===============================-->

                        <td>

                            <div class="documento-tabla__acciones">


                                <!--======================
                                        =       DESCARGAR        =
                                        =======================-->

                                <a href="<?= base_url(
                                                $rutaDocumento
                                            ) ?>" class="
                                                boton
                                                boton--secundario
                                                boton--pequeno
                                            " download="<?= esc(
                                                $nombreDocumento,
                                                'attr'
                                            ) ?>">
                                    Descargar
                                </a>


                                <!--======================
                                        =        ELIMINAR        =
                                        =======================-->

                                <button type="button" class="
                                                boton
                                                boton--peligro
                                                boton--pequeno
                                            " data-eliminar-documento data-documento-id="<?= $idDocumento ?>"
                                    data-documento-nombre="<?= esc(
                                                $nombreDocumento,
                                                'attr'
                                            ) ?>">
                                    Eliminar
                                </button>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

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
                No hay documentos
            </h3>

            <p>
                Este sistema todavía no tiene
                archivos registrados.
            </p>

        </div>

        <?php endif; ?>


    </section>

</section>


<!--==============================================
=           CONFIRMAR ELIMINACIÓN               =
===============================================-->

<?= view(
    'App\Modules\Documentos\Views\modals\confirmar_eliminar_documento'
) ?>

<script type="module" src="<?= base_url(
        'assets/js/documentos/index.js'
    ) ?>"></script>

<?= $this->endSection() ?>