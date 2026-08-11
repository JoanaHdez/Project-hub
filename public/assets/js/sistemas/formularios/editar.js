import { mostrarNotificacion } from "../../proyectos/notificaciones.js";

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
      resultado.mensaje || "No fue posible cargar el sistema.",
    );
  }

  return resultado.sistema;
}

async function actualizarSistema(idSistema, sistema) {
  const respuesta = await fetch(`/sistemas/${idSistema}`, {
    method: "PUT",
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
      resultado.mensaje || "No fue posible actualizar el sistema.",
    );
  }

  return resultado;
}

function asignarValor(formulario, nombreCampo, valor) {
  const campo = formulario.elements.namedItem(nombreCampo);

  if (!campo) {
    return;
  }

  campo.value = valor ?? "";
}

function llenarFormularioEditar(formulario, sistema) {
  asignarValor(
    formulario,
    "id_proyecto",
    sistema.id_proyecto,
  );

  asignarValor(
    formulario,
    "proyecto_nombre",
    sistema.proyecto_nombre,
  );

  asignarValor(formulario, "nombre", sistema.nombre);
  asignarValor(formulario, "estado", sistema.estado);
  asignarValor(formulario, "tipo", sistema.tipo);

  asignarValor(
    formulario,
    "modo_visualizacion",
    sistema.modo_visualizacion,
  );

  asignarValor(
    formulario,
    "descripcion",
    sistema.descripcion,
  );

  asignarValor(formulario, "url", sistema.url);

  asignarValor(
    formulario,
    "repositorio_url",
    sistema.repositorio_url,
  );

  asignarValor(
    formulario,
    "ruta_local",
    sistema.ruta_local,
  );

  asignarValor(
    formulario,
    "url_servidor",
    sistema.url_servidor,
  );

  asignarValor(
    formulario,
    "responsable",
    sistema.responsable,
  );

  asignarValor(
    formulario,
    "observaciones",
    sistema.observaciones,
  );

  formulario.dataset.sistemaId =
    String(sistema.id_sistema ?? "");
}

function obtenerDatosFormulario(formulario) {
  const datosFormulario = new FormData(formulario);

  return {
    nombre: String(
      datosFormulario.get("nombre") ?? "",
    ).trim(),

    estado: String(
      datosFormulario.get("estado") ?? "",
    ),

    tipo: String(
      datosFormulario.get("tipo") ?? "",
    ),

    modo_visualizacion: String(
      datosFormulario.get("modo_visualizacion") ?? "",
    ),

    descripcion: String(
      datosFormulario.get("descripcion") ?? "",
    ).trim(),

    url: String(
      datosFormulario.get("url") ?? "",
    ).trim(),

    repositorio_url: String(
      datosFormulario.get("repositorio_url") ?? "",
    ).trim(),

    ruta_local: String(
      datosFormulario.get("ruta_local") ?? "",
    ).trim(),

    url_servidor: String(
      datosFormulario.get("url_servidor") ?? "",
    ).trim(),

    responsable: String(
      datosFormulario.get("responsable") ?? "",
    ).trim(),

    observaciones: String(
      datosFormulario.get("observaciones") ?? "",
    ).trim(),
  };
}

function abrirModalEditarSistema() {
  const modal = document.getElementById(
    "modal-editar-sistema",
  );

  if (!modal) {
    return;
  }

  modal.classList.add("modal--visible");
  modal.setAttribute("aria-hidden", "false");

  document.body.style.overflow = "hidden";
}

function cerrarModalEditarSistema() {
  const modal = document.getElementById(
    "modal-editar-sistema",
  );

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

function actualizarFilaSistema(sistema) {
  const fila = document.querySelector(
    `tr[data-sistema-id="${sistema.id_sistema}"]`,
  );

  if (!fila) {
    return false;
  }

  const celdas = fila.querySelectorAll("td");

  if (celdas.length < 3) {
    return false;
  }

  celdas[0].textContent =
    sistema.nombre ?? "Sistema sin nombre";

  celdas[1].textContent =
    sistema.tipo ?? "Sistema";

  celdas[2].innerHTML = `
    <span
      class="estado estado--${sistema.estado_tipo ?? "inactivo"}"
    >
      ${sistema.estado ?? "Sin estado"}
    </span>
  `;

  return true;
}

export function inicializarFormularioEditarSistema() {
  const formulario = document.getElementById(
    "form-editar-sistema",
  );

  if (!formulario) {
    return;
  }

  /*
   * Abrir edición desde la tabla de Sistemas asociados.
   */
  document.addEventListener("click", async (event) => {
    const botonEditar = event.target.closest(
      '[data-accion="editar-sistema"][data-sistema-id]',
    );

    if (!botonEditar) {
      return;
    }

    const idSistema =
      botonEditar.dataset.sistemaId;

    if (!idSistema) {
      return;
    }

    try {
      const sistema =
        await cargarSistema(idSistema);

      llenarFormularioEditar(
        formulario,
        sistema,
      );

      abrirModalEditarSistema();
    } catch (error) {
      console.error(
        "Error al cargar el sistema para editar:",
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
   * Guardar edición.
   */
  formulario.addEventListener(
    "submit",
    async (event) => {
      event.preventDefault();

      if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return;
      }

      const idSistema =
        formulario.dataset.sistemaId;

      if (!idSistema) {
        mostrarNotificacion({
          tipo: "error",
          titulo: "No se pudo actualizar",
          mensaje:
            "No se encontró el identificador del sistema.",
        });

        return;
      }

      const sistema =
        obtenerDatosFormulario(formulario);

      try {
        const resultado =
          await actualizarSistema(
            idSistema,
            sistema,
          );

        const filaActualizada =
          actualizarFilaSistema(
            resultado.sistema,
          );

        if (!filaActualizada) {
          console.warn(
            `El sistema ${idSistema} se actualizó, pero no se encontró su fila en la tabla.`,
          );
        }

        cerrarModalEditarSistema();

        mostrarNotificacion({
          tipo: "success",
          titulo: "Sistema actualizado",
          mensaje: resultado.mensaje,
        });
      } catch (error) {
        console.error(
          "Error al actualizar el sistema:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",
          titulo: "No se pudo actualizar",
          mensaje: error.message,
        });
      }
    },
  );
}