<section
    class="ficha-seccion"
    data-ficha-contenido="dependencias"
>

    <div class="ficha-seccion__encabezado">

        <span class="ficha-seccion__numero">
            04
        </span>

        <div>
            <h3>
                Dependencias
            </h3>

            <p>
                Servicios y recursos necesarios para funcionar.
            </p>
        </div>

    </div>


    <!-- =========================================
         ESTADO PENDIENTE
    ========================================== -->

    <div
        id="ficha-dependencias-pendiente"
        class="ficha-pendiente"
    >

        <span class="ficha-pendiente__estado">
            Pendiente
        </span>

        <strong>
            Dependencias sin documentar
        </strong>

        <p>
            Todavía no se han registrado los servicios
            o recursos necesarios para esta API.
        </p>

        <button
            type="button"
            class="boton boton--secundario"
            id="btn-completar-dependencias"
        >
            Completar información
        </button>

    </div>


    <!-- =========================================
         DEPENDENCIAS DOCUMENTADAS
    ========================================== -->

    <div
        id="ficha-dependencias-contenido"
        hidden
    >

        <div class="ficha-seccion__acciones">

            <button
                type="button"
                class="boton boton--secundario"
                id="btn-editar-dependencias"
            >
                Editar
            </button>

            <button
                type="button"
                class="boton boton--peligro"
                id="btn-eliminar-dependencias"
            >
                Eliminar
            </button>

        </div>


        <div
            class="ficha-dependencias"
            id="ficha-dependencias-lista"
        >
        </div>

    </div>

</section>