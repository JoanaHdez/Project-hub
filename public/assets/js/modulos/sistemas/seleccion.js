import {
    obtenerModulosPorSistema,
} from "../modulos/datos.js";

import {
    renderModulos,
} from "../modulos/render.js";


/*==================================================*
*=              SELECCIÓN DE SISTEMA               =*
*==================================================*/

export function inicializarSeleccionSistema() {
    const vistaModulos =
        document.querySelector(
            "[data-vista-modulos]",
        );

    const contenedorModulos =
        document.querySelector(
            "[data-contenedor-modulos]",
        );

    const tituloSistema =
        document.querySelector(
            "[data-sistema-titulo]",
        );

    const descripcionSistema =
        document.querySelector(
            "[data-sistema-descripcion]",
        );

    const totalModulos =
        document.querySelector(
            "[data-total-modulos]",
        );

    const proyectoSistema =
        document.querySelector(
            ".explorador-modulos__proyecto",
        );

    if (
        !vistaModulos ||
        !contenedorModulos
    ) {
        return;
    }


    /*==================================================*
    *=              TARJETAS DE SISTEMAS              =*
    *==================================================*/

    document
        .querySelectorAll(
            ".sistema-card",
        )
        .forEach(
            (tarjeta) => {

                const abrirSistema =
                    () => {
                        const idSistema =
                            tarjeta.dataset.sistemaId ?? "";

                        if (!idSistema) {
                            return;
                        }

                        const modulos =
                            obtenerModulosPorSistema(
                                idSistema,
                            );


                        /*==================================================*
                        *=          INFORMACIÓN DEL SISTEMA                =*
                        *==================================================*/

                        if (tituloSistema) {
                            tituloSistema.textContent =
                                tarjeta.dataset.sistemaNombre ||
                                "Sistema";
                        }

                        if (proyectoSistema) {
                            proyectoSistema.textContent =
                                tarjeta.dataset.sistemaProyecto ||
                                "Sin proyecto";
                        }

                        if (descripcionSistema) {
                            descripcionSistema.textContent =
                                tarjeta.dataset.sistemaDescripcionValor ||
                                "Explora los módulos disponibles en este sistema.";
                        }

                        if (totalModulos) {
                            totalModulos.textContent =
                                modulos.length;
                        }


                        /*==================================================*
                        *=              RENDER MÓDULOS                     =*
                        *==================================================*/

                        renderModulos({
                            modulos,

                            contenedor:
                                contenedorModulos,
                        });


                        /*==================================================*
                        *=              MOSTRAR EXPLORADOR                 =*
                        *==================================================*/

                        vistaModulos.hidden =
                            false;

                        vistaModulos.classList.remove(
                            "modulos-entrada",
                        );

                        void vistaModulos.offsetWidth;

                        vistaModulos.classList.add(
                            "modulos-entrada",
                        );


                        /*==================================================*
                        *=              DESPLAZAR VISTA                    =*
                        *==================================================*/

                        vistaModulos.scrollIntoView({
                            behavior:
                                "smooth",

                            block:
                                "start",
                        });
                    };


                /*==================================================*
                *=                  CLICK                           =*
                *==================================================*/

                tarjeta.addEventListener(
                    "click",
                    abrirSistema,
                );


                /*==================================================*
                *=                  TECLADO                         =*
                *==================================================*/

                tarjeta.addEventListener(
                    "keydown",
                    (evento) => {
                        if (
                            evento.key === "Enter" ||
                            evento.key === " "
                        ) {
                            evento.preventDefault();

                            abrirSistema();
                        }
                    },
                );
            },
        );
}