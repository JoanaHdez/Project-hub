/*==================================================*
*=              MODAL ARQUITECTURA                  =*
*==================================================*/

export function abrirModalArquitectura(
    modal,
    {
        limpiar = false,
    } = {},
) {
    if (!modal) {
        return;
    }

    if (limpiar) {
        limpiarFormularioArquitectura();
    }

    modal.classList.add(
        "modal--visible",
    );

    modal.setAttribute(
        "aria-hidden",
        "false",
    );

    document.body.style.overflow =
        "hidden";
}


/*==================================================*
*=              CERRAR MODAL                        =*
*==================================================*/

export function cerrarModalArquitectura(
    modal,
) {
    if (!modal) {
        return;
    }

    if (
        document.activeElement
        instanceof HTMLElement
    ) {
        document.activeElement.blur();
    }

    modal.classList.remove(
        "modal--visible",
    );

    modal.setAttribute(
        "aria-hidden",
        "true",
    );

    document.body.style.overflow =
        "";
}


/*==================================================*
*=          LIMPIAR FORMULARIO                      =*
*==================================================*/

function limpiarFormularioArquitectura() {
    const formulario =
        document.getElementById(
            "form-arquitectura-api",
        );

    const contenedor =
        document.getElementById(
            "arquitectura-componentes",
        );

    const estadoVacio =
        document.getElementById(
            "arquitectura-componentes-vacio",
        );

    /*
     * Restablece inputs, selects, textarea, etc.
     */
    formulario?.reset();


    /*
     * Los componentes fueron creados dinámicamente,
     * así que reset() no los elimina.
     */
    if (contenedor) {
        contenedor
            .querySelectorAll(
                "[data-componente-arquitectura]",
            )
            .forEach(
                (componente) => {
                    componente.remove();
                },
            );
    }


    /*
     * Al no existir componentes,
     * mostramos nuevamente el estado vacío.
     */
    if (estadoVacio) {
        estadoVacio.hidden = false;
    }
}