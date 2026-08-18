import {
    mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";


/*==================================================*
*=              FORMULARIO HISTORIAL                =*
*==================================================*/

export function inicializarFormularioHistorial({
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
                    titulo: "No se pudo guardar",
                    mensaje:
                        "No se encontró la API seleccionada.",
                });

                return;
            }

            const historial =
                obtenerHistorial();

            try {
                const respuesta =
                    await fetch(
                        `/apis/${idApi}/historial`,
                        {
                            method: "PATCH",

                            headers: {
                                "Content-Type":
                                    "application/json",

                                "X-Requested-With":
                                    "XMLHttpRequest",
                            },

                            body: JSON.stringify({
                                historial,
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
                        "No fue posible guardar el historial.",
                    );
                }

                if (
                    typeof alGuardar ===
                    "function"
                ) {
                    alGuardar(
                        resultado.historial,
                    );
                }

                mostrarNotificacion({
                    tipo: "success",

                    titulo:
                        "Historial guardado",

                    mensaje:
                        resultado.mensaje ||
                        "Historial guardado correctamente.",
                });

            } catch (error) {
                console.error(
                    "Error al guardar historial:",
                    error,
                );

                mostrarNotificacion({
                    tipo: "error",

                    titulo:
                        "No se pudo guardar",

                    mensaje:
                        error.message ||
                        "Ocurrió un error al guardar el historial.",
                });
            }
        },
    );
}


/*==================================================*
*=              OBTENER HISTORIAL                   =*
*==================================================*/

export function obtenerHistorial() {
    const contenedor =
        document.getElementById(
            "historial-lista",
        );

    if (!contenedor) {
        return [];
    }

    return Array.from(
        contenedor.querySelectorAll(
            "[data-historial]",
        ),
    )
        .map(
            (item) => ({
                version:
                    item.querySelector(
                        "[data-historial-version]",
                    )?.value.trim() ?? "",

                descripcion:
                    item.querySelector(
                        "[data-historial-descripcion]",
                    )?.value.trim() ?? "",

                fecha:
                    item.querySelector(
                        "[data-historial-fecha]",
                    )?.value.trim() ?? "",
            }),
        )
        .filter(
            (registro) =>
                registro.version !== "" ||
                registro.descripcion !== "" ||
                registro.fecha !== "",
        );
}