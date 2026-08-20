import {
    mostrarNotificacion,
} from "../proyectos/notificaciones.js";


/*==================================================*
*=              NUEVO DOCUMENTO                    =*
*==================================================*/

export function inicializarNuevoDocumento() {

    const formulario =
        document.getElementById(
            "form-documento",
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


    const botonGuardar =
        formulario.querySelector(
            'button[type="submit"]',
        );


    /*==================================================*
    *=                  SUBMIT                        =*
    *==================================================*/

    formulario.addEventListener(
        "submit",
        async (evento) => {

            evento.preventDefault();


            /*==================================================*
            *=                 VALIDACIÓN                       =*
            *==================================================*/

            if (!formulario.checkValidity()) {
                formulario.reportValidity();
                return;
            }


            /*==================================================*
            *=              PREPARAR DATOS                      =*
            *==================================================*/

            const datos =
                new FormData(
                    formulario,
                );

            const idSistema =
                datos.get(
                    "id_sistema",
                );

            const archivo =
                datos.get(
                    "archivo",
                );

            if (!idSistema) {

                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo subir",

                    mensaje:
                        "Selecciona el sistema al que pertenece el documento.",
                });

                return;
            }

            if (
                !(archivo instanceof File) ||
                !archivo.name
            ) {

                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo subir",

                    mensaje:
                        "Selecciona un archivo.",
                });

                return;
            }


            /*==================================================*
            *=              BLOQUEAR BOTÓN                      =*
            *==================================================*/

            if (botonGuardar) {
                botonGuardar.disabled =
                    true;

                botonGuardar.textContent =
                    "Subiendo...";
            }


            /*==================================================*
            *=                  GUARDAR                         =*
            *==================================================*/

            try {

                const resultado =
                    await guardarDocumento(
                        datos,
                    );


                /*==================================================*
                *=                NOTIFICACIÓN                     =*
                *==================================================*/

                mostrarNotificacion({
                    tipo:
                        "success",

                    titulo:
                        "Documento guardado",

                    mensaje:
                        resultado.mensaje ||
                        "El documento fue subido correctamente.",
                });


                /*==================================================*
                *=                REDIRECCIÓN                      =*
                *==================================================*/

                const sistemaDestino =
                    resultado.id_sistema ||
                    idSistema;

                /*
                 * Damos un momento para que la
                 * notificación pueda visualizarse.
                 */
                window.setTimeout(
                    () => {

                        window.location.href =
                            `/documentos/sistema/${sistemaDestino}`;

                    },
                    700,
                );

            } catch (error) {

                console.error(
                    "Error al subir documento:",
                    error,
                );


                /*==================================================*
                *=            NOTIFICACIÓN ERROR                   =*
                *==================================================*/

                mostrarNotificacion({
                    tipo:
                        "error",

                    titulo:
                        "No se pudo subir",

                    mensaje:
                        error.message ||
                        "Ocurrió un error al subir el documento.",
                });


                /*==================================================*
                *=              RESTAURAR BOTÓN                    =*
                *==================================================*/

                if (botonGuardar) {
                    botonGuardar.disabled =
                        false;

                    botonGuardar.textContent =
                        "Subir documento";
                }
            }
        },
    );
}


/*==================================================*
*=              PETICIÓN POST                      =*
*==================================================*/

async function guardarDocumento(
    datos,
) {

    const respuesta =
        await fetch(
            "/documentos",
            {
                method:
                    "POST",

                headers: {
                    "X-Requested-With":
                        "XMLHttpRequest",
                },

                body:
                    datos,
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
            "No fue posible subir el documento.",
        );
    }

    return resultado;
}