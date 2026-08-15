import {
    mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";


/*==================================================*
*=          FORMULARIO DE ARQUITECTURA              =*
*==================================================*/

export function inicializarFormularioArquitectura({
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

            const arquitectura =
                obtenerArquitectura();

            try {
                const respuesta =
                    await fetch(
                        `/apis/${idApi}/arquitectura`,
                        {
                            method: "PATCH",

                            headers: {
                                "Content-Type":
                                    "application/json",

                                "X-Requested-With":
                                    "XMLHttpRequest",
                            },

                            body: JSON.stringify({
                                arquitectura,
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
                        "No fue posible guardar la arquitectura.",
                    );
                }

                if (
                    typeof alGuardar ===
                    "function"
                ) {
                    alGuardar(
                        resultado.arquitectura,
                    );
                }

                mostrarNotificacion({
                    tipo: "success",
                    titulo:
                        "Arquitectura guardada",
                    mensaje:
                        resultado.mensaje ||
                        "Arquitectura guardada correctamente.",
                });

            } catch (error) {
                console.error(
                    "Error al guardar arquitectura:",
                    error,
                );

                mostrarNotificacion({
                    tipo: "error",
                    titulo:
                        "No se pudo guardar",
                    mensaje:
                        error.message ||
                        "Ocurrió un error al guardar la arquitectura.",
                });
            }
        },
    );
}


/*==================================================*
*=              OBTENER ARQUITECTURA                =*
*==================================================*/

export function obtenerArquitectura() {
    const campoModulo =
        document.getElementById(
            "arquitectura-modulo",
        );

    const contenedor =
        document.getElementById(
            "arquitectura-componentes",
        );

    if (!contenedor) {
        return {
            modulo:
                campoModulo?.value.trim() ?? "",

            componentes: [],
        };
    }

    const componentes =
        Array.from(
            contenedor.querySelectorAll(
                "[data-componente-arquitectura]",
            ),
        )
            .map((item) => ({
                tipo:
                    item.querySelector(
                        "[data-arquitectura-tipo]",
                    )?.value ?? "",

                archivo:
                    item.querySelector(
                        "[data-arquitectura-archivo]",
                    )?.value.trim() ?? "",
            }))
            .filter(
                (componente) =>
                    componente.tipo !== "" ||
                    componente.archivo !== "",
            );

    return {
        modulo:
            campoModulo?.value.trim() ?? "",

        componentes,
    };
}