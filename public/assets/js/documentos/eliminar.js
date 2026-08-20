import {
    mostrarNotificacion,
} from "../proyectos/notificaciones.js";


/*==================================================*
*=              ELIMINAR DOCUMENTO                 =*
*==================================================*/

export function inicializarEliminarDocumento() {

    const modalConfirmacion =
        document.getElementById(
            "modal-confirmar-eliminar-documento",
        );

    if (!modalConfirmacion) {
        return;
    }


    const botonConfirmar =
        modalConfirmacion.querySelector(
            "[data-confirmar-eliminar-documento]",
        );

    if (!botonConfirmar) {
        return;
    }


    /*
     * Aquí guardaremos temporalmente
     * el documento seleccionado.
     */
    let idDocumentoSeleccionado =
        "";

    let nombreDocumentoSeleccionado =
        "";


    /*==================================================*
    *=              ABRIR CONFIRMACIÓN                =*
    *==================================================*/

    document.addEventListener(
        "click",
        (evento) => {

            const botonEliminar =
                evento.target.closest(
                    "[data-eliminar-documento]",
                );

            if (!botonEliminar) {
                return;
            }


            /*==================================================*
            *=              DATOS DOCUMENTO                    =*
            *==================================================*/

            idDocumentoSeleccionado =
                botonEliminar.dataset.documentoId
                ?? "";

            nombreDocumentoSeleccionado =
                botonEliminar.dataset.documentoNombre
                ?? "Documento";


            if (!idDocumentoSeleccionado) {

                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo eliminar",

                    mensaje:
                        "No se encontró el documento seleccionado.",
                });

                return;
            }


            /*==================================================*
            *=                ABRIR MODAL                       =*
            *==================================================*/

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
    *=              CONFIRMAR ELIMINACIÓN             =*
    *==================================================*/

    botonConfirmar.addEventListener(
        "click",
        async () => {

            if (!idDocumentoSeleccionado) {
                return;
            }


            botonConfirmar.disabled =
                true;


            try {

                const resultado =
                    await eliminarDocumento(
                        idDocumentoSeleccionado,
                    );


                /*==================================================*
                *=              ELIMINAR FILA                      =*
                *==================================================*/

                const fila =
                    document.querySelector(
                        `[data-documento-fila][data-documento-id="${idDocumentoSeleccionado}"]`,
                    );

                if (fila) {
                    fila.remove();
                }


                /*==================================================*
                *=              CERRAR MODAL                       =*
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

                document.body.style.overflow =
                    "";


                /*==================================================*
                *=              NOTIFICACIÓN                       =*
                *==================================================*/

                mostrarNotificacion({
                    tipo:
                        "success",

                    titulo:
                        "Documento eliminado",

                    mensaje:
                        resultado.mensaje ||
                        "El documento fue eliminado correctamente.",
                });


                /*==================================================*
                *=          ÚLTIMO DOCUMENTO                      =*
                *==================================================*/

                if (
                    Number(
                        resultado.total_documentos,
                    ) === 0
                ) {

                    /*
                     * Si acabamos de eliminar el último
                     * documento, regresamos a la vista
                     * principal.
                     *
                     * Ahí la tarjeta del sistema ya no
                     * aparecerá.
                     */

                    window.setTimeout(
                        () => {

                            window.location.href =
                                "/documentos";

                        },
                        700,
                    );
                }


                /*==================================================*
                *=              LIMPIAR DATOS                      =*
                *==================================================*/

                idDocumentoSeleccionado =
                    "";

                nombreDocumentoSeleccionado =
                    "";

            } catch (error) {

                console.error(
                    "Error al eliminar documento:",
                    error,
                );


                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo eliminar",

                    mensaje:
                        error.message ||
                        "Ocurrió un error al eliminar el documento.",
                });

            } finally {

                botonConfirmar.disabled =
                    false;
            }
        },
    );


    /*==================================================*
    *=          CERRAR / CANCELAR CONFIRMACIÓN         =*
    *==================================================*/

    modalConfirmacion.addEventListener(
        "click",
        (evento) => {

            const cerrar =
                evento.target.closest(
                    "[data-modal-cerrar]",
                );

            if (!cerrar) {
                return;
            }

            idDocumentoSeleccionado =
                "";

            nombreDocumentoSeleccionado =
                "";
        },
    );
}


/*==================================================*
*=              PETICIÓN DELETE                    =*
*==================================================*/

async function eliminarDocumento(
    idDocumento,
) {

    const respuesta =
        await fetch(
            `/documentos/${idDocumento}`,
            {
                method:
                    "DELETE",

                headers: {
                    "X-Requested-With":
                        "XMLHttpRequest",
                },
            },
        );


    /*==================================================*
    *=              LEER RESPUESTA                    =*
    *==================================================*/

    let resultado;

    try {

        resultado =
            await respuesta.json();

    } catch {

        throw new Error(
            "El servidor devolvió una respuesta no válida.",
        );
    }


    /*==================================================*
    *=              VALIDAR RESPUESTA                  =*
    *==================================================*/

    if (
        !respuesta.ok ||
        !resultado.ok
    ) {
        throw new Error(
            resultado.mensaje ||
            "No fue posible eliminar el documento.",
        );
    }

    return resultado;
}