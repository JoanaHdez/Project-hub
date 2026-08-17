/*==================================================*
*=             RENDER DEPENDENCIAS                  =*
*==================================================*/

export function renderDependencias(
    dependencias,
) {
    const pendiente =
        document.getElementById(
            "ficha-dependencias-pendiente",
        );

    const contenido =
        document.getElementById(
            "ficha-dependencias-contenido",
        );

    const lista =
        document.getElementById(
            "ficha-dependencias-lista",
        );

    if (
        !pendiente ||
        !contenido ||
        !lista
    ) {
        return;
    }

    const dependenciasLista =
        Array.isArray(dependencias)
            ? dependencias
            : [];

    const tieneDatos =
        dependenciasLista.length > 0;

    pendiente.hidden =
        tieneDatos;

    contenido.hidden =
        !tieneDatos;

    lista.innerHTML = "";

    if (!tieneDatos) {
        return;
    }

    dependenciasLista.forEach(
        (dependencia) => {
            const articulo =
                document.createElement(
                    "article",
                );

            articulo.className =
                "ficha-dependencia";

            articulo.innerHTML = `
                <div
                    class="ficha-dependencia__icono"
                    aria-hidden="true"
                >
                    ${obtenerIconoDependencia(
                        dependencia.tipo,
                    )}
                </div>

                <div
                    class="ficha-dependencia__contenido"
                >
                    <small>
                        ${escaparHtml(
                            dependencia.tipo || "Otro",
                        )}
                    </small>

                    <strong>
                        ${escaparHtml(
                            dependencia.nombre || "—",
                        )}
                    </strong>

                    <span>
                        ${escaparHtml(
                            dependencia.descripcion ||
                            "Sin descripción.",
                        )}
                    </span>
                </div>

                <span
                    class="ficha-dependencia__estado"
                >
                    ${escaparHtml(
                        dependencia.estado || "—",
                    )}
                </span>
            `;

            lista.appendChild(
                articulo,
            );
        },
    );
}


/*==================================================*
*=          ICONO POR TIPO                          =*
*==================================================*/

function obtenerIconoDependencia(
    tipo,
) {
    const iconos = {
        "Base de datos": "🗄️",
        "Servicio de correo": "✉️",
        "Framework": "⚙️",
        "Autenticación": "🔑",
        "Configuración": "📄",
        "Servicio interno": "🧩",
        "Otro": "🔗",
    };

    return iconos[tipo] ?? "🔗";
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