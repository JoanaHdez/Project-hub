import {
    inicializarFormularioArquitectura,
} from "./arquitectura/formulario.js";

import {
    inicializarComponentesArquitectura,
} from "./arquitectura/componentes.js";

import {
    renderArquitectura,
} from "./arquitectura/render.js";

/*==================================================*
*=              ARQUITECTURA DE API                 =*
*==================================================*/

export function inicializarArquitecturaFicha() {
    const botonCompletar =
        document.getElementById(
            "btn-completar-arquitectura",
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
    *=              ABRIR MODAL                        =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            modal.classList.add(
                "modal--visible",
            );

            modal.setAttribute(
                "aria-hidden",
                "false",
            );

            document.body.style.overflow =
                "hidden";
        },
    );


    inicializarComponentesArquitectura({
        botonAgregar,
        contenedor,
        estadoVacio,
    });

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


    /*==================================================*
    *=              CERRAR MODAL                        =*
    *==================================================*/

    function cerrarModalArquitectura(
        modal,
    ) {
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
}