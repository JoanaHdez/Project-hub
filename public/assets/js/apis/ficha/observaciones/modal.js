/*==================================================*
*=              MODAL OBSERVACIONES                 =*
*==================================================*/

export function abrirModalObservaciones(
    modal,
    {
        limpiar = false,
    } = {},
) {
    if (!modal) {
        return;
    }

    if (limpiar) {
        limpiarFormularioObservaciones();
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

export function cerrarModalObservaciones(
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

function limpiarFormularioObservaciones() {
    const formulario =
        document.getElementById(
            "form-observaciones-api",
        );

    const contenedor =
        document.getElementById(
            "observaciones-lista",
        );

    const estadoVacio =
        document.getElementById(
            "observaciones-vacio",
        );

    /*
     * Restablecer campos del formulario.
     */
    formulario?.reset();


    /*
     * Eliminar las observaciones
     * creadas dinámicamente.
     */
    if (contenedor) {
        contenedor
            .querySelectorAll(
                "[data-observacion]",
            )
            .forEach(
                (observacion) => {
                    observacion.remove();
                },
            );
    }


    /*
     * Mostrar nuevamente
     * el estado vacío.
     */
    if (estadoVacio) {
        estadoVacio.hidden = false;
    }
}