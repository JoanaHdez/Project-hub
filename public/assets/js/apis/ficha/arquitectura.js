import {
    mostrarNotificacion,
} from "../../proyectos/notificaciones.js";

/*==================================================*
*=              ARQUITECTURA DE API                 =*
*==================================================*/

export function inicializarArquitecturaFicha() {
    const botonCompletar =
        document.getElementById(
            "btn-completar-arquitectura",
        );

    const modal =
        document.getElementById(
            "modal-arquitectura-api",
        );

    const botonAgregar =
        document.getElementById(
            "btn-agregar-componente-arquitectura",
        );

    const contenedor =
        document.getElementById(
            "arquitectura-componentes",
        );

    const estadoVacio =
        document.getElementById(
            "arquitectura-componentes-vacio",
        );

    const formulario =
        document.getElementById(
            "form-arquitectura-api",
        );

    if (
        !botonCompletar ||
        !modal ||
        !botonAgregar ||
        !contenedor ||
        !estadoVacio
    ) {
        return;
    }


    /*==================================================*
    *=              ABRIR MODAL                        =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            modal.classList.add(
                "modal--visible",
            );

            modal.setAttribute(
                "aria-hidden",
                "false",
            );

            document.body.style.overflow =
                "hidden";
        },
    );


    /*==================================================*
    *=            AGREGAR COMPONENTE                   =*
    *==================================================*/

    botonAgregar.addEventListener(
        "click",
        () => {
            agregarComponente(
                contenedor,
                estadoVacio,
            );
        },
    );


    /*==================================================*
    *=            ELIMINAR COMPONENTE                  =*
    *==================================================*/

    contenedor.addEventListener(
        "click",
        (event) => {
            const botonEliminar =
                event.target.closest(
                    "[data-eliminar-componente-arquitectura]",
                );

            if (!botonEliminar) {
                return;
            }

            const item =
                botonEliminar.closest(
                    "[data-componente-arquitectura]",
                );

            if (item) {
                item.remove();
            }

            actualizarEstadoVacio(
                contenedor,
                estadoVacio,
            );
        },
    );

    /*==================================================*
*=              GUARDAR ARQUITECTURA                =*
*==================================================*/

    formulario?.addEventListener(
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

                renderArquitectura(
                    resultado.arquitectura,
                );

                cerrarModalArquitectura(
                    modal,
                );

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
*=            CREAR COMPONENTE                      =*
*==================================================*/

function agregarComponente(
    contenedor,
    estadoVacio,
) {
    const item =
        document.createElement(
            "div",
        );

    item.className =
        "form-api-documentacion__item";

    item.dataset.componenteArquitectura =
        "";

    item.innerHTML = `
    <div class="form-grid">

      <div class="form-grupo">

        <label>
          Tipo
        </label>

        <select
          data-arquitectura-tipo
        >
          <option value="">
            Selecciona un tipo
          </option>

          <option value="Controllers">
            Controllers
          </option>

          <option value="Services">
            Services
          </option>

          <option value="Models">
            Models
          </option>

          <option value="Views">
            Views
          </option>

          <option value="Libraries">
            Libraries
          </option>

          <option value="Helpers">
            Helpers
          </option>

          <option value="Config">
            Config
          </option>

          <option value="Routes">
            Routes
          </option>

          <option value="Otros">
            Otros
          </option>
        </select>

      </div>


      <div class="form-grupo">

        <label>
          Archivo / componente
        </label>

        <input
          type="text"
          data-arquitectura-archivo
          placeholder="Ej. ConstanciaAPI_Controller.php"
        >

      </div>

    </div>


    <div class="form-api-documentacion__acciones">

      <button
        type="button"
        class="boton boton--peligro boton--sm"
        data-eliminar-componente-arquitectura
      >
        Eliminar
      </button>

    </div>
  `;

    contenedor.appendChild(
        item,
    );

    actualizarEstadoVacio(
        contenedor,
        estadoVacio,
    );
}


/*==================================================*
*=              ESTADO VACÍO                        =*
*==================================================*/

function actualizarEstadoVacio(
    contenedor,
    estadoVacio,
) {
    const existenComponentes =
        contenedor.querySelector(
            "[data-componente-arquitectura]",
        ) !== null;

    estadoVacio.hidden =
        existenComponentes;
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
*=              CERRAR MODAL                        =*
*==================================================*/

function cerrarModalArquitectura(
    modal,
) {
    if (
        document.activeElement
        instanceof HTMLElement
    ) {
        document.activeElement.blur();
    }

    modal.classList.remove(
        "modal--visible",
    );

    modal.setAttribute(
        "aria-hidden",
        "true",
    );

    document.body.style.overflow =
        "";
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