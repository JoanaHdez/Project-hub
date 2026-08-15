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