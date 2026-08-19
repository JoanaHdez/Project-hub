import {
    obtenerModulosPorSistema,
} from "./datos.js";

import {
    renderModulos,
} from "./render.js";


/*==================================================*
*=               ELIMINAR MÓDULO                   =*
*==================================================*/

export function inicializarEliminarModulo() {

    const modal =
        document.getElementById(
            "modal-nuevo-modulo",
        );

    const formulario =
        document.getElementById(
            "form-nuevo-modulo",
        );

    const modalConfirmacion =
        document.getElementById(
            "modal-confirmar-eliminar-modulo",
        );

    if (
        !modal ||
        !formulario ||
        !modalConfirmacion
    ) {
        return;
    }


    const botonEliminar =
        modal.querySelector(
            "[data-modulo-eliminar]",
        );

    const botonConfirmar =
        modalConfirmacion.querySelector(
            "[data-confirmar-eliminar-modulo]",
        );

    if (
        !botonEliminar ||
        !botonConfirmar
    ) {
        return;
    }


    /*==================================================*
    *=              ABRIR CONFIRMACIÓN                 =*
    *==================================================*/

    botonEliminar.addEventListener(
        "click",
        () => {

            const idModulo =
                document.getElementById(
                    "modulo-id",
                )?.value ?? "";

            if (!idModulo) {
                return;
            }

            modalConfirmacion.classList.add(
                "modal--visible",
            );

            modalConfirmacion.setAttribute(
                "aria-hidden",
                "false",
            );

            document.body.style.overflow =
                "hidden";
        },
    );


    /*==================================================*
    *=              CONFIRMAR ELIMINACIÓN              =*
    *==================================================*/

    botonConfirmar.addEventListener(
        "click",
        async () => {

            const idModulo =
                document.getElementById(
                    "modulo-id",
                )?.value ?? "";

            const idSistema =
                formulario.dataset.sistemaId ?? "";

            if (
                !idModulo ||
                !idSistema
            ) {
                return;
            }

            botonConfirmar.disabled =
                true;

            try {

                const resultado =
                    await eliminarModulo(
                        idModulo,
                    );


                /*==================================================*
                *=          ACTUALIZAR DATOS LOCALES              =*
                *==================================================*/

                const contenedorDatos =
                    document.getElementById(
                        "datos-modulos",
                    );

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

                    modulos =
                        modulos.filter(
                            (modulo) =>
                                String(
                                    modulo.id_modulo ?? "",
                                ) !==
                                String(idModulo),
                        );

                    contenedorDatos.textContent =
                        JSON.stringify(
                            modulos,
                        );
                }


                /*==================================================*
                *=              RENDER MÓDULOS                     =*
                *==================================================*/

                const modulosSistema =
                    obtenerModulosPorSistema(
                        idSistema,
                    );

                const contenedorModulos =
                    document.querySelector(
                        "[data-contenedor-modulos]",
                    );

                renderModulos({
                    modulos:
                        modulosSistema,

                    contenedor:
                        contenedorModulos,
                });


                /*==================================================*
                *=          ACTUALIZAR CONTADORES                  =*
                *==================================================*/

                const total =
                    resultado.total_modulos ??
                    modulosSistema.length;

                const contadorExplorador =
                    document.querySelector(
                        "[data-total-modulos]",
                    );

                if (contadorExplorador) {
                    contadorExplorador.textContent =
                        total;
                }


                /*==================================================*
                *=          CONTADOR DE LA TARJETA                 =*
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
                        badge.textContent =
                            `${total} ${
                                total === 1
                                    ? "módulo"
                                    : "módulos"
                            }`;
                    }
                }


                /*==================================================*
                *=          CERRAR CONFIRMACIÓN                    =*
                *==================================================*/

                if (
                    document.activeElement
                    instanceof HTMLElement
                ) {
                    document.activeElement.blur();
                }

                modalConfirmacion.classList.remove(
                    "modal--visible",
                );

                modalConfirmacion.setAttribute(
                    "aria-hidden",
                    "true",
                );


                /*==================================================*
                *=              CERRAR FICHA                       =*
                *==================================================*/

                modal.classList.remove(
                    "modal--visible",
                );

                modal.setAttribute(
                    "aria-hidden",
                    "true",
                );

                document.body.style.overflow =
                    "";

            } catch (error) {

                console.error(
                    "Error al eliminar módulo:",
                    error,
                );

            } finally {

                botonConfirmar.disabled =
                    false;
            }
        },
    );
}


/*==================================================*
*=              PETICIÓN DELETE                    =*
*==================================================*/

async function eliminarModulo(
    idModulo,
) {
    const respuesta =
        await fetch(
            `/modulos/${idModulo}`,
            {
                method:
                    "DELETE",

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
            "No fue posible eliminar el módulo.",
        );
    }

    return resultado;
}