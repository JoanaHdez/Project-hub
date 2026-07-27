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

function llenarFormularioEditar(formulario, proyecto) {
  formulario.elements.nombre.value = proyecto.nombre ?? "";
  formulario.elements.estado.value = proyecto.estado ?? "";
  formulario.elements.origen.value = proyecto.origen ?? "";
  formulario.elements.descripcion.value = proyecto.descripcion ?? "";
  formulario.elements.repositorio_url.value =
    proyecto.repositorio_url ?? "";
  formulario.elements.ruta_local.value = proyecto.ruta_local ?? "";
  formulario.elements.url_servidor.value = proyecto.url_servidor ?? "";
  formulario.elements.id_especificacion.value =
    proyecto.id_especificacion ?? "";
  formulario.elements.responsable.value = proyecto.responsable ?? "";
  formulario.elements.observaciones.value =
    proyecto.observaciones ?? "";

  formulario.dataset.proyectoId = proyecto.id_proyecto;
}

export function inicializarFormularioEditar() {
  const formulario = document.getElementById("form-editar-proyecto");

  if (!formulario) {
    return;
  }

  document.addEventListener("click", async (event) => {
    const botonEditar = event.target.closest(
      '[data-accion="editar"][data-proyecto-id]',
    );

    if (!botonEditar) {
      return;
    }

    event.preventDefault();

    const idProyecto = botonEditar.dataset.proyectoId;

    if (!idProyecto) {
      return;
    }

    try {
      const proyecto = await cargarProyecto(idProyecto);

      llenarFormularioEditar(formulario, proyecto);

      console.log("Proyecto cargado para edición:", proyecto);
    } catch (error) {
      console.error("Error al cargar el proyecto:", error);

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: error.message,
      });
    }
  });

  formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    console.log(
      "Formulario de edición listo para guardar:",
      formulario.dataset.proyectoId,
    );
  });
}