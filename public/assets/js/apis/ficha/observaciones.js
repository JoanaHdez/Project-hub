import {
    inicializarItemsObservaciones,
    inicializarEliminacionObservaciones,
} from "./observaciones/componentes.js";

import {
    inicializarFormularioObservaciones,
} from "./observaciones/formulario.js";

import {
    abrirModalObservaciones,
    cerrarModalObservaciones,
} from "./observaciones/modal.js";

import {
    renderObservaciones,
} from "./observaciones/render.js";


/*==================================================*
*=              OBSERVACIONES DE API                =*
*==================================================*/

export function inicializarObservacionesFicha() {
    const botonCompletar =
        document.getElementById(
            "btn-completar-observaciones",
        );

    const modal =
        document.getElementById(
            "modal-observaciones-api",
        );

    const botonAgregar =
        document.getElementById(
            "btn-agregar-observacion",
        );

    const contenedor =
        document.getElementById(
            "observaciones-lista",
        );

    const estadoVacio =
        document.getElementById(
            "observaciones-vacio",
        );

    const formulario =
        document.getElementById(
            "form-observaciones-api",
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
    *=              COMPLETAR OBSERVACIONES             =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            abrirModalObservaciones(
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

    inicializarItemsObservaciones({
        botonAgregar,
        contenedor,
        estadoVacio,
    });

    inicializarEliminacionObservaciones({
        contenedor,
        estadoVacio,
    });


    /*==================================================*
    *=              FORMULARIO                          =*
    *==================================================*/

    inicializarFormularioObservaciones({
        formulario,

        alGuardar: (observaciones) => {
            const apiSeleccionada =
                document.querySelector(
                    ".selector--activo",
                );

            if (apiSeleccionada) {
                apiSeleccionada.dataset.apiObservaciones =
                    JSON.stringify(
                        observaciones,
                    );
            }

            renderObservaciones(
                observaciones,
            );

            cerrarModalObservaciones(
                modal,
            );
        },
    });
}