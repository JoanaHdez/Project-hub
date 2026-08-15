/*==================================================*
*=             RENDER ARQUITECTURA                  =*
*==================================================*/

export function renderArquitectura(
    arquitectura,
) {
    const pendiente =
        document.getElementById(
            "ficha-arquitectura-pendiente",
        );

    const contenido =
        document.getElementById(
            "ficha-arquitectura-contenido",
        );

    const modulo =
        document.getElementById(
            "ficha-arquitectura-modulo",
        );

    const grupos =
        document.getElementById(
            "ficha-arquitectura-grupos",
        );

    if (
        !pendiente ||
        !contenido ||
        !modulo ||
        !grupos
    ) {
        return;
    }

    const componentes =
        Array.isArray(
            arquitectura?.componentes,
        )
            ? arquitectura.componentes
            : [];

    const tieneDatos =
        Boolean(
            arquitectura?.modulo?.trim(),
        ) ||
        componentes.length > 0;

    pendiente.hidden =
        tieneDatos;

    contenido.hidden =
        !tieneDatos;

    if (!tieneDatos) {
        return;
    }

    modulo.textContent =
        arquitectura.modulo || "—";

    grupos.innerHTML = "";

    const agrupados = {};

    componentes.forEach(
        (componente) => {
            const tipo =
                componente.tipo || "Otros";

            if (!agrupados[tipo]) {
                agrupados[tipo] = [];
            }

            agrupados[tipo].push(
                componente,
            );
        },
    );

    Object.entries(
        agrupados,
    ).forEach(
        ([tipo, archivos]) => {
            const grupo =
                document.createElement(
                    "div",
                );

            grupo.className =
                "ficha-arbol__grupo";

            const archivosHtml =
                archivos
                    .map(
                        (archivo) => `
                            <div class="ficha-arbol__archivo">

                                <span
                                    class="ficha-arbol__linea"
                                    aria-hidden="true"
                                ></span>

                                <span
                                    class="ficha-arbol__icono"
                                    aria-hidden="true"
                                >
                                    📄
                                </span>

                                <code>
                                    ${escaparHtml(
                                        archivo.archivo || "—",
                                    )}
                                </code>

                            </div>
                        `,
                    )
                    .join("");

            grupo.innerHTML = `
                <div class="ficha-arbol__carpeta">

                    <span
                        class="ficha-arbol__icono"
                        aria-hidden="true"
                    >
                        📂
                    </span>

                    <strong>
                        ${escaparHtml(tipo)}
                    </strong>

                </div>

                ${archivosHtml}
            `;

            grupos.appendChild(
                grupo,
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