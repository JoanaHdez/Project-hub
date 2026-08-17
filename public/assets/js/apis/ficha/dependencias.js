import {
    abrirModalDependencias,
} from "./dependencias/modal.js";

import {
    inicializarItemsDependencias,
    inicializarEliminacionDependencias,
} from "./dependencias/componentes.js";


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
}