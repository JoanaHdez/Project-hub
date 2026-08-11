import { mostrarNotificacion } from "./notificaciones.js";

async function cargarProyecto(idProyecto) {
  const respuesta = await fetch(`/proyectos/${idProyecto}`, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  });

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje || "No fue posible cargar el proyecto.",
    );
  }

  return resultado.proyecto;
}

async function cargarSistemasProyecto(idProyecto) {
  const respuesta = await fetch(
    `/proyectos/${idProyecto}/sistemas`,
    {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    },
  );

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje ||
        "No fue posible cargar los sistemas asociados.",
    );
  }

  return resultado;
}

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

export function inicializarSistemasAsociados() {
  const modal = document.getElementById(
    "modal-sistemas-asociados",
  );

  if (!modal) {
    return;
  }

  document.addEventListener("click", async (event) => {
    const botonSistemas = event.target.closest(
      '[data-accion="sistemas"][data-proyecto-id]',
    );

    if (!botonSistemas) {
      return;
    }

    const idProyecto =
      botonSistemas.dataset.proyectoId;

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

      const [proyecto, resultadoSistemas] =
        await Promise.all([
          cargarProyecto(idProyecto),
          cargarSistemasProyecto(idProyecto),
        ]);

      /*
       * Guardamos el proyecto seleccionado en el modal.
       * Esto nos servirá después para "Agregar sistema".
       */
      modal.dataset.proyectoId = idProyecto;
      modal.dataset.proyectoNombre =
        proyecto.nombre ?? "";

      if (nombreProyecto) {
        nombreProyecto.textContent =
          proyecto.nombre ?? "Proyecto";
      }

      if (contenido) {
        contenido.innerHTML =
          construirTablaSistemas(resultadoSistemas);
      }
    } catch (error) {
      console.error(
        "Error al cargar sistemas asociados:",
        error,
      );

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
}