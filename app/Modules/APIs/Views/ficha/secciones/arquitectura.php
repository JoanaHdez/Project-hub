<section class="ficha-seccion" data-ficha-contenido="arquitectura">

    <div class="ficha-seccion__encabezado">

        <span class="ficha-seccion__numero">
            03
        </span>

        <div>
            <h3>
                Arquitectura
            </h3>

            <p>
                Componentes internos relacionados con la API.
            </p>
        </div>

    </div>


    <!-- =========================================
         ESTADO PENDIENTE
    ========================================== -->

    <div id="ficha-arquitectura-pendiente" class="ficha-pendiente">

        <span class="ficha-pendiente__estado">
            Pendiente
        </span>

        <strong>
            Arquitectura sin documentar
        </strong>

        <p>
            Todavía no se ha registrado la estructura interna
            relacionada con esta API.
        </p>

        <button type="button" class="boton boton--secundario" id="btn-completar-arquitectura">
            Completar información
        </button>

    </div>


    <!-- =========================================
         ARQUITECTURA DOCUMENTADA
    ========================================== -->

    <div id="ficha-arquitectura-contenido" hidden>

        <div class="ficha-seccion__acciones">

            <button type="button" class="boton boton--secundario" id="btn-editar-arquitectura">
                Editar
            </button>

        </div>

        <div class="ficha-arbol">

            <div class="ficha-arbol__raiz">

                <span class="ficha-arbol__icono" aria-hidden="true">
                    📦
                </span>

                <div>
                    <small>
                        Módulo
                    </small>

                    <strong id="ficha-arquitectura-modulo">
                        —
                    </strong>
                </div>

            </div>

            <div class="ficha-arbol__contenido" id="ficha-arquitectura-grupos">
            </div>

        </div>

    </div>

</section>