<section
    class="ficha-seccion"
    data-ficha-contenido="observaciones">

    <div class="ficha-seccion__encabezado">

        <span class="ficha-seccion__numero">
            05
        </span>

        <div>
            <h3>
                Observaciones
            </h3>

            <p>
                Notas importantes relacionadas con el uso y mantenimiento de la API.
            </p>
        </div>

    </div>


    <!-- =========================================
         ESTADO PENDIENTE
    ========================================== -->

    <div
        id="ficha-observaciones-pendiente"
        class="ficha-pendiente">

        <span class="ficha-pendiente__estado">
            Pendiente
        </span>

        <strong>
            Observaciones sin documentar
        </strong>

        <p>
            Todavía no se han registrado notas u observaciones
            relacionadas con esta API.
        </p>

        <button
            type="button"
            class="boton boton--secundario"
            id="btn-completar-observaciones">
            Completar información
        </button>

    </div>


    <!-- =========================================
         OBSERVACIONES DOCUMENTADAS
    ========================================== -->

    <div
        id="ficha-observaciones-contenido"
        hidden>

        <div class="ficha-seccion__acciones">

            <button
                type="button"
                class="boton boton--secundario"
                id="btn-editar-observaciones">
                Editar
            </button>

            <button
                type="button"
                class="boton boton--peligro"
                id="btn-eliminar-observaciones">
                Eliminar
            </button>

        </div>


        <div
            class="ficha-alertas"
            id="ficha-observaciones-lista">
        </div>

    </div>

</section>