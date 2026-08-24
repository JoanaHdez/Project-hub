/*==================================================*
*=             MODAL NUEVO MÓDULO                  =*
*==================================================*/

export function inicializarModalNuevoModulo() {

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
    *=                ABRIR NUEVO                     =*
    *==================================================*/

    document.addEventListener(
        "click",
        (evento) => {

            const botonNuevoModulo =
                evento.target.closest(
                    "#btn-nuevo-modulo",
                );

            if (!botonNuevoModulo) {
                return;
            }


            /*==================================================*
            *=              SISTEMA SELECCIONADO              =*
            *==================================================*/

            const idSistema =
                document.body.dataset
                    .sistemaSeleccionadoId ?? "";

            if (!idSistema) {

                console.error(
                    "No hay un sistema seleccionado.",
                );

                return;
            }


            /*==================================================*
            *=              MODO NUEVO                         =*
            *==================================================*/

            formulario.dataset.modo =
                "nuevo";

            formulario.dataset.sistemaId =
                idSistema;

            formulario.reset();


            /*==================================================*
            *=              LIMPIAR IMAGEN                     =*
            *==================================================*/

            const editorImagen =
                formulario.querySelector(
                    "[data-modulo-imagen-editor]",
                );

            const previewImagen =
                formulario.querySelector(
                    "[data-modulo-imagen-preview]",
                );

            const estadoVacioImagen =
                formulario.querySelector(
                    "[data-modulo-imagen-vacia]",
                );

            const botonImagen =
                formulario.querySelector(
                    "[data-modulo-imagen-seleccionar]",
                );

            const inputImagen =
                formulario.querySelector(
                    "[data-modulo-imagen-input]",
                );


            /*
             * En modo nuevo no se solicita imagen.
             *
             * La imagen solamente se administra
             * posteriormente desde la edición
             * del módulo.
             */

            if (editorImagen) {
                editorImagen.hidden =
                    true;
            }

            if (previewImagen) {

                previewImagen.removeAttribute(
                    "src",
                );

                previewImagen.hidden =
                    true;
            }

            if (estadoVacioImagen) {
                estadoVacioImagen.hidden =
                    false;
            }

            if (botonImagen) {
                botonImagen.hidden =
                    true;
            }

            if (inputImagen) {
                inputImagen.value =
                    "";
            }


            /*==================================================*
            *=                 CAMPOS                          =*
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


            if (campoId) {
                campoId.value =
                    "";
            }

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
            *=                  TÍTULO                         =*
            *==================================================*/

            const tituloModal =
                document.getElementById(
                    "modal-nuevo-modulo-titulo",
                );

            if (tituloModal) {

                tituloModal.textContent =
                    "Nuevo módulo";
            }


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

            const textoGuardar =
                modal.querySelector(
                    "[data-modulo-texto-guardar]",
                );

            const textoCerrar =
                modal.querySelector(
                    "[data-modulo-texto-cerrar]",
                );


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

            if (textoGuardar) {

                textoGuardar.textContent =
                    "Guardar módulo";
            }

            if (textoCerrar) {

                textoCerrar.textContent =
                    "Cancelar";
            }


            /*==================================================*
            *=                MOSTRAR MODAL                    =*
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
}