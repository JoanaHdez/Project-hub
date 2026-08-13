<?php

$modo = $modo ?? 'crear';

$esDetalle = $modo === 'detalle';

$prefijoId = match ($modo) {
    'editar'  => 'editar-api',
    'detalle' => 'detalle-api',
    default   => 'nueva-api',
};

$idBtnHeader =
    $prefijoId . '-btn-agregar-header';

$idHeaders =
    $prefijoId . '-headers';

$idHeadersVacio =
    $prefijoId . '-headers-vacio';

$idBtnParametro =
    $prefijoId . '-btn-agregar-parametro';

$idParametros =
    $prefijoId . '-parametros';

$idParametrosVacio =
    $prefijoId . '-parametros-vacio';

$idEjemploBody =
    $prefijoId . '-ejemplo-body';

$idBtnRespuesta =
    $prefijoId . '-btn-agregar-respuesta';

$idRespuestas =
    $prefijoId . '-respuestas';

$idRespuestasVacio =
    $prefijoId . '-respuestas-vacio';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Documentación
    </legend>

    <p class="form-bloque__descripcion">
        Registra la información necesaria para documentar
        y consumir la API.
    </p>


    <!-- =========================================
         HEADERS
    ========================================== -->

    <section class="form-api-documentacion">

        <div class="form-api-documentacion__encabezado">

            <div>
                <h4>Headers</h4>

                <p>
                    Encabezados requeridos para consumir la API.
                </p>
            </div>

            <?php if (!$esDetalle): ?>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="<?= esc($idBtnHeader, 'attr') ?>"
                >
                    + Agregar header
                </button>

            <?php endif; ?>

        </div>

        <div
            id="<?= esc($idHeaders, 'attr') ?>"
            class="form-api-documentacion__lista"
        >
        </div>

        <div
            id="<?= esc($idHeadersVacio, 'attr') ?>"
            class="estado-vacio"
        >
            No se han agregado headers.
        </div>

    </section>


    <!-- =========================================
         PARÁMETROS
    ========================================== -->

    <section class="form-api-documentacion">

        <div class="form-api-documentacion__encabezado">

            <div>
                <h4>Parámetros</h4>

                <p>
                    Parámetros que puede recibir la API.
                </p>
            </div>

            <?php if (!$esDetalle): ?>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="<?= esc($idBtnParametro, 'attr') ?>"
                >
                    + Agregar parámetro
                </button>

            <?php endif; ?>

        </div>

        <div
            id="<?= esc($idParametros, 'attr') ?>"
            class="form-api-documentacion__lista"
        >
        </div>

        <div
            id="<?= esc($idParametrosVacio, 'attr') ?>"
            class="estado-vacio"
        >
            No se han agregado parámetros.
        </div>

    </section>


    <!-- =========================================
         EJEMPLO DE CONSUMO
    ========================================== -->

    <section class="form-api-documentacion">

        <div class="form-api-documentacion__encabezado">

            <div>
                <h4>Ejemplo de consumo</h4>

                <p>
                    Registra un ejemplo del contenido enviado
                    al consumir la API.
                </p>
            </div>

        </div>

        <div class="form-grid">

            <div class="form-grupo form-grupo--completo">

                <label
                    for="<?= esc($idEjemploBody, 'attr') ?>"
                >
                    Body de ejemplo
                </label>

                <textarea
                    id="<?= esc($idEjemploBody, 'attr') ?>"
                    rows="8"
                    <?= $esDetalle ? 'readonly' : '' ?>
                    placeholder='{
  "correo": "usuario@ejemplo.com",
  "nombre": "Usuario"
}'
                ></textarea>

                <small>
                    Ingresa un objeto JSON válido.
                </small>

            </div>

        </div>

    </section>


    <!-- =========================================
         RESPUESTAS ESPERADAS
    ========================================== -->

    <section class="form-api-documentacion">

        <div class="form-api-documentacion__encabezado">

            <div>
                <h4>Respuestas esperadas</h4>

                <p>
                    Registra las posibles respuestas que puede
                    devolver la API.
                </p>
            </div>

            <?php if (!$esDetalle): ?>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="<?= esc($idBtnRespuesta, 'attr') ?>"
                >
                    + Agregar respuesta
                </button>

            <?php endif; ?>

        </div>

        <div
            id="<?= esc($idRespuestas, 'attr') ?>"
            class="form-api-documentacion__lista"
        >
        </div>

        <div
            id="<?= esc($idRespuestasVacio, 'attr') ?>"
            class="estado-vacio"
        >
            No se han agregado respuestas.
        </div>

    </section>

</fieldset>