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

function asignarValor(formulario, nombreCampo, valor) {
  const campo = formulario.elements.namedItem(nombreCampo);

  if (!campo) {
    return;
  }

  campo.value = valor ?? "";
}

function llenarFormularioDetalle(formulario, proyecto, idProyecto) {
  asignarValor(formulario, "nombre", proyecto.nombre);
  asignarValor(formulario, "estado", proyecto.estado);
  asignarValor(formulario, "origen", proyecto.origen);
  asignarValor(formulario, "descripcion", proyecto.descripcion);
  asignarValor(formulario, "repositorio_url", proyecto.repositorio_url);
  asignarValor(formulario, "ruta_local", proyecto.ruta_local);
  asignarValor(formulario, "url_servidor", proyecto.url_servidor);
  asignarValor(
    formulario,
    "id_especificacion",
    proyecto.id_especificacion,
  );
  asignarValor(formulario, "responsable", proyecto.responsable);
  asignarValor(formulario, "observaciones", proyecto.observaciones);

  formulario.dataset.proyectoId = String(idProyecto);
}

export function inicializarFormularioDetalle() {
  const formulario = document.getElementById("form-detalle-proyecto");

  if (!formulario) {
    return;
  }

  document.addEventListener("click", async (event) => {
    const botonDetalle = event.target.closest(
      '[data-accion="detalle"][data-proyecto-id]',
    );

    if (!botonDetalle) {
      return;
    }

    event.preventDefault();

    const idProyecto = botonDetalle.dataset.proyectoId;

    if (!idProyecto) {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: "El botón no contiene el identificador del proyecto.",
      });

      return;
    }

    try {
      const proyecto = await cargarProyecto(idProyecto);

      llenarFormularioDetalle(
        formulario,
        proyecto,
        idProyecto,
      );
    } catch (error) {
      console.error("Error al cargar el detalle del proyecto:", error);

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: error.message,
      });
    }
  });
}
