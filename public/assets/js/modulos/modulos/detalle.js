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


            /*==================================================*
            *=             PREPARAR FORMULARIO                 =*
            *==================================================*/

            formulario.dataset.modo =
                "detalle";

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
}