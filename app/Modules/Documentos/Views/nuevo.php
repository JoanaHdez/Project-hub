<?= $this->extend('layouts/head') ?>

<?= $this->section('title') ?>
Subir documento | Project Hub
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
                Nuevo archivo
            </span>

            <h1 class="documentos-header__titulo">
                Subir documento
            </h1>

            <p class="documentos-header__descripcion">
                Selecciona el sistema al que pertenece
                el archivo y registra su información.
            </p>

        </div>

        <div class="documentos-header__acciones">

            <a href="<?= isset($idSistemaSeleccionado)
                            ? base_url(
                                'documentos/sistema/'
                                    . (int) $idSistemaSeleccionado
                            )
                            : base_url('documentos')
                        ?>" class="boton boton--secundario">
                ← Volver
            </a>

        </div>

    </header>


    <!--==============================================
    =                 FORMULARIO                    =
    ===============================================-->

    <section class="documento-formulario">

        <form id="form-documento" enctype="multipart/form-data" autocomplete="off">

            <div class="form-grid">


                <!--==================================
                =              SISTEMA              =
                ===================================-->

                <div class="form-grupo form-grupo--completo">

                    <label for="documento-sistema">
                        Sistema
                    </label>

                    <select id="documento-sistema" name="id_sistema" required <?= isset($idSistemaSeleccionado)
                                                                                    ? 'disabled'
                                                                                    : ''
                                                                                ?>>

                        <option value="">
                            Selecciona un sistema
                        </option>

                        <?php foreach ($sistemas as $sistema): ?>

                            <?php

                            $idSistema =
                                (int) (
                                    $sistema['id_sistema']
                                    ?? 0
                                );

                            $seleccionado =
                                isset($idSistemaSeleccionado)
                                &&
                                $idSistema ===
                                (int) $idSistemaSeleccionado;

                            ?>

                            <option value="<?= $idSistema ?>" <?= $seleccionado
                                                                    ? 'selected'
                                                                    : ''
                                                                ?>>
                                <?= esc(
                                    $sistema['nombre']
                                        ?? 'Sistema'
                                ) ?>

                                —

                                <?= esc(
                                    $sistema['proyecto_nombre']
                                        ?? 'Sin proyecto'
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>


                    <?php if (isset($idSistemaSeleccionado)): ?>

                        <input type="hidden" name="id_sistema" value="<?= (int) $idSistemaSeleccionado ?>">

                    <?php endif; ?>

                </div>


                <!--==================================
                =              ARCHIVO              =
                ===================================-->

                <div class="form-grupo form-grupo--completo">

                    <label for="documento-archivo">
                        Archivo
                    </label>

                    <input type="file" id="documento-archivo" name="archivo" required>

                </div>


                <!--==================================
                =            DESCRIPCIÓN            =
                ===================================-->

                <div class="form-grupo form-grupo--completo">

                    <label for="documento-descripcion">
                        Descripción
                    </label>

                    <textarea id="documento-descripcion" name="descripcion" rows="4"
                        placeholder="Describe brevemente el contenido o propósito del archivo."></textarea>

                </div>

            </div>


            <!--======================================
            =                ACCIONES               =
            =======================================-->

            <div class="documento-formulario__acciones">

                <a
                    href="<?= isset($idSistemaSeleccionado)
                                ? base_url(
                                    'documentos/sistema/'
                                        . (int) $idSistemaSeleccionado
                                )
                                : base_url('documentos')
                            ?>"
                    class="boton boton--secundario">
                    Cancelar
                </a>

                <button type="submit" class="boton boton--primario">
                    Subir documento
                </button>

            </div>

        </form>

    </section>

</section>


<script type="module" src="<?= base_url(
                                'assets/js/documentos/index.js'
                            ) ?>"></script>


<?= $this->endSection() ?>