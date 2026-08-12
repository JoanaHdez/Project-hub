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

            <button type="button" class="boton boton--secundario boton--sm" id="btn-agregar-header">
                + Agregar header
            </button>

        </div>

        <div id="nueva-api-headers" class="form-api-documentacion__lista">
        </div>

        <div id="nueva-api-headers-vacio" class="estado-vacio">
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

            <button type="button" class="boton boton--secundario boton--sm" id="btn-agregar-parametro">
                + Agregar parámetro
            </button>

        </div>

        <div id="nueva-api-parametros" class="form-api-documentacion__lista">
        </div>

        <div id="nueva-api-parametros-vacio" class="estado-vacio">
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

                <label for="nueva-api-ejemplo-body">
                    Body de ejemplo
                </label>

                <textarea id="nueva-api-ejemplo-body" rows="8" placeholder='{
                    "correo": "usuario@ejemplo.com",
                    "nombre": "Usuario"
                    }'></textarea>

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

            <button type="button" class="boton boton--secundario boton--sm" id="btn-agregar-respuesta">
                + Agregar respuesta
            </button>

        </div>

        <div id="nueva-api-respuestas" class="form-api-documentacion__lista">
        </div>

        <div id="nueva-api-respuestas-vacio" class="estado-vacio">
            No se han agregado respuestas.
        </div>

    </section>

</fieldset>