import { mostrarNotificacion } from "./notificaciones.js";

/*
 * ==================================================
 * PETICIONES
 * ==================================================
 */

async function cargarProyecto(idProyecto) {
    const respuesta = await fetch(`/proyectos/${idProyecto}`, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    });

    const resultado = await respuesta.json();

    if (!respuesta.ok || !resultado.ok) {
        throw new Error(resultado.mensaje || "No fue posible cargar el proyecto.");
    }

    return resultado.proyecto;
}

async function cargarSistemasProyecto(idProyecto) {
    const respuesta = await fetch(`/proyectos/${idProyecto}/sistemas`, {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    });

    const resultado = await respuesta.json();

    if (!respuesta.ok || !resultado.ok) {
        throw new Error(
            resultado.mensaje || "No fue posible cargar los sistemas asociados.",
        );
    }

    return resultado;
}

/*
 * ==================================================
 * TABLA DE SISTEMAS
 * ==================================================
 */

function construirTablaSistemas(resultadoSistemas) {
    if (resultadoSistemas.total === 0) {
        return `
      <div class="sistemas-asociados__vacio">
        <span>🖥️</span>

        <strong>
          No hay sistemas registrados
        </strong>

        <p>
          Este proyecto todavía no tiene sistemas asociados.
        </p>
      </div>
    `;
    }

    const filas = resultadoSistemas.sistemas
        .map(
            (sistema) => `
      <tr data-sistema-id="${sistema.id_sistema}">
        <td>
          ${sistema.nombre ?? "Sistema sin nombre"}
        </td>

        <td>
          ${sistema.tipo ?? "Sistema"}
        </td>

        <td>
          <span
            class="estado estado--${sistema.estado_tipo ?? "inactivo"}"
          >
            ${sistema.estado ?? "Sin estado"}
          </span>
        </td>

        <td>
          <div class="acciones-proyecto">

            <button
              type="button"
              class="boton-accion"
              data-accion="detalle-sistema"
              data-sistema-id="${sistema.id_sistema}"
            >
              <span class="boton-accion__icono">
                📄
              </span>

              <span class="boton-accion__mensaje">
                Ver detalle del sistema
              </span>
            </button>

            <button
              type="button"
              class="boton-accion boton-accion--editar"
              data-accion="editar-sistema"
              data-sistema-id="${sistema.id_sistema}"
            >
              <span class="boton-accion__icono">
                ✏️
              </span>

              <span class="boton-accion__mensaje">
                Editar sistema
              </span>
            </button>

            <button
              type="button"
              class="boton-accion boton-accion--eliminar"
              data-accion="eliminar-sistema"
              data-sistema-id="${sistema.id_sistema}"
            >
              <span class="boton-accion__icono">
                🗑️
              </span>

              <span class="boton-accion__mensaje">
                Administrar sistema
              </span>
            </button>

          </div>
        </td>
      </tr>
    `,
        )
        .join("");

    return `
    <div class="tabla-componente">

      <div class="tabla-contenedor">

        <table class="tabla">

        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

          <tbody>
            ${filas}
          </tbody>

        </table>

      </div>

      <div class="tabla-pie">

        <p class="tabla-pie__contador">
          Mostrando
          <strong>1</strong>
          a
          <strong>${resultadoSistemas.total}</strong>
          de
          <strong>${resultadoSistemas.total}</strong>
          registros
        </p>

      </div>

    </div>
  `;
}

/*
 * ==================================================
 * MODALES
 * ==================================================
 */

function cerrarModal(modal) {
    if (!modal) {
        return;
    }

    const botonCerrar = modal.querySelector("[data-modal-cerrar]");

    if (botonCerrar) {
        botonCerrar.click();
    }
}

function mostrarModal(modal) {
    if (!modal) {
        return;
    }

    modal.classList.add("modal--visible");
    modal.setAttribute("aria-hidden", "false");

    document.body.style.overflow = "hidden";
}

function esperarActualizacionInterfaz() {
    return new Promise((resolve) => {
        requestAnimationFrame(() => {
            requestAnimationFrame(resolve);
        });
    });
}

function abrirModalNuevoSistema({ idProyecto, nombreProyecto }) {
    const modalNuevo = document.getElementById("modal-nuevo-sistema");

    if (!modalNuevo) {
        mostrarNotificacion({
            tipo: "error",
            titulo: "No se pudo abrir",
            mensaje: "No se encontró el formulario para registrar un sistema.",
        });

        return;
    }

    const formulario = modalNuevo.querySelector("#form-nuevo-sistema");

    if (!formulario) {
        mostrarNotificacion({
            tipo: "error",
            titulo: "No se pudo abrir",
            mensaje: "No se encontró el formulario de nuevo sistema.",
        });

        return;
    }

    /*
     * Limpiar cualquier dato de una apertura anterior.
     */
    formulario.reset();

    /*
     * Asignar automáticamente el proyecto.
     */
    const campoIdProyecto = formulario.elements.namedItem("id_proyecto");

    const campoNombreProyecto = formulario.elements.namedItem("proyecto_nombre");

    if (campoIdProyecto) {
        campoIdProyecto.value = idProyecto;
    }

    if (campoNombreProyecto) {
        campoNombreProyecto.value = nombreProyecto;
    }

    /*
     * Guardamos también el proyecto en el modal.
     * Nos servirá cuando implementemos el POST.
     */
    modalNuevo.dataset.proyectoId = idProyecto;
    modalNuevo.dataset.proyectoNombre = nombreProyecto;

    mostrarModal(modalNuevo);
}

/*
 * ==================================================
 * INICIALIZACIÓN
 * ==================================================
 */

export function inicializarSistemasAsociados() {
    const modal = document.getElementById("modal-sistemas-asociados");

    if (!modal) {
        return;
    }

    /*
     * ==================================================
     * ABRIR SISTEMAS ASOCIADOS
     * ==================================================
     */

    document.addEventListener("click", async (event) => {
        const botonSistemas = event.target.closest(
            '[data-accion="sistemas"][data-proyecto-id]',
        );

        if (!botonSistemas) {
            return;
        }

        const idProyecto = botonSistemas.dataset.proyectoId;

        if (!idProyecto) {
            return;
        }

        const nombreProyecto = modal.querySelector(
            "[data-sistemas-proyecto-nombre]",
        );

        const contenido = modal.querySelector(
            "[data-sistemas-asociados-contenido]",
        );

        try {
            if (contenido) {
                contenido.innerHTML = `
          <p>
            Cargando sistemas asociados...
          </p>
        `;
            }

            const [proyecto, resultadoSistemas] = await Promise.all([
                cargarProyecto(idProyecto),
                cargarSistemasProyecto(idProyecto),
            ]);

            /*
             * Guardar el proyecto seleccionado.
             */
            modal.dataset.proyectoId = idProyecto;
            modal.dataset.proyectoNombre = proyecto.nombre ?? "";

            /*
             * Mostrar nombre del proyecto.
             */
            if (nombreProyecto) {
                nombreProyecto.textContent = proyecto.nombre ?? "Proyecto";
            }

            /*
             * Mostrar sistemas asociados.
             */
            if (contenido) {
                contenido.innerHTML = construirTablaSistemas(resultadoSistemas);
            }
        } catch (error) {
            console.error("Error al cargar sistemas asociados:", error);

            if (contenido) {
                contenido.innerHTML = `
          <div class="sistemas-asociados__vacio">
            <strong>
              No fue posible cargar los sistemas
            </strong>

            <p>
              Intenta nuevamente.
            </p>
          </div>
        `;
            }

            mostrarNotificacion({
                tipo: "error",
                titulo: "No se pudo cargar",
                mensaje: error.message,
            });
        }
    });

    /*
     * ==================================================
     * AGREGAR SISTEMA AL PROYECTO ACTUAL
     * ==================================================
     */

    document.addEventListener("click", async (event) => {
        const botonAgregar = event.target.closest(
            "[data-agregar-sistema-proyecto]",
        );

        if (!botonAgregar) {
            return;
        }

        const idProyecto = modal.dataset.proyectoId;

        const nombreProyecto = modal.dataset.proyectoNombre ?? "";

        if (!idProyecto) {
            mostrarNotificacion({
                tipo: "error",
                titulo: "No se pudo continuar",
                mensaje: "No se encontró el proyecto seleccionado.",
            });

            return;
        }

        /*
         * Primero cerramos Sistemas asociados.
         */
        cerrarModal(modal);

        /*
         * Esperamos a que termine visualmente el cierre.
         */
        await esperarActualizacionInterfaz();

        /*
         * Después abrimos Nuevo sistema.
         */
        abrirModalNuevoSistema({
            idProyecto,
            nombreProyecto,
        });
    });
}
