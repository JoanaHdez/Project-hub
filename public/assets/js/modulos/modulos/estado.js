import {
    mostrarNotificacion,
} from "../../proyectos/notificaciones.js";


/*==================================================*
*=                ESTADO DEL MÓDULO                =*
*==================================================*/

export function inicializarEstadoModulo() {

    document.addEventListener(
        "click",
        async (evento) => {

            const botonEstado =
                evento.target.closest(
                    "[data-modulo-estado]",
                );

            if (!botonEstado) {
                return;
            }

            const tarjeta =
                botonEstado.closest(
                    ".modulo-card",
                );

            if (!tarjeta) {
                return;
            }

            const idModulo =
                tarjeta.dataset.moduloId ?? "";

            if (!idModulo) {
                return;
            }

            const activoActual =
                botonEstado.dataset.activo === "1";

            const nuevoEstado =
                !activoActual;

            botonEstado.disabled =
                true;

            try {

                const resultado =
                    await cambiarEstadoModulo(
                        idModulo,
                        nuevoEstado,
                    );


                /*==================================================*
                *=              ACTUALIZAR TARJETA                 =*
                *==================================================*/

                actualizarEstadoVisual(
                    tarjeta,
                    botonEstado,
                    resultado.activo,
                );


                /*==================================================*
                *=          ACTUALIZAR DATOS LOCALES              =*
                *==================================================*/

                actualizarDatosLocales(
                    idModulo,
                    resultado.modulo,
                );


                /*==================================================*
                *=              NOTIFICACIÓN                       =*
                *==================================================*/

                mostrarNotificacion({
                    tipo:
                        "success",

                    titulo:
                        resultado.activo
                            ? "Módulo activado"
                            : "Módulo desactivado",

                    mensaje:
                        resultado.mensaje ||
                        (
                            resultado.activo
                                ? "El módulo fue activado correctamente."
                                : "El módulo fue desactivado correctamente."
                        ),
                });

            } catch (error) {

                console.error(
                    "Error al cambiar el estado del módulo:",
                    error,
                );

                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo cambiar el estado",

                    mensaje:
                        error.message ||
                        "Ocurrió un error al cambiar el estado del módulo.",
                });

            } finally {

                botonEstado.disabled =
                    false;
            }
        },
    );
}


/*==================================================*
*=              PETICIÓN PATCH                     =*
*==================================================*/

async function cambiarEstadoModulo(
    idModulo,
    activo,
) {

    const respuesta =
        await fetch(
            `/modulos/${idModulo}/estado`,
            {
                method:
                    "PATCH",

                headers: {
                    "Content-Type":
                        "application/json",

                    "X-Requested-With":
                        "XMLHttpRequest",
                },

                body:
                    JSON.stringify({
                        activo,
                    }),
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
            "No fue posible cambiar el estado del módulo.",
        );
    }

    return resultado;
}


/*==================================================*
*=              ESTADO VISUAL                      =*
*==================================================*/

function actualizarEstadoVisual(
    tarjeta,
    botonEstado,
    activo,
) {

    tarjeta.dataset.moduloActivo =
        activo
            ? "1"
            : "0";

    botonEstado.dataset.activo =
        activo
            ? "1"
            : "0";

    botonEstado.classList.toggle(
        "modulo-card__estado-toggle--activo",
        activo,
    );

    botonEstado.classList.toggle(
        "modulo-card__estado-toggle--inactivo",
        !activo,
    );

    botonEstado.setAttribute(
        "aria-pressed",
        activo
            ? "true"
            : "false",
    );

    botonEstado.setAttribute(
        "aria-label",
        activo
            ? "Desactivar módulo"
            : "Activar módulo",
    );

    botonEstado.title =
        activo
            ? "Activo"
            : "Inactivo";
}


/*==================================================*
*=          ACTUALIZAR DATOS LOCALES              =*
*==================================================*/

function actualizarDatosLocales(
    idModulo,
    moduloActualizado,
) {

    const contenedorDatos =
        document.getElementById(
            "datos-modulos",
        );

    if (!contenedorDatos) {
        return;
    }

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
                ) === String(idModulo)
                    ? moduloActualizado
                    : modulo,
        );

    contenedorDatos.textContent =
        JSON.stringify(
            modulos,
        );
}