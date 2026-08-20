/*==================================================*
*=               DETALLE DEL MÓDULO                =*
*==================================================*/

export function inicializarDetalleModulo() {

    const modal =
        document.getElementById(
            "modal-nuevo-modulo",
        );

    const formulario =
        document.getElementById(
            "form-nuevo-modulo",
        );

    if (
        !modal ||
        !formulario
    ) {
        return;
    }


    /*==================================================*
    *=              CAMPOS DEL FORMULARIO              =*
    *==================================================*/

    const campoId =
        document.getElementById(
            "modulo-id",
        );

    const campoTipo =
        document.getElementById(
            "modulo-tipo",
        );

    const campoNombre =
        document.getElementById(
            "modulo-nombre",
        );

    const campoDescripcion =
        document.getElementById(
            "modulo-descripcion",
        );

    const campoUrl =
        document.getElementById(
            "modulo-url",
        );


    /*==================================================*
    *=              IMAGEN DEL MÓDULO                 =*
    *==================================================*/

    const editorImagen =
        modal.querySelector(
            "[data-modulo-imagen-editor]",
        );

    const previewImagen =
        modal.querySelector(
            "[data-modulo-imagen-preview]",
        );

    const imagenVacia =
        modal.querySelector(
            "[data-modulo-imagen-vacia]",
        );

    const botonSeleccionarImagen =
        modal.querySelector(
            "[data-modulo-imagen-seleccionar]",
        );


    /*==================================================*
    *=                  BOTONES                        =*
    *==================================================*/

    const botonEliminar =
        modal.querySelector(
            "[data-modulo-eliminar]",
        );

    const botonEditar =
        modal.querySelector(
            "[data-modulo-editar]",
        );

    const botonGuardar =
        modal.querySelector(
            "[data-modulo-guardar]",
        );

    const textoCerrar =
        modal.querySelector(
            "[data-modulo-texto-cerrar]",
        );


    /*==================================================*
    *=               ABRIR VER FICHA                   =*
    *==================================================*/

    document.addEventListener(
        "click",
        (evento) => {

            const botonDetalle =
                evento.target.closest(
                    ".modulo-card__detalle",
                );

            if (!botonDetalle) {
                return;
            }

            const tarjeta =
                botonDetalle.closest(
                    ".modulo-card",
                );

            if (!tarjeta) {
                return;
            }


            /*==================================================*
            *=              DATOS DEL MÓDULO                   =*
            *==================================================*/

            const idModulo =
                tarjeta.dataset.moduloId ?? "";

            const tipo =
                tarjeta.dataset.moduloTipo ?? "";

            const nombre =
                tarjeta.dataset.moduloNombre ?? "";

            const descripcion =
                tarjeta.dataset.moduloDescripcion ?? "";

            const url =
                tarjeta.dataset.moduloUrl ?? "";

            const imagen =
                tarjeta.dataset.moduloImagen ?? "";


            /*==================================================*
            *=             PREPARAR FORMULARIO                 =*
            *==================================================*/

            formulario.dataset.modo =
                "detalle";

            const idSistema =
                document.body.dataset
                    .sistemaSeleccionadoId ?? "";

            formulario.dataset.sistemaId =
                idSistema;


            if (campoId) {
                campoId.value =
                    idModulo;
            }

            if (campoTipo) {
                campoTipo.value =
                    tipo;

                campoTipo.disabled =
                    true;
            }

            if (campoNombre) {
                campoNombre.value =
                    nombre;

                campoNombre.disabled =
                    true;
            }

            if (campoDescripcion) {
                campoDescripcion.value =
                    descripcion;

                campoDescripcion.disabled =
                    true;
            }

            if (campoUrl) {
                campoUrl.value =
                    url;

                campoUrl.disabled =
                    true;
            }


            /*==================================================*
            *=              MOSTRAR IMAGEN                      =*
            *==================================================*/

            if (editorImagen) {
                editorImagen.hidden =
                    false;
            }

            if (imagen) {

                if (previewImagen) {
                    previewImagen.src =
                        imagen;

                    previewImagen.hidden =
                        false;
                }

                if (imagenVacia) {
                    imagenVacia.hidden =
                        true;
                }

            } else {

                if (previewImagen) {
                    previewImagen.removeAttribute(
                        "src",
                    );

                    previewImagen.hidden =
                        true;
                }

                if (imagenVacia) {
                    imagenVacia.hidden =
                        false;
                }
            }


            /*==================================================*
            *=          OCULTAR LÁPIZ EN DETALLE               =*
            *==================================================*/

            if (botonSeleccionarImagen) {
                botonSeleccionarImagen.hidden =
                    true;
            }


            /*==================================================*
            *=                  BOTONES                         =*
            *==================================================*/

            if (botonEliminar) {
                botonEliminar.hidden =
                    false;
            }

            if (botonEditar) {
                botonEditar.hidden =
                    false;
            }

            if (botonGuardar) {
                botonGuardar.hidden =
                    true;
            }

            if (textoCerrar) {
                textoCerrar.textContent =
                    "Cerrar";
            }


            /*==================================================*
            *=                TÍTULO MODAL                     =*
            *==================================================*/

            const tituloModal =
                document.getElementById(
                    "modal-nuevo-modulo-titulo",
                );

            if (tituloModal) {
                tituloModal.textContent =
                    "Detalle del módulo";
            }


            /*==================================================*
            *=                  ABRIR MODAL                     =*
            *==================================================*/

            modal.classList.add(
                "modal--visible",
            );

            modal.setAttribute(
                "aria-hidden",
                "false",
            );

            document.body.style.overflow =
                "hidden";
        },
    );


    /*==================================================*
    *=                  EDITAR MÓDULO                   =*
    *==================================================*/

    botonEditar?.addEventListener(
        "click",
        () => {

            if (
                formulario.dataset.modo !==
                "detalle"
            ) {
                return;
            }


            /*==================================================*
            *=                MODO EDICIÓN                     =*
            *==================================================*/

            formulario.dataset.modo =
                "editar";


            /*==================================================*
            *=              HABILITAR CAMPOS                   =*
            *==================================================*/

            if (campoTipo) {
                campoTipo.disabled =
                    false;
            }

            if (campoNombre) {
                campoNombre.disabled =
                    false;
            }

            if (campoDescripcion) {
                campoDescripcion.disabled =
                    false;
            }

            if (campoUrl) {
                campoUrl.disabled =
                    false;
            }


            /*==================================================*
            *=              MOSTRAR LÁPIZ                      =*
            *==================================================*/

            if (botonSeleccionarImagen) {
                botonSeleccionarImagen.hidden =
                    false;
            }


            /*==================================================*
            *=                  TÍTULO                         =*
            *==================================================*/

            const tituloModal =
                document.getElementById(
                    "modal-nuevo-modulo-titulo",
                );

            if (tituloModal) {
                tituloModal.textContent =
                    "Editar módulo";
            }


            /*==================================================*
            *=                  BOTONES                        =*
            *==================================================*/

            if (botonEliminar) {
                botonEliminar.hidden =
                    true;
            }

            if (botonEditar) {
                botonEditar.hidden =
                    true;
            }

            if (botonGuardar) {
                botonGuardar.hidden =
                    false;
            }

            const textoGuardar =
                modal.querySelector(
                    "[data-modulo-texto-guardar]",
                );

            if (textoGuardar) {
                textoGuardar.textContent =
                    "Guardar cambios";
            }

            if (textoCerrar) {
                textoCerrar.textContent =
                    "Cancelar";
            }


            /*==================================================*
            *=                  FOCO                            =*
            *==================================================*/

            campoNombre?.focus();
        },
    );
}