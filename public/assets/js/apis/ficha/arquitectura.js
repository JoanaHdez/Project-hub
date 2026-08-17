import {
    inicializarFormularioArquitectura,
} from "./arquitectura/formulario.js";

import {
    inicializarComponentesArquitectura,
    cargarComponentesArquitectura,
} from "./arquitectura/componentes.js";

import {
    renderArquitectura,
} from "./arquitectura/render.js";

import {
    abrirModalArquitectura,
    cerrarModalArquitectura,
} from "./arquitectura/modal.js";

import {
    abrirConfirmacionApi,
} from "../acciones/administrar/confirmacion.js";

/*==================================================*
*=              ARQUITECTURA DE API                 =*
*==================================================*/

export function inicializarArquitecturaFicha() {

    const botonCompletar =
        document.getElementById(
            "btn-completar-arquitectura",
        );

    const botonEditar =
        document.getElementById(
            "btn-editar-arquitectura",
        );

    const botonEliminar =
        document.getElementById(
            "btn-eliminar-arquitectura",
        );

    const modal =
        document.getElementById(
            "modal-arquitectura-api",
        );

    const botonAgregar =
        document.getElementById(
            "btn-agregar-componente-arquitectura",
        );

    const contenedor =
        document.getElementById(
            "arquitectura-componentes",
        );

    const estadoVacio =
        document.getElementById(
            "arquitectura-componentes-vacio",
        );

    const formulario =
        document.getElementById(
            "form-arquitectura-api",
        );


    if (
        !botonCompletar ||
        !modal ||
        !botonAgregar ||
        !contenedor ||
        !estadoVacio
    ) {
        return;
    }


    /*==================================================*
    *=              COMPLETAR ARQUITECTURA              =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            abrirModalArquitectura(
                modal,
                {
                    limpiar: true,
                },
            );
        },
    );


    /*==================================================*
    *=              EDITAR ARQUITECTURA                 =*
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


            let arquitectura = {};

            try {
                arquitectura =
                    JSON.parse(
                        apiSeleccionada
                            .dataset
                            .apiArquitectura || "{}",
                    );
            } catch (error) {
                console.error(
                    "No fue posible leer la arquitectura:",
                    error,
                );

                arquitectura = {};
            }


            const campoModulo =
                document.getElementById(
                    "arquitectura-modulo",
                );

            if (campoModulo) {
                campoModulo.value =
                    arquitectura.modulo ?? "";
            }


            cargarComponentesArquitectura(
                arquitectura.componentes,
                contenedor,
                estadoVacio,
            );


            abrirModalArquitectura(
                modal,
            );
        },
    );


    /*==================================================*
    *=              ELIMINAR ARQUITECTURA               =*
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
                    "eliminar-arquitectura",

                idApi,

                nombreApi,
            });
        },
    );


    /*==================================================*
    *=              COMPONENTES                         =*
    *==================================================*/

    inicializarComponentesArquitectura({
        botonAgregar,
        contenedor,
        estadoVacio,
    });


    /*==================================================*
    *=              FORMULARIO                          =*
    *==================================================*/

    inicializarFormularioArquitectura({
        formulario,

        alGuardar: (arquitectura) => {
            const apiSeleccionada =
                document.querySelector(
                    ".selector--activo",
                );

            if (apiSeleccionada) {
                apiSeleccionada.dataset.apiArquitectura =
                    JSON.stringify(
                        arquitectura,
                    );
            }

            renderArquitectura(
                arquitectura,
            );

            cerrarModalArquitectura(
                modal,
            );
        },
    });
}