/*==================================================*
*=              RENDER HISTORIAL                    =*
*==================================================*/

export function renderHistorial(
    historial,
) {
    const pendiente =
        document.getElementById(
            "ficha-historial-pendiente",
        );

    const contenido =
        document.getElementById(
            "ficha-historial-contenido",
        );

    const lista =
        document.getElementById(
            "ficha-historial-lista",
        );

    if (
        !pendiente ||
        !contenido ||
        !lista
    ) {
        return;
    }

    const registros =
        Array.isArray(historial)
            ? historial
            : [];

    const tieneDatos =
        registros.length > 0;

    pendiente.hidden =
        tieneDatos;

    contenido.hidden =
        !tieneDatos;

    lista.innerHTML = "";

    if (!tieneDatos) {
        return;
    }


    /*==================================================*
    *=              CREAR REGISTROS                    =*
    *==================================================*/

    registros.forEach(
        (registro) => {
            const item =
                document.createElement(
                    "article",
                );

            item.className =
                "ficha-historial__item";

            const version =
                registro.version?.trim()
                    || "—";

            const descripcion =
                registro.descripcion?.trim()
                    || "Sin descripción.";

            const fecha =
                registro.fecha?.trim()
                    || "—";

            item.innerHTML = `
                <span>
                    Versión ${escaparHtml(version)}
                </span>

                <strong>
                    ${escaparHtml(descripcion)}
                </strong>

                <small>
                    ${escaparHtml(fecha)}
                </small>
            `;

            lista.appendChild(
                item,
            );
        },
    );
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