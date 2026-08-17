import {
    mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";


/*==================================================*
*=          FORMULARIO DE DEPENDENCIAS              =*
*==================================================*/

export function inicializarFormularioDependencias({
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

            const dependencias =
                obtenerDependencias();

            try {
                const respuesta =
                    await fetch(
                        `/apis/${idApi}/dependencias`,
                        {
                            method: "PATCH",

                            headers: {
                                "Content-Type":
                                    "application/json",

                                "X-Requested-With":
                                    "XMLHttpRequest",
                            },

                            body: JSON.stringify({
                                dependencias,
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
                        "No fue posible guardar las dependencias.",
                    );
                }

                if (
                    typeof alGuardar ===
                    "function"
                ) {
                    alGuardar(
                        resultado.dependencias,
                    );
                }

                mostrarNotificacion({
                    tipo: "success",
                    titulo:
                        "Dependencias guardadas",
                    mensaje:
                        resultado.mensaje ||
                        "Dependencias guardadas correctamente.",
                });

            } catch (error) {
                console.error(
                    "Error al guardar dependencias:",
                    error,
                );

                mostrarNotificacion({
                    tipo: "error",
                    titulo:
                        "No se pudo guardar",
                    mensaje:
                        error.message ||
                        "Ocurrió un error al guardar las dependencias.",
                });
            }
        },
    );
}


/*==================================================*
*=              OBTENER DEPENDENCIAS                =*
*==================================================*/

export function obtenerDependencias() {
    const contenedor =
        document.getElementById(
            "dependencias-lista",
        );

    if (!contenedor) {
        return [];
    }

    return Array.from(
        contenedor.querySelectorAll(
            "[data-dependencia]",
        ),
    )
        .map((item) => ({
            tipo:
                item.querySelector(
                    "[data-dependencia-tipo]",
                )?.value ?? "",

            nombre:
                item.querySelector(
                    "[data-dependencia-nombre]",
                )?.value.trim() ?? "",

            descripcion:
                item.querySelector(
                    "[data-dependencia-descripcion]",
                )?.value.trim() ?? "",

            estado:
                item.querySelector(
                    "[data-dependencia-estado]",
                )?.value ?? "",
        }))
        .filter(
            (dependencia) =>
                dependencia.tipo !== "" ||
                dependencia.nombre !== "" ||
                dependencia.descripcion !== "" ||
                dependencia.estado !== "",
        );
}