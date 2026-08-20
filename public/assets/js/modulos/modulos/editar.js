import {
    obtenerDatosNuevoModulo,
} from "./formulario.js";

import {
    obtenerModulosPorSistema,
} from "./datos.js";

import {
    renderModulos,
} from "./render.js";

import {
    mostrarNotificacion,
} from "../../proyectos/notificaciones.js";


/*==================================================*
*=                EDITAR MÓDULO                    =*
*==================================================*/

export function inicializarEditarModulo() {

    const formulario =
        document.getElementById(
            "form-nuevo-modulo",
        );

    if (!formulario) {
        return;
    }


    /*==================================================*
    *=                  GUARDAR CAMBIOS                =*
    *==================================================*/

    formulario.addEventListener(
        "submit",
        async (evento) => {

            evento.preventDefault();


            /*==================================================*
            *=                VALIDAR MODO                      =*
            *==================================================*/

            if (
                formulario.dataset.modo !==
                "editar"
            ) {
                return;
            }


            /*==================================================*
            *=                VALIDACIÓN                        =*
            *==================================================*/

            if (!formulario.checkValidity()) {
                formulario.reportValidity();
                return;
            }


            /*==================================================*
            *=              ID DEL MÓDULO                       =*
            *==================================================*/

            const idModulo =
                document.getElementById(
                    "modulo-id",
                )?.value ?? "";

            if (!idModulo) {

                mostrarNotificacion({
                    tipo: "error",

                    titulo:
                        "No se pudo actualizar",

                    mensaje:
                        "No se encontró el módulo seleccionado.",
                });

                return;
            }


            /*==================================================*
            *=                OBTENER DATOS                     =*
            *==================================================*/

            const datosModulo =
                obtenerDatosNuevoModulo(
                    formulario,
                );

            if (!datosModulo) {

                mostrarNotificacion({
                    tipo: "error",

                    titulo:
                        "No se pudo actualizar",

                    mensaje:
                        "No fue posible obtener los datos del módulo.",
                });

                return;
            }


            /*==================================================*
            *=              IMAGEN SELECCIONADA                 =*
            *==================================================*/

            const inputImagen =
                formulario.querySelector(
                    "[data-modulo-imagen-input]",
                );

            const archivoImagen =
                inputImagen?.files?.[0] ??
                null;


            /*==================================================*
            *=                  ACTUALIZAR                      =*
            *==================================================*/

            try {

                /*
                 * Primero actualizamos los datos
                 * generales del módulo.
                 */
                const resultado =
                    await actualizarModulo(
                        idModulo,
                        datosModulo,
                    );

                let moduloActualizado =
                    resultado.modulo;


                /*==================================================*
                *=              SUBIR IMAGEN                       =*
                *==================================================*/

                if (archivoImagen) {

                    const resultadoImagen =
                        await actualizarImagenModulo(
                            idModulo,
                            archivoImagen,
                        );

                    moduloActualizado =
                        resultadoImagen.modulo;
                }


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
                                contenedorDatos.textContent ||
                                "[]",
                            );

                    } catch {

                        modulos = [];
                    }

                    modulos =
                        modulos.map(
                            (modulo) =>
                                String(
                                    modulo.id_modulo ?? "",
                                ) ===
                                String(idModulo)
                                    ? moduloActualizado
                                    : modulo,
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
                        datosModulo.id_sistema,
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
                *=              LIMPIAR IMAGEN                     =*
                *==================================================*/

                if (inputImagen) {
                    inputImagen.value =
                        "";
                }


                /*==================================================*
                *=              CERRAR MODAL                       =*
                *==================================================*/

                const modal =
                    document.getElementById(
                        "modal-nuevo-modulo",
                    );

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
                *=              NOTIFICACIÓN                       =*
                *==================================================*/

                mostrarNotificacion({
                    tipo:
                        "success",

                    titulo:
                        "Módulo actualizado",

                    mensaje:
                        archivoImagen
                            ? "Los cambios y la imagen fueron guardados correctamente."
                            : (
                                resultado.mensaje ||
                                "Los cambios fueron guardados correctamente."
                            ),
                });

            } catch (error) {

                console.error(
                    "Error al editar módulo:",
                    error,
                );

                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo actualizar",

                    mensaje:
                        error.message ||
                        "Ocurrió un error al actualizar el módulo.",
                });
            }
        },
    );
}


/*==================================================*
*=              PETICIÓN PUT                       =*
*==================================================*/

async function actualizarModulo(
    idModulo,
    datosModulo,
) {
    const respuesta =
        await fetch(
            `/modulos/${idModulo}`,
            {
                method:
                    "PUT",

                headers: {
                    "Content-Type":
                        "application/json",

                    "X-Requested-With":
                        "XMLHttpRequest",
                },

                body:
                    JSON.stringify(
                        datosModulo,
                    ),
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
            "No fue posible actualizar el módulo.",
        );
    }

    return resultado;
}


/*==================================================*
*=              SUBIR IMAGEN                       =*
*==================================================*/

async function actualizarImagenModulo(
    idModulo,
    archivo,
) {

    const datosImagen =
        new FormData();

    datosImagen.append(
        "imagen",
        archivo,
    );

    const respuesta =
        await fetch(
            `/modulos/${idModulo}/imagen`,
            {
                method:
                    "POST",

                headers: {
                    "X-Requested-With":
                        "XMLHttpRequest",
                },

                body:
                    datosImagen,
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
            "No fue posible actualizar la imagen del módulo.",
        );
    }

    return resultado;
}