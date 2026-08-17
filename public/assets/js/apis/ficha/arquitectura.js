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
        async () => {
            const apiSeleccionada =
                document.querySelector(
                    ".selector--activo",
                );

            if (!apiSeleccionada) {
                return;
            }

            const idApi =
                apiSeleccionada.dataset.apiId ?? "";

            if (!idApi) {
                return;
            }

            const confirmar =
                window.confirm(
                    "¿Deseas eliminar únicamente la información de Arquitectura de esta API?",
                );

            if (!confirmar) {
                return;
            }

            try {
                const respuesta =
                    await fetch(
                        `/apis/${idApi}/arquitectura`,
                        {
                            method: "DELETE",

                            headers: {
                                "X-Requested-With":
                                    "XMLHttpRequest",
                            },
                        },
                    );

                const resultado =
                    await respuesta.json();

                if (
                    !respuesta.ok ||
                    !resultado.ok
                ) {
                    throw new Error(
                        resultado.mensaje ||
                        "No fue posible eliminar la arquitectura.",
                    );
                }

                const arquitecturaVacia =
                    resultado.arquitectura ?? {
                        modulo: "",
                        componentes: [],
                    };

                apiSeleccionada.dataset.apiArquitectura =
                    JSON.stringify(
                        arquitecturaVacia,
                    );

                renderArquitectura(
                    arquitecturaVacia,
                );

            } catch (error) {
                console.error(
                    "Error al eliminar arquitectura:",
                    error,
                );
            }
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