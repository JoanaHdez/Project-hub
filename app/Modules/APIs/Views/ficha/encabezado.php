<header class="modal-ficha__encabezado">

    <div class="modal-ficha__identidad">

        <div class="modal-ficha__titulo-contenedor">

            <span class="modal-ficha__etiqueta">
                Componente FT · Ficha técnica
            </span>

            <h2
                id="titulo-ficha-tecnica"
                class="modal-ficha__titulo"
            >
                —
            </h2>

            <p
                class="modal-ficha__subtitulo"
                id="ficha-encabezado-proyecto"
            >
                —
            </p>

        </div>

        <div
            class="ficha-badges"
            aria-label="Resumen técnico de la API"
        >

            <span
                class="ficha-badge ficha-badge--metodo"
                id="ficha-encabezado-metodo"
            >
                —
            </span>

            <span
                class="ficha-badge"
                id="ficha-encabezado-version"
            >
                Sin versión
            </span>

            <span
                class="ficha-badge ficha-badge--produccion"
                id="ficha-encabezado-estado"
            >
                <span
                    class="ficha-badge__indicador"
                    aria-hidden="true"
                ></span>

                <span id="ficha-encabezado-estado-texto">
                    —
                </span>
            </span>

            <!--
                Más adelante estos badges se
                generarán desde Dependencias.
            -->
            <div id="ficha-encabezado-tecnologias"></div>

        </div>

    </div>

    <button
        type="button"
        id="cerrar-ficha-tecnica"
        class="modal-ficha__cerrar"
        aria-label="Cerrar ficha técnica"
    >
        ×
    </button>

</header>