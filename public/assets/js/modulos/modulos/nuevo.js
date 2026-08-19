import {
    obtenerDatosNuevoModulo,
} from "./formulario.js";

import {
    guardarNuevoModulo,
} from "./guardar.js";

import {
    obtenerModulosPorSistema,
} from "./datos.js";

import {
    renderModulos,
} from "./render.js";

/*==================================================*
*=                NUEVO MÓDULO                     =*
*==================================================*/

export function inicializarNuevoModulo() {

    const formulario =
        document.getElementById(
            "form-nuevo-modulo",
        );

    if (!formulario) {
        return;
    }

    if (
        formulario.dataset.inicializado ===
        "true"
    ) {
        return;
    }

    formulario.dataset.inicializado =
        "true";


    /*==================================================*
    *=                  GUARDAR                        =*
    *==================================================*/

    formulario.addEventListener(
        "submit",
        async (evento) => {

            evento.preventDefault();

            /*==================================================*
            *=              VALIDAR MODO NUEVO                 =*
            *==================================================*/

            if (
                formulario.dataset.modo !==
                "nuevo"
            ) {
                return;
            }

            /*==================================================*
            *=                VALIDACIÓN                       =*
            *==================================================*/

            if (!formulario.checkValidity()) {
                formulario.reportValidity();
                return;
            }


            /*==================================================*
            *=                OBTENER DATOS                    =*
            *==================================================*/

            const datosModulo =
                obtenerDatosNuevoModulo(
                    formulario,
                );

            if (!datosModulo) {
                console.error(
                    "No fue posible obtener los datos del módulo.",
                );

                return;
            }


            /*==================================================*
            *=                   GUARDAR                        =*
            *==================================================*/
            try {
                const resultado =
                    await guardarNuevoModulo(
                        datosModulo,
                    );

                const modal =
                    document.getElementById(
                        "modal-nuevo-modulo",
                    );

                const contenedorDatos =
                    document.getElementById(
                        "datos-modulos",
                    );

                const contenedorModulos =
                    document.querySelector(
                        "[data-contenedor-modulos]",
                    );

                const totalModulos =
                    document.querySelector(
                        "[data-total-modulos]",
                    );


                /*==================================================*
                *=          ACTUALIZAR DATOS LOCALES              =*
                *==================================================*/

                if (contenedorDatos) {
                    let modulos = [];

                    try {
                        modulos =
                            JSON.parse(
                                contenedorDatos.textContent || "[]",
                            );
                    } catch {
                        modulos = [];
                    }

                    modulos.push(
                        resultado.modulo,
                    );

                    contenedorDatos.textContent =
                        JSON.stringify(
                            modulos,
                        );
                }


                /*==================================================*
                *=              RENDER DEL SISTEMA                =*
                *==================================================*/

                const idSistema =
                    datosModulo.id_sistema;

                const modulosSistema =
                    obtenerModulosPorSistema(
                        idSistema,
                    );

                renderModulos({
                    modulos:
                        modulosSistema,

                    contenedor:
                        contenedorModulos,
                });

                if (totalModulos) {
                    totalModulos.textContent =
                        resultado.total_modulos ??
                        modulosSistema.length;
                }

                /*==================================================*
                *=          ACTUALIZAR TARJETA DEL SISTEMA         =*
                *==================================================*/

                const tarjetaSistema =
                    document.querySelector(
                        `.sistema-card[data-sistema-id="${idSistema}"]`,
                    );

                if (tarjetaSistema) {
                    const badge =
                        tarjetaSistema.querySelector(
                            ".sistema-card__badge",
                        );

                    if (badge) {
                        const total =
                            resultado.total_modulos ??
                            modulosSistema.length;

                        badge.textContent =
                            `${total} ${total === 1
                                ? "módulo"
                                : "módulos"
                            }`;
                    }
                }

                /*==================================================*
                *=              CERRAR MODAL                      =*
                *==================================================*/

                if (modal) {
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


                /*==================================================*
                *=              LIMPIAR FORMULARIO                =*
                *==================================================*/

                formulario.reset();

            } catch (error) {
                console.error(
                    "Error al registrar el módulo:",
                    error,
                );
            }
        },
    );
}