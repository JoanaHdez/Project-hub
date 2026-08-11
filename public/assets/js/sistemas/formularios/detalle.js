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

function asignarValor(formulario, nombreCampo, valor) {
  const campo = formulario.elements.namedItem(nombreCampo);

  if (!campo) {
    return;
  }

  campo.value = valor ?? "";
}

function llenarFormularioDetalle(formulario, sistema) {
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

function abrirModalDetalleSistema() {
  const modal = document.getElementById(
    "modal-detalle-sistema",
  );

  if (!modal) {
    return;
  }

  modal.classList.add("modal--visible");
  modal.setAttribute("aria-hidden", "false");

  document.body.style.overflow = "hidden";
}

export function inicializarFormularioDetalleSistema() {
  const formulario = document.getElementById(
    "form-detalle-sistema",
  );

  if (!formulario) {
    return;
  }

  document.addEventListener("click", async (event) => {
    const botonDetalle = event.target.closest(
      '[data-accion="detalle-sistema"][data-sistema-id]',
    );

    if (!botonDetalle) {
      return;
    }

    const idSistema =
      botonDetalle.dataset.sistemaId;

    if (!idSistema) {
      return;
    }

    try {
      const sistema =
        await cargarSistema(idSistema);

      llenarFormularioDetalle(
        formulario,
        sistema,
      );

      abrirModalDetalleSistema();
    } catch (error) {
      console.error(
        "Error al cargar el detalle del sistema:",
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