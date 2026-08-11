import { mostrarNotificacion } from "../../proyectos/notificaciones.js";

/*==================================================*
*=              PETICIONES AL BACKEND               =*
*==================================================*/

async function cargarSistema(idSistema) {
  const respuesta = await fetch(`/sistemas/${idSistema}`, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  });

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje ||
        "No fue posible cargar el sistema.",
    );
  }

  return resultado.sistema;
}

async function desactivarSistema(idSistema) {
  const respuesta = await fetch(
    `/sistemas/${idSistema}/desactivar`,
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
        "No fue posible desactivar el sistema.",
    );
  }

  return resultado;
}

async function activarSistema(idSistema) {
  const respuesta = await fetch(
    `/sistemas/${idSistema}/activar`,
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
        "No fue posible activar el sistema.",
    );
  }

  return resultado;
}

async function eliminarSistema(idSistema) {
  const respuesta = await fetch(
    `/sistemas/${idSistema}`,
    {
      method: "DELETE",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    },
  );

  const resultado = await respuesta.json();

  if (!respuesta.ok || !resultado.ok) {
    throw new Error(
      resultado.mensaje ||
        "No fue posible eliminar el sistema.",
    );
  }

  return resultado;
}

/*==================================================*
*=                    MODALES                       =*
*==================================================*/

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

function esperarActualizacionInterfaz() {
  return new Promise((resolve) => {
    requestAnimationFrame(() => {
      requestAnimationFrame(resolve);
    });
  });
}

/*==================================================*
*=             FILAS Y CONTADORES                   =*
*==================================================*/

function obtenerFilaSistema(idSistema) {
  return document.querySelector(
    `tr[data-sistema-id="${idSistema}"]`,
  );
}

function actualizarEstadoFilaSistema(
  idSistema,
  activo,
) {
  const fila = obtenerFilaSistema(idSistema);

  if (!fila) {
    return;
  }

  fila.classList.toggle(
    "sistema-fila--inactiva",
    !activo,
  );
}

function eliminarFilaSistema(idSistema) {
  const fila = obtenerFilaSistema(idSistema);

  if (!fila) {
    return false;
  }

  fila.remove();

  return true;
}

function actualizarTotalSistemasProyecto(
  idProyecto,
  totalSistemas,
) {
  const filaProyecto = document.querySelector(
    `tr[data-proyecto-id="${idProyecto}"]`,
  );

  if (!filaProyecto) {
    return;
  }

  const celdaTotal = filaProyecto.querySelector(
    "[data-total-sistemas]",
  );

  if (!celdaTotal) {
    return;
  }

  celdaTotal.textContent = totalSistemas;
}

function actualizarContadorModal(totalSistemas) {
  const modal = document.getElementById(
    "modal-sistemas-asociados",
  );

  if (!modal) {
    return;
  }

  const contador = modal.querySelector(
    ".tabla-pie__contador",
  );

  if (!contador) {
    return;
  }

  if (totalSistemas <= 0) {
    return;
  }

  contador.innerHTML = `
    Mostrando
    <strong>1</strong>
    a
    <strong>${totalSistemas}</strong>
    de
    <strong>${totalSistemas}</strong>
    registros
  `;
}

function mostrarEstadoVacioSistemas() {
  const modal = document.getElementById(
    "modal-sistemas-asociados",
  );

  if (!modal) {
    return;
  }

  const contenido = modal.querySelector(
    "[data-sistemas-asociados-contenido]",
  );

  if (!contenido) {
    return;
  }

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
}

/*==================================================*
*=          MODAL DE CONFIRMACIÓN                   =*
*==================================================*/

function limpiarEstiloBotonConfirmar(
  botonConfirmar,
) {
  if (!botonConfirmar) {
    return;
  }

  botonConfirmar.classList.remove(
    "boton--primario",
    "boton--advertencia",
    "boton--peligro",
  );
}

function abrirModalConfirmacionSistema({
  accion,
  idSistema,
  idProyecto,
  nombreSistema,
}) {
  const modalConfirmacion = document.getElementById(
    "modal-confirmar-accion-sistema",
  );

  if (!modalConfirmacion) {
    mostrarNotificacion({
      tipo: "error",
      titulo: "No se pudo continuar",
      mensaje:
        "No se encontró el modal de confirmación del sistema.",
    });

    return;
  }

  const titulo = modalConfirmacion.querySelector(
    "[data-confirmacion-sistema-titulo]",
  );

  const mensaje = modalConfirmacion.querySelector(
    "[data-confirmacion-sistema-mensaje]",
  );

  const botonConfirmar = modalConfirmacion.querySelector(
    "[data-confirmar-accion-sistema]",
  );

  modalConfirmacion.dataset.accion = accion;
  modalConfirmacion.dataset.sistemaId = idSistema;
  modalConfirmacion.dataset.proyectoId =
    idProyecto ?? "";
  modalConfirmacion.dataset.sistemaNombre =
    nombreSistema;

  limpiarEstiloBotonConfirmar(botonConfirmar);

  if (accion === "desactivar") {
    if (titulo) {
      titulo.textContent =
        "Confirmar desactivación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas desactivar el sistema "${nombreSistema}"? ` +
        "El sistema conservará su información y podrá reactivarse posteriormente.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent =
        "Desactivar";

      botonConfirmar.classList.add(
        "boton--advertencia",
      );
    }
  }

  if (accion === "activar") {
    if (titulo) {
      titulo.textContent =
        "Confirmar activación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas activar nuevamente el sistema "${nombreSistema}"? ` +
        "El sistema volverá a estar disponible como activo.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent =
        "Activar";

      botonConfirmar.classList.add(
        "boton--primario",
      );
    }
  }

  if (accion === "eliminar") {
    if (titulo) {
      titulo.textContent =
        "Confirmar eliminación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas eliminar definitivamente el sistema "${nombreSistema}"? ` +
        "Esta acción no se podrá deshacer.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent =
        "Eliminar";

      botonConfirmar.classList.add(
        "boton--peligro",
      );
    }
  }

  mostrarModal(modalConfirmacion);
}

/*==================================================*
*=                INICIALIZACIÓN                    =*
*==================================================*/

export function inicializarFormularioEliminarSistema() {
  const modalAdministrar = document.getElementById(
    "modal-eliminar-sistema",
  );

  if (!modalAdministrar) {
    return;
  }

  /*================================================*
  *=      ABRIR MODAL DESDE SISTEMAS ASOCIADOS     =*
  *================================================*/

  document.addEventListener("click", async (event) => {
    const botonAdministrar = event.target.closest(
      '[data-accion="eliminar-sistema"][data-sistema-id]',
    );

    if (!botonAdministrar) {
      return;
    }

    const idSistema =
      botonAdministrar.dataset.sistemaId;

    if (!idSistema) {
      return;
    }

    try {
      const sistema =
        await cargarSistema(idSistema);

      modalAdministrar.dataset.sistemaId =
        String(sistema.id_sistema ?? "");

      modalAdministrar.dataset.sistemaNombre =
        sistema.nombre ?? "";

      modalAdministrar.dataset.proyectoId =
        String(sistema.id_proyecto ?? "");

      const estaActivo =
        sistema.activo !== false;

      modalAdministrar.dataset.sistemaActivo =
        String(estaActivo);

      const nombreSistema =
        modalAdministrar.querySelector(
          "[data-eliminar-sistema-nombre]",
        );

      if (nombreSistema) {
        nombreSistema.textContent =
          `"${sistema.nombre ?? "Sistema"}"`;
      }

      const botonEstado =
        modalAdministrar.querySelector(
          "[data-boton-estado-sistema]",
        );

      if (botonEstado) {
        if (estaActivo) {
          botonEstado.dataset.sistemaAccion =
            "desactivar";

          botonEstado.textContent =
            "Desactivar";
        } else {
          botonEstado.dataset.sistemaAccion =
            "activar";

          botonEstado.textContent =
            "Activar";
        }
      }

      mostrarModal(modalAdministrar);
    } catch (error) {
      console.error(
        "Error al cargar el sistema:",
        error,
      );

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: error.message,
      });
    }
  });

  /*================================================*
  *=         SELECCIONAR ACCIÓN                     =*
  *================================================*/

  document.addEventListener("click", async (event) => {
    const botonAccion = event.target.closest(
      "[data-sistema-accion]",
    );

    if (!botonAccion) {
      return;
    }

    if (!modalAdministrar.contains(botonAccion)) {
      return;
    }

    const accion =
      botonAccion.dataset.sistemaAccion;

    const idSistema =
      modalAdministrar.dataset.sistemaId;

    const nombreSistema =
      modalAdministrar.dataset.sistemaNombre ||
      "el sistema seleccionado";

    const idProyecto =
      modalAdministrar.dataset.proyectoId;

    if (!idSistema) {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo continuar",
        mensaje:
          "No se encontró el identificador del sistema.",
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

    /*
     * Cerramos primero el modal de administración.
     */
    cerrarModal(modalAdministrar);

    /*
     * Esperamos el cambio visual antes de abrir
     * la confirmación.
     */
    await esperarActualizacionInterfaz();

    abrirModalConfirmacionSistema({
      accion,
      idSistema,
      idProyecto,
      nombreSistema,
    });
  });

  /*================================================*
  *=             CONFIRMAR ACCIÓN                   =*
  *================================================*/

  document.addEventListener("click", async (event) => {
    const botonConfirmar = event.target.closest(
      "[data-confirmar-accion-sistema]",
    );

    if (!botonConfirmar) {
      return;
    }

    const modalConfirmacion =
      document.getElementById(
        "modal-confirmar-accion-sistema",
      );

    if (!modalConfirmacion) {
      return;
    }

    const accion =
      modalConfirmacion.dataset.accion;

    const idSistema =
      modalConfirmacion.dataset.sistemaId;

    const idProyecto =
      modalConfirmacion.dataset.proyectoId;

    if (!accion || !idSistema) {
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
      /*============================================*
      *=               DESACTIVAR                  =*
      *============================================*/

      if (accion === "desactivar") {
        const resultado =
          await desactivarSistema(idSistema);

        actualizarEstadoFilaSistema(
          idSistema,
          false,
        );

        mostrarNotificacion({
          tipo: "success",
          titulo: "Sistema desactivado",
          mensaje: resultado.mensaje,
        });
      }

      /*============================================*
      *=                 ACTIVAR                    =*
      *============================================*/

      if (accion === "activar") {
        const resultado =
          await activarSistema(idSistema);

        actualizarEstadoFilaSistema(
          idSistema,
          true,
        );

        mostrarNotificacion({
          tipo: "success",
          titulo: "Sistema activado",
          mensaje: resultado.mensaje,
        });
      }

      /*============================================*
      *=                 ELIMINAR                   =*
      *============================================*/

      if (accion === "eliminar") {
        const resultado =
          await eliminarSistema(idSistema);

        eliminarFilaSistema(idSistema);

        actualizarTotalSistemasProyecto(
          resultado.id_proyecto ?? idProyecto,
          resultado.total_sistemas,
        );

        if (resultado.total_sistemas === 0) {
          mostrarEstadoVacioSistemas();
        } else {
          actualizarContadorModal(
            resultado.total_sistemas,
          );
        }

        mostrarNotificacion({
          tipo: "success",
          titulo: "Sistema eliminado",
          mensaje: resultado.mensaje,
        });
      }

      /*
       * Cerrar confirmación después de ejecutar
       * correctamente la acción.
       */
      cerrarModal(modalConfirmacion);

      /*
       * Limpiar datos temporales.
       */
      modalConfirmacion.removeAttribute(
        "data-accion",
      );

      modalConfirmacion.removeAttribute(
        "data-sistema-id",
      );

      modalConfirmacion.removeAttribute(
        "data-proyecto-id",
      );

      modalConfirmacion.removeAttribute(
        "data-sistema-nombre",
      );
    } catch (error) {
      console.error(
        "Error al ejecutar la acción del sistema:",
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