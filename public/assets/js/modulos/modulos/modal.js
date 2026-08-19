/*==================================================*
*=             MODAL NUEVO MÓDULO                  =*
*==================================================*/

export function inicializarModalNuevoModulo() {

    const botonNuevoModulo =
        document.getElementById(
            "btn-nuevo-modulo",
        );

    const modal =
        document.getElementById(
            "modal-nuevo-modulo",
        );

    const formulario =
        document.getElementById(
            "form-nuevo-modulo",
        );

    if (
        !botonNuevoModulo ||
        !modal
    ) {
        return;
    }


    /*==================================================*
    *=                ABRIR MODAL                      =*
    *==================================================*/

    botonNuevoModulo.addEventListener(
        "click",
        () => {

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
            *=          ASOCIAR SISTEMA AL FORMULARIO          =*
            *==================================================*/

            if (formulario) {
                formulario.dataset.sistemaId =
                    idSistema;
            }


            /*==================================================*
            *=                 MOSTRAR MODAL                    =*
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