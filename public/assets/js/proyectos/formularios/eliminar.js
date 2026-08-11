import { mostrarNotificacion } from "../notificaciones.js";

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

async function desactivarProyecto(idProyecto) {
  const respuesta = await fetch(
    `/proyectos/${idProyecto}/desactivar`,
    {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    },
  );

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje || "No fue posible desactivar el proyecto.",
    );
  }

  return resultado;
}

async function activarProyecto(idProyecto) {
  const respuesta = await fetch(
    `/proyectos/${idProyecto}/activar`,
    {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    },
  );

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje || "No fue posible activar el proyecto.",
    );
  }

  return resultado;
}

async function eliminarProyecto(idProyecto) {
  const respuesta = await fetch(`/proyectos/${idProyecto}`, {
    method: "DELETE",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  });

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje || "No fue posible eliminar el proyecto.",
    );
  }

  return resultado;
}

function cerrarModalEliminarProyecto() {
  const modal = document.getElementById("modal-eliminar-proyecto");

  if (!modal) {
    return;
  }

  const botonCerrar = modal.querySelector("[data-modal-cerrar]");

  if (botonCerrar) {
    botonCerrar.click();
  }
}

function esperarActualizacionInterfaz() {
  return new Promise((resolve) => {
    requestAnimationFrame(() => {
      requestAnimationFrame(resolve);
    });
  });
}

function obtenerFilaProyecto(idProyecto) {
  return document.querySelector(
    `tr[data-proyecto-id="${idProyecto}"]`,
  );
}

function eliminarFilaProyecto(idProyecto) {
  const fila = obtenerFilaProyecto(idProyecto);

  if (!fila) {
    return false;
  }

  fila.remove();

  return true;
}

export function inicializarFormularioEliminar() {
  const modal = document.getElementById(
    "modal-eliminar-proyecto",
  );

  if (!modal) {
    return;
  }

  /*
   * ------------------------------------------------
   * CARGAR PROYECTO AL ABRIR EL MODAL
   * ------------------------------------------------
   */
  document.addEventListener("click", async (event) => {
    const botonAccion = event.target.closest(
      '[data-accion="eliminar-desactivar"][data-proyecto-id]',
    );

    if (!botonAccion) {
      return;
    }

    const idProyecto = botonAccion.dataset.proyectoId;

    if (!idProyecto) {
      return;
    }

    try {
      const proyecto = await cargarProyecto(idProyecto);

      modal.dataset.proyectoId = idProyecto;
      modal.dataset.proyectoNombre =
        proyecto.nombre ?? "";

      const estaActivo = proyecto.activo ?? true;

      modal.dataset.proyectoActivo = String(estaActivo);

      const botonEstado = modal.querySelector(
        "[data-boton-estado-proyecto]",
      );

      if (botonEstado) {
        if (estaActivo) {
          botonEstado.dataset.proyectoAccion =
            "desactivar";

          botonEstado.textContent = "Desactivar";
        } else {
          botonEstado.dataset.proyectoAccion =
            "activar";

          botonEstado.textContent = "Activar";
        }
      }

      const nombreProyecto = modal.querySelector(
        "[data-eliminar-proyecto-nombre]",
      );

      if (nombreProyecto) {
        nombreProyecto.textContent =
          `"${proyecto.nombre ?? "Proyecto"}"`;
      }
    } catch (error) {
      console.error(
        "Error al cargar el proyecto para eliminar/desactivar:",
        error,
      );

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: error.message,
      });
    }
  });

  /*
   * ------------------------------------------------
   * ACTIVAR / DESACTIVAR / ELIMINAR
   * ------------------------------------------------
   */
  document.addEventListener("click", async (event) => {
    const botonAccionProyecto = event.target.closest(
      "[data-proyecto-accion]",
    );

    if (!botonAccionProyecto) {
      return;
    }

    const accion =
      botonAccionProyecto.dataset.proyectoAccion;

    const idProyecto = modal.dataset.proyectoId;

    const nombreProyecto =
      modal.dataset.proyectoNombre ||
      "el proyecto seleccionado";

    if (!idProyecto) {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo continuar",
        mensaje:
          "No se encontró el identificador del proyecto.",
      });

      return;
    }

    /*
     * --------------------------
     * DESACTIVAR
     * --------------------------
     */
    if (accion === "desactivar") {
      cerrarModalEliminarProyecto();

      await esperarActualizacionInterfaz();

      const confirmado = window.confirm(
        `¿Confirmas que deseas desactivar el proyecto "${nombreProyecto}"?\n\nEl proyecto conservará su información y podrá reactivarse posteriormente.`,
      );

      if (!confirmado) {
        return;
      }

      try {
        const resultado =
          await desactivarProyecto(idProyecto);

        const filaProyecto =
          obtenerFilaProyecto(idProyecto);

        if (filaProyecto) {
          filaProyecto.classList.add(
            "proyecto-fila--inactiva",
          );
        }

        mostrarNotificacion({
          tipo: "success",
          titulo: "Proyecto desactivado",
          mensaje: resultado.mensaje,
        });
      } catch (error) {
        console.error(
          "Error al desactivar el proyecto:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",
          titulo: "No se pudo desactivar",
          mensaje: error.message,
        });
      }

      return;
    }

    /*
     * --------------------------
     * ACTIVAR
     * --------------------------
     */
    if (accion === "activar") {
      cerrarModalEliminarProyecto();

      await esperarActualizacionInterfaz();

      const confirmado = window.confirm(
        `¿Confirmas que deseas activar nuevamente el proyecto "${nombreProyecto}"?\n\nEl proyecto volverá a estar disponible como activo.`,
      );

      if (!confirmado) {
        return;
      }

      try {
        const resultado =
          await activarProyecto(idProyecto);

        const filaProyecto =
          obtenerFilaProyecto(idProyecto);

        if (filaProyecto) {
          filaProyecto.classList.remove(
            "proyecto-fila--inactiva",
          );
        }

        mostrarNotificacion({
          tipo: "success",
          titulo: "Proyecto activado",
          mensaje: resultado.mensaje,
        });
      } catch (error) {
        console.error(
          "Error al activar el proyecto:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",
          titulo: "No se pudo activar",
          mensaje: error.message,
        });
      }

      return;
    }

    /*
     * --------------------------
     * ELIMINAR
     * --------------------------
     */
    if (accion === "eliminar") {
      cerrarModalEliminarProyecto();

      await esperarActualizacionInterfaz();

      const confirmado = window.confirm(
        `¿Confirmas que deseas eliminar definitivamente el proyecto "${nombreProyecto}"?\n\nEsta acción no se podrá deshacer.`,
      );

      if (!confirmado) {
        return;
      }

      try {
        const resultado =
          await eliminarProyecto(idProyecto);

        const filaEliminada =
          eliminarFilaProyecto(idProyecto);

        if (!filaEliminada) {
          console.warn(
            `El proyecto ${idProyecto} fue eliminado, pero no se encontró su fila en la tabla.`,
          );
        }

        mostrarNotificacion({
          tipo: "success",
          titulo: "Proyecto eliminado",
          mensaje: resultado.mensaje,
        });
      } catch (error) {
        console.error(
          "Error al eliminar el proyecto:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",
          titulo: "No se pudo eliminar",
          mensaje: error.message,
        });
      }

      return;
    }
  });
}