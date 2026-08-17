/*==================================================*
*=              RENDER OBSERVACIONES                =*
*==================================================*/

export function renderObservaciones(
    observaciones,
) {
    const pendiente =
        document.getElementById(
            "ficha-observaciones-pendiente",
        );

    const contenido =
        document.getElementById(
            "ficha-observaciones-contenido",
        );

    const lista =
        document.getElementById(
            "ficha-observaciones-lista",
        );

    if (
        !pendiente ||
        !contenido ||
        !lista
    ) {
        return;
    }

    const listaObservaciones =
        Array.isArray(observaciones)
            ? observaciones
            : [];

    const tieneDatos =
        listaObservaciones.length > 0;

    pendiente.hidden =
        tieneDatos;

    contenido.hidden =
        !tieneDatos;

    lista.innerHTML = "";

    if (!tieneDatos) {
        return;
    }


    /*==================================================*
    *=              CREAR OBSERVACIONES                =*
    *==================================================*/

    listaObservaciones.forEach(
        (observacion) => {
            const configuracion =
                obtenerConfiguracionTipo(
                    observacion.tipo,
                );

            const articulo =
                document.createElement(
                    "article",
                );

            articulo.className =
                `ficha-alerta ${configuracion.clase}`;

            articulo.innerHTML = `
                <div class="ficha-alerta__encabezado">

                    <span
                        class="ficha-alerta__punto"
                        aria-hidden="true"
                    ></span>

                    <strong>
                        ${escaparHtml(
                            configuracion.titulo,
                        )}
                    </strong>

                </div>

                <p>
                    ${escaparHtml(
                        observacion.mensaje || "",
                    )}
                </p>
            `;

            lista.appendChild(
                articulo,
            );
        },
    );
}


/*==================================================*
*=          CONFIGURACIÓN POR TIPO                  =*
*==================================================*/

function obtenerConfiguracionTipo(
    tipo,
) {
    switch (tipo) {

        case "recomendacion":
            return {
                titulo:
                    "Recomendación",

                clase:
                    "ficha-alerta--warning",
            };


        case "importante":
            return {
                titulo:
                    "Importante",

                clase:
                    "ficha-alerta--danger",
            };


        case "informacion":
        default:
            return {
                titulo:
                    "Información",

                clase:
                    "ficha-alerta--info",
            };
    }
}


/*==================================================*
*=                ESCAPAR HTML                      =*
*==================================================*/

function escaparHtml(
    valor,
) {
    const div =
        document.createElement(
            "div",
        );

    div.textContent =
        valor ?? "";

    return div.innerHTML;
}