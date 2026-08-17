/*==================================================*
*=              MODAL DEPENDENCIAS                  =*
*==================================================*/

export function abrirModalDependencias(
    modal,
    {
        limpiar = false,
    } = {},
) {
    if (!modal) {
        return;
    }

    if (limpiar) {
        limpiarFormularioDependencias();
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

export function cerrarModalDependencias(
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

function limpiarFormularioDependencias() {
    const formulario =
        document.getElementById(
            "form-dependencias-api",
        );

    const lista =
        document.getElementById(
            "dependencias-lista",
        );

    const estadoVacio =
        document.getElementById(
            "dependencias-vacio",
        );

    formulario?.reset();

    if (lista) {
        lista.innerHTML = "";
    }

    if (estadoVacio) {
        estadoVacio.hidden = false;
    }
}