<section class="documentacion-api">

    <div class="documentacion-api__encabezado">

        <div>
            <span class="documentacion-api__metodo" id="api-metodo">
                —
            </span>

            <h2 class="documentacion-api__titulo" id="api-nombre">
                Selecciona una API
            </h2>

            <p class="documentacion-api__descripcion" id="api-descripcion">
                Selecciona una API del catálogo para consultar su documentación técnica.
            </p>
        </div>

        <button type="button" class="boton boton--secundario" id="btn-ficha-api" data-modal-abrir="modal-ficha-api"
            disabled>
            Ficha de ubicación
        </button>

    </div>

    <div class="documentacion-api__contenido">

        <section class="documentacion-seccion">

            <h3 class="documentacion-seccion__titulo">
                Información general
            </h3>

            <div class="documentacion-datos">

                <?= view('components/ui/dato', [
                    'etiqueta' => 'Proyecto',
                    'id' => 'api-proyecto',
                ]) ?>

                <?= view('components/ui/dato', [
                    'etiqueta' => 'Estado',
                    'id' => 'api-estado',
                ]) ?>

                <?= view('components/ui/dato', [
                    'etiqueta' => 'Autenticación',
                    'id' => 'api-autenticacion',
                ]) ?>

            </div>

        </section>

        <section class="documentacion-seccion">

            <h3 class="documentacion-seccion__titulo">
                Endpoint
            </h3>

            <div class="endpoint-api">

                <span class="endpoint-api__metodo" id="api-endpoint-metodo">
                    —
                </span>

                <code class="endpoint-api__url" id="api-url">
                    —
                </code>

            </div>

            <p class="endpoint-api__ruta" id="api-endpoint">
                —
            </p>

        </section>

        <section class="documentacion-seccion">

            <h3 class="documentacion-seccion__titulo">
                Parámetros
            </h3>

            <div class="estado-vacio">
                Los parámetros se agregarán en el siguiente paso.
            </div>

        </section>

        <section class="documentacion-seccion">

            <h3 class="documentacion-seccion__titulo">
                Ejemplo de consumo
            </h3>

            <div class="estado-vacio">
                El ejemplo de petición se agregará más adelante.
            </div>

        </section>

        <section class="documentacion-seccion">

            <h3 class="documentacion-seccion__titulo">
                Respuestas esperadas
            </h3>

            <div class="estado-vacio">
                Las respuestas de la API se agregarán más adelante.
            </div>

        </section>

    </div>

</section>