import { mostrarNotificacion } from "../../proyectos/notificaciones.js";

function obtenerDatosFormulario(formulario) {
  const datosFormulario = new FormData(formulario);

  return {
    id_proyecto: Number(datosFormulario.get("id_proyecto") ?? 0),

    nombre: String(datosFormulario.get("nombre") ?? "").trim(),

    estado: String(datosFormulario.get("estado") ?? ""),

    tipo: String(datosFormulario.get("tipo") ?? ""),

    modo_visualizacion: String(datosFormulario.get("modo_visualizacion") ?? ""),

    descripcion: String(datosFormulario.get("descripcion") ?? "").trim(),

    url: String(datosFormulario.get("url") ?? "").trim(),

    repositorio_url: String(
      datosFormulario.get("repositorio_url") ?? "",
    ).trim(),

    ruta_local: String(datosFormulario.get("ruta_local") ?? "").trim(),

    url_servidor: String(datosFormulario.get("url_servidor") ?? "").trim(),

    responsable: String(datosFormulario.get("responsable") ?? "").trim(),

    observaciones: String(datosFormulario.get("observaciones") ?? "").trim(),
  };
}

async function guardarSistema(sistema) {
  const respuesta = await fetch("/sistemas", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify(sistema),
  });

  const contenido = await respuesta.text();

  let resultado;

  try {
    resultado = JSON.parse(contenido);
  } catch (error) {
    throw new Error(
      `El servidor respondió con código ${respuesta.status}, pero no devolvió JSON.`,
    );
  }

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje || "No fue posible registrar el sistema.",
    );
  }

  return resultado;
}

function cerrarModalNuevoSistema() {
  const modal = document.getElementById("modal-nuevo-sistema");

  if (!modal) {
    return;
  }

  const botonCerrar = modal.querySelector("[data-modal-cerrar]");

  if (botonCerrar) {
    botonCerrar.click();
  }
}

async function recargarSistemasAsociados(idProyecto) {
  const modal = document.getElementById("modal-sistemas-asociados");

  if (!modal) {
    return;
  }

  const contenido = modal.querySelector("[data-sistemas-asociados-contenido]");

  if (!contenido) {
    return;
  }

  const respuesta = await fetch(`/proyectos/${idProyecto}/sistemas`, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  });

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    return;
  }

  if (resultado.total === 0) {
    contenido.innerHTML = `
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

    return;
  }

  const filas = resultado.sistemas
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

  contenido.innerHTML = `
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
          <strong>${resultado.total}</strong>
          de
          <strong>${resultado.total}</strong>
          registros
        </p>

      </div>

    </div>
  `;
}

function actualizarTotalSistemasProyecto(idProyecto, totalSistemas) {
  const filaProyecto = document.querySelector(
    `tr[data-proyecto-id="${idProyecto}"]`,
  );

  if (!filaProyecto) {
    return;
  }

  const celdaTotal = filaProyecto.querySelector("[data-total-sistemas]");

  if (!celdaTotal) {
    return;
  }

  celdaTotal.textContent = totalSistemas;
}

export function inicializarFormularioNuevoSistema() {
  const formulario = document.getElementById("form-nuevo-sistema");

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (!formulario.checkValidity()) {
      formulario.reportValidity();
      return;
    }

    const sistema = obtenerDatosFormulario(formulario);

    if (!sistema.id_proyecto) {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo registrar",
        mensaje: "No se encontró el proyecto asociado.",
      });

      return;
    }

    try {
      const resultado = await guardarSistema(sistema);

      actualizarTotalSistemasProyecto(
        sistema.id_proyecto,
        resultado.total_sistemas,
      );

      cerrarModalNuevoSistema();

      mostrarNotificacion({
        tipo: "success",
        titulo: "Sistema registrado",
        mensaje: resultado.mensaje,
      });

      await recargarSistemasAsociados(sistema.id_proyecto);

      formulario.reset();
    } catch (error) {
      console.error("Error al registrar el sistema:", error);

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo registrar",
        mensaje: error.message,
      });
    }
  });
}
