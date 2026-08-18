import {
    inicializarItemsObservaciones,
    inicializarEliminacionObservaciones,
    cargarObservaciones,
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

import {
    abrirConfirmacionApi,
} from "../acciones/administrar/confirmacion.js";

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

    const botonEditar =
        document.getElementById(
            "btn-editar-observaciones",
        );

    const botonEliminar =
        document.getElementById(
            "btn-eliminar-observaciones",
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
    *=              EDITAR OBSERVACIONES                =*
    *==================================================*/

    botonEditar?.addEventListener(
        "click",
        () => {
            const apiSeleccionada =
                document.querySelector(
                    ".selector--activo",
                );

            if (!apiSeleccionada) {
                return;
            }

            let observaciones = [];

            try {
                observaciones =
                    JSON.parse(
                        apiSeleccionada
                            .dataset
                            .apiObservaciones || "[]",
                    );
            } catch (error) {
                console.error(
                    "No fue posible leer las observaciones:",
                    error,
                );

                observaciones = [];
            }

            cargarObservaciones(
                observaciones,
                contenedor,
                estadoVacio,
            );

            abrirModalObservaciones(
                modal,
            );
        },
    );


    /*==================================================*
    *=              ELIMINAR OBSERVACIONES             =*
    *==================================================*/

    botonEliminar?.addEventListener(
        "click",
        () => {
            const apiSeleccionada =
                document.querySelector(
                    ".selector--activo",
                );

            if (!apiSeleccionada) {
                return;
            }

            const idApi =
                apiSeleccionada.dataset.apiId ?? "";

            const nombreApi =
                apiSeleccionada.dataset.apiNombre ??
                "API";

            if (!idApi) {
                return;
            }

            abrirConfirmacionApi({
                accion:
                    "eliminar-observaciones",

                idApi,

                nombreApi,
            });
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