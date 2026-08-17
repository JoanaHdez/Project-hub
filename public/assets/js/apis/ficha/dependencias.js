import {
    inicializarItemsDependencias,
    inicializarEliminacionDependencias,
    cargarDependencias,
} from "./dependencias/componentes.js";

import {
    inicializarFormularioDependencias,
} from "./dependencias/formulario.js";

import {
    abrirModalDependencias,
    cerrarModalDependencias,
} from "./dependencias/modal.js";

import {
    renderDependencias,
} from "./dependencias/render.js";


/*==================================================*
*=              DEPENDENCIAS DE API                 =*
*==================================================*/

export function inicializarDependenciasFicha() {
    const botonCompletar =
        document.getElementById(
            "btn-completar-dependencias",
        );

    const modal =
        document.getElementById(
            "modal-dependencias-api",
        );

    const botonAgregar =
        document.getElementById(
            "btn-agregar-dependencia",
        );

    const contenedor =
        document.getElementById(
            "dependencias-lista",
        );

    const estadoVacio =
        document.getElementById(
            "dependencias-vacio",
        );

    const formulario =
        document.getElementById(
            "form-dependencias-api",
        );

    const botonEditar =
        document.getElementById(
            "btn-editar-dependencias",
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
    *=              COMPLETAR DEPENDENCIAS              =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            abrirModalDependencias(
                modal,
                {
                    limpiar: true,
                },
            );
        },
    );


    /*==================================================*
    *=              EDITAR DEPENDENCIAS                 =*
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

            let dependencias = [];

            try {
                dependencias =
                    JSON.parse(
                        apiSeleccionada
                            .dataset
                            .apiDependencias || "[]",
                    );
            } catch (error) {
                console.error(
                    "No fue posible leer las dependencias:",
                    error,
                );

                dependencias = [];
            }

            cargarDependencias(
                dependencias,
                contenedor,
                estadoVacio,
            );

            abrirModalDependencias(
                modal,
            );
        },
    );


    /*==================================================*
    *=              FORMULARIO DEPENDENCIAS             =*
    *==================================================*/

    inicializarItemsDependencias({
        botonAgregar,
        contenedor,
        estadoVacio,
    });

    inicializarEliminacionDependencias({
        contenedor,
        estadoVacio,
    });

    inicializarFormularioDependencias({
        formulario,

        alGuardar: (dependencias) => {
            const apiSeleccionada =
                document.querySelector(
                    ".selector--activo",
                );

            if (apiSeleccionada) {
                apiSeleccionada.dataset.apiDependencias =
                    JSON.stringify(
                        dependencias,
                    );
            }

            renderDependencias(
                dependencias,
            );

            cerrarModalDependencias(
                modal,
            );
        },
    });
}