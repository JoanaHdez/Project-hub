import {
    inicializarItemsHistorial,
    inicializarEliminacionHistorial,
} from "./historial/componentes.js";

import {
    inicializarFormularioHistorial,
} from "./historial/formulario.js";

import {
    abrirModalHistorial,
    cerrarModalHistorial,
} from "./historial/modal.js";

import {
    renderHistorial,
} from "./historial/render.js";

/*==================================================*
*=              HISTORIAL DE API                    =*
*==================================================*/

export function inicializarHistorialFicha() {
    const botonCompletar =
        document.getElementById(
            "btn-completar-historial",
        );

    const modal =
        document.getElementById(
            "modal-historial-api",
        );

    const botonAgregar =
        document.getElementById(
            "btn-agregar-historial",
        );

    const contenedor =
        document.getElementById(
            "historial-lista",
        );

    const estadoVacio =
        document.getElementById(
            "historial-vacio",
        );

    const formulario =
        document.getElementById(
            "form-historial-api",
        );

    if (
        !botonCompletar ||
        !modal ||
        !botonAgregar ||
        !contenedor ||
        !estadoVacio || 
        !formulario
    ) {
        return;
    }


    /*==================================================*
    *=              COMPLETAR HISTORIAL                 =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            abrirModalHistorial(
                modal,
                {
                    limpiar: true,
                },
            );
        },
    );


    /*==================================================*
    *=              COMPONENTES                         =*
    *==================================================*/

    inicializarItemsHistorial({
        botonAgregar,
        contenedor,
        estadoVacio,
    });

    inicializarEliminacionHistorial({
        contenedor,
        estadoVacio,
    });

    /*==================================================*
*=              FORMULARIO                          =*
*==================================================*/

inicializarFormularioHistorial({
    formulario,

    alGuardar: (historial) => {
        const apiSeleccionada =
            document.querySelector(
                ".selector--activo",
            );

        if (apiSeleccionada) {
            apiSeleccionada.dataset.apiHistorial =
                JSON.stringify(
                    historial,
                );
        }

        renderHistorial(
            historial,
        );

        cerrarModalHistorial(
            modal,
        );
    },
});
}