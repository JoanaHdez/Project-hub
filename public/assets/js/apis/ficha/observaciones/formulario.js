import {
    mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";


/*==================================================*
*=          FORMULARIO OBSERVACIONES                =*
*==================================================*/

export function inicializarFormularioObservaciones({
    formulario,
    alGuardar,
}) {
    if (!formulario) {
        return;
    }

    formulario.addEventListener(
        "submit",
        async (event) => {
            event.preventDefault();

            const idApi =
                document.body.dataset.apiSeleccionadaId ?? "";

            if (!idApi) {
                mostrarNotificacion({
                    tipo: "error",
                    titulo:
                        "No se pudo guardar",
                    mensaje:
                        "No se encontró la API seleccionada.",
                });

                return;
            }

            const observaciones =
                obtenerObservaciones();

            try {
                const respuesta =
                    await fetch(
                        `/apis/${idApi}/observaciones`,
                        {
                            method: "PATCH",

                            headers: {
                                "Content-Type":
                                    "application/json",

                                "X-Requested-With":
                                    "XMLHttpRequest",
                            },

                            body: JSON.stringify({
                                observaciones,
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
                        "No fue posible guardar las observaciones.",
                    );
                }

                if (
                    typeof alGuardar ===
                    "function"
                ) {
                    alGuardar(
                        resultado.observaciones,
                    );
                }

                mostrarNotificacion({
                    tipo: "success",

                    titulo:
                        "Observaciones guardadas",

                    mensaje:
                        resultado.mensaje ||
                        "Observaciones guardadas correctamente.",
                });

            } catch (error) {
                console.error(
                    "Error al guardar observaciones:",
                    error,
                );

                mostrarNotificacion({
                    tipo: "error",

                    titulo:
                        "No se pudo guardar",

                    mensaje:
                        error.message ||
                        "Ocurrió un error al guardar las observaciones.",
                });
            }
        },
    );
}


/*==================================================*
*=              OBTENER OBSERVACIONES               =*
*==================================================*/

export function obtenerObservaciones() {
    const contenedor =
        document.getElementById(
            "observaciones-lista",
        );

    if (!contenedor) {
        return [];
    }

    return Array.from(
        contenedor.querySelectorAll(
            "[data-observacion]",
        ),
    )
        .map(
            (item) => ({
                tipo:
                    item.querySelector(
                        "[data-observacion-tipo]",
                    )?.value ?? "",

                mensaje:
                    item.querySelector(
                        "[data-observacion-mensaje]",
                    )?.value.trim() ?? "",
            }),
        )
        .filter(
            (observacion) =>
                observacion.tipo !== "" ||
                observacion.mensaje !== "",
        );
}