import { mostrarNotificacion } from "../notificaciones.js";

/*
 * ==================================================
 * PETICIONES AL BACKEND
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
      resultado.mensaje ||
        "No fue posible desactivar el proyecto.",
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
      resultado.mensaje ||
        "No fue posible activar el proyecto.",
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
      resultado.mensaje ||
        "No fue posible eliminar el proyecto.",
    );
  }

  return resultado;
}

/*
 * ==================================================
 * MODALES
 * ==================================================
 */

function mostrarModal(modal) {
  if (!modal) {
    return;
  }

  modal.classList.add("modal--visible");
  modal.setAttribute("aria-hidden", "false");

  document.body.style.overflow = "hidden";
}

function cerrarModal(modal) {
  if (!modal) {
    return;
  }

  const botonCerrar = modal.querySelector(
    "[data-modal-cerrar]",
  );

  if (botonCerrar) {
    botonCerrar.click();
  }
}

function cerrarModalEliminarProyecto() {
  const modal = document.getElementById(
    "modal-eliminar-proyecto",
  );

  cerrarModal(modal);
}

function esperarActualizacionInterfaz() {
  return new Promise((resolve) => {
    requestAnimationFrame(() => {
      requestAnimationFrame(resolve);
    });
  });
}

/*
 * ==================================================
 * TABLA
 * ==================================================
 */

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

/*
 * ==================================================
 * MODAL DE CONFIRMACIÓN
 * ==================================================
 */

function limpiarEstiloBotonConfirmar(botonConfirmar) {
  if (!botonConfirmar) {
    return;
  }

  botonConfirmar.classList.remove(
    "boton--primario",
    "boton--advertencia",
    "boton--peligro",
  );
}

function abrirModalConfirmacion({
  accion,
  idProyecto,
  nombreProyecto,
}) {
  const modalConfirmacion = document.getElementById(
    "modal-confirmar-accion-proyecto",
  );

  if (!modalConfirmacion) {
    return;
  }

  const titulo = modalConfirmacion.querySelector(
    "[data-confirmacion-titulo]",
  );

  const mensaje = modalConfirmacion.querySelector(
    "[data-confirmacion-mensaje]",
  );

  const botonConfirmar = modalConfirmacion.querySelector(
    "[data-confirmar-accion-proyecto]",
  );

  modalConfirmacion.dataset.accion = accion;
  modalConfirmacion.dataset.proyectoId = idProyecto;
  modalConfirmacion.dataset.proyectoNombre = nombreProyecto;

  limpiarEstiloBotonConfirmar(botonConfirmar);

  /*
   * DESACTIVAR
   */
  if (accion === "desactivar") {
    if (titulo) {
      titulo.textContent = "Confirmar desactivación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas desactivar el proyecto "${nombreProyecto}"? ` +
        "El proyecto conservará su información y podrá reactivarse posteriormente.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent = "Desactivar";
      botonConfirmar.classList.add(
        "boton--advertencia",
      );
    }
  }

  /*
   * ACTIVAR
   */
  if (accion === "activar") {
    if (titulo) {
      titulo.textContent = "Confirmar activación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas activar nuevamente el proyecto "${nombreProyecto}"? ` +
        "El proyecto volverá a estar disponible como activo.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent = "Activar";
      botonConfirmar.classList.add(
        "boton--primario",
      );
    }
  }

  /*
   * ELIMINAR
   */
  if (accion === "eliminar") {
    if (titulo) {
      titulo.textContent = "Confirmar eliminación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas eliminar definitivamente el proyecto "${nombreProyecto}"? ` +
        "Esta acción no se podrá deshacer.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent = "Eliminar";
      botonConfirmar.classList.add(
        "boton--peligro",
      );
    }
  }

  mostrarModal(modalConfirmacion);
}

/*
 * ==================================================
 * INICIALIZACIÓN
 * ==================================================
 */

export function inicializarFormularioEliminar() {
  const modal = document.getElementById(
    "modal-eliminar-proyecto",
  );

  if (!modal) {
    return;
  }

  /*
   * ==================================================
   * CARGAR EL PROYECTO AL ABRIR EL PRIMER MODAL
   * ==================================================
   */

  document.addEventListener("click", async (event) => {
    const botonAccion = event.target.closest(
      '[data-accion="eliminar-desactivar"][data-proyecto-id]',
    );

    if (!botonAccion) {
      return;
    }

    const idProyecto =
      botonAccion.dataset.proyectoId;

    if (!idProyecto) {
      return;
    }

    try {
      const proyecto =
        await cargarProyecto(idProyecto);

      modal.dataset.proyectoId = idProyecto;
      modal.dataset.proyectoNombre =
        proyecto.nombre ?? "";

      const estaActivo =
        proyecto.activo ?? true;

      modal.dataset.proyectoActivo =
        String(estaActivo);

      const botonEstado = modal.querySelector(
        "[data-boton-estado-proyecto]",
      );

      if (botonEstado) {
        if (estaActivo) {
          botonEstado.dataset.proyectoAccion =
            "desactivar";

          botonEstado.textContent =
            "Desactivar";
        } else {
          botonEstado.dataset.proyectoAccion =
            "activar";

          botonEstado.textContent =
            "Activar";
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
   * ==================================================
   * PRIMER MODAL:
   * ACTIVAR / DESACTIVAR / ELIMINAR
   * ==================================================
   */

  document.addEventListener("click", async (event) => {
    const botonAccionProyecto =
      event.target.closest(
        "[data-proyecto-accion]",
      );

    if (!botonAccionProyecto) {
      return;
    }

    const accion =
      botonAccionProyecto.dataset.proyectoAccion;

    const idProyecto =
      modal.dataset.proyectoId;

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

    if (
      accion !== "activar" &&
      accion !== "desactivar" &&
      accion !== "eliminar"
    ) {
      return;
    }

    cerrarModalEliminarProyecto();

    await esperarActualizacionInterfaz();

    abrirModalConfirmacion({
      accion,
      idProyecto,
      nombreProyecto,
    });
  });

  /*
   * ==================================================
   * SEGUNDO MODAL:
   * CONFIRMAR LA ACCIÓN
   * ==================================================
   */

  document.addEventListener("click", async (event) => {
    const botonConfirmar =
      event.target.closest(
        "[data-confirmar-accion-proyecto]",
      );

    if (!botonConfirmar) {
      return;
    }

    const modalConfirmacion =
      document.getElementById(
        "modal-confirmar-accion-proyecto",
      );

    if (!modalConfirmacion) {
      return;
    }

    const accion =
      modalConfirmacion.dataset.accion;

    const idProyecto =
      modalConfirmacion.dataset.proyectoId;

    if (!accion || !idProyecto) {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo continuar",
        mensaje:
          "Faltan datos para realizar la acción.",
      });

      return;
    }

    botonConfirmar.disabled = true;

    try {
      /*
       * DESACTIVAR
       */
      if (accion === "desactivar") {
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
      }

      /*
       * ACTIVAR
       */
      if (accion === "activar") {
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
      }

      /*
       * ELIMINAR
       */
      if (accion === "eliminar") {
        const resultado =
          await eliminarProyecto(idProyecto);

        const filaEliminada =
          eliminarFilaProyecto(idProyecto);

        if (!filaEliminada) {
          console.warn(
            `El proyecto ${idProyecto} fue eliminado, ` +
            "pero no se encontró su fila en la tabla.",
          );
        }

        mostrarNotificacion({
          tipo: "success",
          titulo: "Proyecto eliminado",
          mensaje: resultado.mensaje,
        });
      }

      cerrarModal(modalConfirmacion);

      modalConfirmacion.removeAttribute(
        "data-accion",
      );

      modalConfirmacion.removeAttribute(
        "data-proyecto-id",
      );

      modalConfirmacion.removeAttribute(
        "data-proyecto-nombre",
      );
    } catch (error) {
      console.error(
        "Error al ejecutar la acción del proyecto:",
        error,
      );

      mostrarNotificacion({
        tipo: "error",
        titulo:
          "No se pudo realizar la acción",
        mensaje: error.message,
      });
    } finally {
      botonConfirmar.disabled = false;
    }
  });
}