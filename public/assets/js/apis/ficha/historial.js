import {
    inicializarItemsHistorial,
    inicializarEliminacionHistorial,
    cargarHistorial,
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

import {
    abrirConfirmacionApi,
} from "../acciones/administrar/confirmacion.js";
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

    const botonEditar =
        document.getElementById(
            "btn-editar-historial",
        );

    const botonEliminar =
        document.getElementById(
            "btn-eliminar-historial",
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
    *=              EDITAR HISTORIAL                    =*
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

            let historial = [];

            try {
                historial =
                    JSON.parse(
                        apiSeleccionada
                            .dataset
                            .apiHistorial || "[]",
                    );
            } catch (error) {
                console.error(
                    "No fue posible leer el historial:",
                    error,
                );

                historial = [];
            }

            cargarHistorial(
                historial,
                contenedor,
                estadoVacio,
            );

            abrirModalHistorial(
                modal,
            );
        },
    );

    /*==================================================*
    *=              ELIMINAR HISTORIAL                  =*
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
                    "eliminar-historial",

                idApi,

                nombreApi,
            });
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