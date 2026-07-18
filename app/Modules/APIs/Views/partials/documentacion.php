<section class="documentacion-api">

    <div class="documentacion-api__panel">

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

                <div class="documentacion-seccion__encabezado">
                    <h3>Headers</h3>
                    <p>
                        Encabezados requeridos para consumir la API.
                    </p>
                </div>

                <div class="tabla-contenedor" id="api-headers-contenedor" hidden>
                    <table class="tabla">

                        <thead>
                            <tr>
                                <th>Header</th>
                                <th>Valor</th>
                                <th>Obligatorio</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>

                        <tbody id="api-headers"></tbody>

                    </table>
                </div>

                <div class="estado-vacio" id="api-headers-vacio">
                    Selecciona una API para consultar sus headers.
                </div>

            </section>

            <section class="documentacion-seccion">

                <div class="documentacion-seccion__encabezado">
                    <h3>Parámetros</h3>
                    <p>
                        Parámetros requeridos para consumir la API.
                    </p>
                </div>

                <div class="tabla-contenedor" id="api-parametros-contenedor" hidden>

                    <table class="tabla">

                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Obligatorio</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>

                        <tbody id="api-parametros"></tbody>

                    </table>

                </div>

                <div class="estado-vacio" id="api-parametros-vacio">
                    Selecciona una API para consultar sus parámetros.
                </div>

            </section>

            <section class="documentacion-seccion">

                <div class="documentacion-seccion__encabezado">
                    <h3>Ejemplo de consumo</h3>
                    <p>
                        Ejemplo de petición para consumir la API.
                    </p>
                </div>

                <div id="api-ejemplo-contenedor" hidden>

    <div class="codigo-api__acciones">

        <button
            type="button"
            class="boton boton--secundario boton--sm"
            id="copiar-ejemplo">

            Copiar

        </button>

    </div>

    <div class="codigo-api__encabezado">

    <span
        id="api-ejemplo-metodo"
        class="badge-metodo">

        POST

    </span>

</div>

<pre class="codigo-api">
    <code id="api-ejemplo"></code>
</pre>

</div>

                <div id="api-ejemplo-vacio" class="estado-vacio">

                    Esta API no tiene un ejemplo documentado.

                </div>

            </section>

            <section class="documentacion-seccion">

                <div class="documentacion-seccion__encabezado">
                    <h3>Respuestas esperadas</h3>
                    <p>
                        Posibles respuestas que devuelve la API.
                    </p>
                </div>

                <div id="api-respuestas-contenedor" hidden>

                    <div id="api-respuestas"></div>

                </div>

                <div
                    id="api-respuestas-vacio"
                    class="estado-vacio">

                    Esta API no tiene respuestas documentadas.

                </div>

            </section>

        </div>
    </div>

</section>