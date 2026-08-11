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

    try {
      const proyecto =
        await cargarProyecto(idProyecto);

      modal.dataset.proyectoId = idProyecto;
      modal.dataset.proyectoNombre =
        proyecto.nombre ?? "";

      const nombreProyecto = modal.querySelector(
        "[data-sistemas-proyecto-nombre]",
      );

      if (nombreProyecto) {
        nombreProyecto.textContent =
          proyecto.nombre ?? "Proyecto";
      }

      const contenido = modal.querySelector(
        "[data-sistemas-asociados-contenido]",
      );

      if (contenido) {
        contenido.innerHTML = `
          <p>
            Los sistemas asociados al proyecto se cargarán aquí.
          </p>
        `;
      }
    } catch (error) {
      console.error(
        "Error al cargar sistemas asociados:",
        error,
      );

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: error.message,
      });
    }
  });
}