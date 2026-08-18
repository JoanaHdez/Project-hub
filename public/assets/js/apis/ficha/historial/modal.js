/*==================================================*
*=              MODAL HISTORIAL                     =*
*==================================================*/

export function abrirModalHistorial(
    modal,
    {
        limpiar = false,
    } = {},
) {
    if (!modal) {
        return;
    }

    if (limpiar) {
        limpiarFormularioHistorial();
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

export function cerrarModalHistorial(
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

function limpiarFormularioHistorial() {
    const formulario =
        document.getElementById(
            "form-historial-api",
        );

    const contenedor =
        document.getElementById(
            "historial-lista",
        );

    const estadoVacio =
        document.getElementById(
            "historial-vacio",
        );

    /*
     * Restablecer campos del formulario.
     */
    formulario?.reset();


    /*
     * Los registros del historial se crean
     * dinámicamente, por lo que reset()
     * no los elimina.
     */
    if (contenedor) {
        contenedor
            .querySelectorAll(
                "[data-historial]",
            )
            .forEach(
                (item) => {
                    item.remove();
                },
            );
    }


    /*
     * Mostrar nuevamente el estado vacío.
     */
    if (estadoVacio) {
        estadoVacio.hidden =
            false;
    }
}