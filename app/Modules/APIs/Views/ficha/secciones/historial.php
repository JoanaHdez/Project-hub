<section
    class="ficha-seccion"
    data-ficha-contenido="historial"
>

    <div class="ficha-seccion__encabezado">

        <span class="ficha-seccion__numero">
            06
        </span>

        <div>
            <h3>
                Historial
            </h3>

            <p>
                Cambios importantes realizados en la API.
            </p>
        </div>

    </div>


    <!-- =========================================
         ESTADO PENDIENTE
    ========================================== -->

    <div
        id="ficha-historial-pendiente"
        class="ficha-pendiente"
    >

        <span class="ficha-pendiente__estado">
            Pendiente
        </span>

        <strong>
            Historial sin documentar
        </strong>

        <p>
            Todavía no se han registrado cambios
            importantes relacionados con esta API.
        </p>

        <button
            type="button"
            class="boton boton--secundario"
            id="btn-completar-historial"
        >
            Completar información
        </button>

    </div>


    <!-- =========================================
         HISTORIAL DOCUMENTADO
    ========================================== -->

    <div
        id="ficha-historial-contenido"
        hidden
    >

        <div class="ficha-seccion__acciones">

            <button
                type="button"
                class="boton boton--secundario"
                id="btn-editar-historial"
            >
                Editar
            </button>

            <button
                type="button"
                class="boton boton--peligro"
                id="btn-eliminar-historial"
            >
                Eliminar
            </button>

        </div>


        <div
            class="ficha-historial"
            id="ficha-historial-lista"
        >
        </div>

    </div>

</section>