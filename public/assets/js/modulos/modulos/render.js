/*==================================================*
 *=              RENDER DE MÓDULOS                  =*
 *==================================================*/

export function renderModulos({
    modulos,
    contenedor,
}) {

    if (!contenedor) {
        return;
    }

    const lista =
        Array.isArray(modulos)
            ? modulos
            : [];

    contenedor.innerHTML = "";

    if (lista.length === 0) {

        contenedor.innerHTML = `
            <div class="estado-vacio">
                No hay módulos registrados para este sistema.
            </div>
        `;

        return;
    }

    contenedor.innerHTML =
        lista
            .map(
                (modulo) =>
                    crearTarjetaModulo(
                        modulo,
                    ),
            )
            .join("");
}


/*==================================================*
 *=              CREAR TARJETA                      =*
 *==================================================*/

function crearTarjetaModulo(
    modulo,
) {

    const tipo =
        escaparHtml(
            modulo.tipo ||
            "Módulo",
        );

    const nombre =
        escaparHtml(
            modulo.nombre ||
            "Módulo sin nombre",
        );

    const descripcion =
        escaparHtml(
            modulo.descripcion ||
            "Sin descripción.",
        );

    const url =
        String(
            modulo.url ?? "",
        ).trim();

    const imagen =
        String(
            modulo.imagen ?? "",
        ).trim();

    const activo =
        Boolean(
            modulo.activo ?? true,
        );


    return `
        <article
            class="modulo-card"

            data-modulo-id="${escaparHtml(
                modulo.id_modulo ?? "",
            )}"

            data-modulo-tipo="${escaparHtml(
                modulo.tipo ?? "",
            )}"

            data-modulo-nombre="${escaparHtml(
                modulo.nombre ?? "",
            )}"

            data-modulo-descripcion="${escaparHtml(
                modulo.descripcion ?? "",
            )}"

            data-modulo-url="${escaparHtml(
                url,
            )}"

            data-modulo-imagen="${escaparHtml(
                imagen,
            )}"

            data-modulo-activo="${
                activo
                    ? "1"
                    : "0"
            }"
        >


            <!--==========================================
            =              VISTA PREVIA                 =
            ===========================================-->

            <div class="modulo-card__imagen">

                ${
                    imagen
                        ? `
                            <img
                                src="${escaparHtml(
                                    imagen,
                                )}"
                                alt="Vista previa de ${nombre}"
                                class="modulo-card__imagen-archivo"
                            >
                        `
                        : `
                            <span class="modulo-card__imagen-vacia">
                                Vista previa
                            </span>
                        `
                }

            </div>


            <!--==========================================
            =                 CONTENIDO                  =
            ===========================================-->

            <div class="modulo-card__contenido">


                <!--======================================
                =          TIPO Y ESTADO                =
                =======================================-->

                <div class="modulo-card__encabezado">

                    <span class="modulo-card__tipo">
                        ${tipo}
                    </span>


                    <button
                        type="button"

                        class="
                            modulo-card__estado-toggle
                            ${
                                activo
                                    ? "modulo-card__estado-toggle--activo"
                                    : "modulo-card__estado-toggle--inactivo"
                            }
                        "

                        data-modulo-estado

                        data-activo="${
                            activo
                                ? "1"
                                : "0"
                        }"

                        aria-pressed="${
                            activo
                                ? "true"
                                : "false"
                        }"

                        aria-label="${
                            activo
                                ? "Desactivar módulo"
                                : "Activar módulo"
                        }"

                        title="${
                            activo
                                ? "Activo"
                                : "Inactivo"
                        }"
                    >

                        <span
                            class="modulo-card__estado-toggle-punto"
                            aria-hidden="true"
                        ></span>

                    </button>

                </div>


                <!--======================================
                =              INFORMACIÓN              =
                =======================================-->

                <h3>
                    ${nombre}
                </h3>

                <p>
                    ${descripcion}
                </p>


                <!--======================================
                =                ACCIONES               =
                =======================================-->

                <div class="modulo-card__acciones">

                    <button
                        type="button"
                        class="modulo-card__detalle"
                        data-ver-modulo
                    >
                        Ver ficha
                    </button>


                    <a
                        href="${
                            url
                                ? escaparHtml(url)
                                : "#"
                        }"

                        class="
                            modulo-card__abrir
                            ${
                                url
                                    ? ""
                                    : "modulo-card__abrir--deshabilitado"
                            }
                        "

                        ${
                            url
                                ? 'target="_blank" rel="noopener noreferrer"'
                                : 'aria-disabled="true" tabindex="-1"'
                        }

                        title="${
                            url
                                ? "Abrir módulo"
                                : "URL no disponible"
                        }"
                    >
                        Abrir
                    </a>

                </div>

            </div>

        </article>
    `;
}


/*==================================================*
 *=                ESCAPAR HTML                     =*
 *==================================================*/

function escaparHtml(
    valor,
) {

    const elemento =
        document.createElement(
            "div",
        );

    elemento.textContent =
        valor ?? "";

    return elemento.innerHTML;
}