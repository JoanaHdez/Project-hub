import { validarFormulario } from "../validaciones.js";
import { mostrarNotificacion } from "../notificaciones.js";
import { obtenerDatosFormulario } from "../datos_Formulario.js";
import { actualizarProyectoEnTabla } from "../tabla.js";

console.log("editar.js cargado");

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

function llenarFormularioEditar(formulario, proyecto, idProyecto) {
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

function cerrarModalEditarProyecto() {
  const modal = document.getElementById("modal-editar-proyecto");

  if (!modal) {
    return;
  }

  const botonCerrar = modal.querySelector("[data-modal-cerrar]");

  if (botonCerrar) {
    botonCerrar.click();
  }
}

async function actualizarProyecto(idProyecto, proyecto) {
  const respuesta = await fetch(`/proyectos/${idProyecto}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify(proyecto),
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
      resultado.mensaje || "No fue posible actualizar el proyecto.",
    );
  }

  return resultado;
}

export function inicializarFormularioEditar() {
  console.log("inicializarFormularioEditar()");
  const formulario = document.getElementById("form-editar-proyecto");

  if (!formulario) {
    return;
  }

  console.log("Listener de editar registrado");
  
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
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: "El botón no contiene el identificador del proyecto.",
      });

      return;
    }

    try {
      const proyecto = await cargarProyecto(idProyecto);

      llenarFormularioEditar(
        formulario,
        proyecto,
        idProyecto,
      );
    } catch (error) {
      console.error("Error al cargar el proyecto:", error);

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo cargar",
        mensaje: error.message,
      });
    }
  });

  formulario.addEventListener("submit", async (event) => {
    event.preventDefault();

    if (!validarFormulario(formulario)) {
      return;
    }

    const idProyecto = formulario.dataset.proyectoId;

    if (!idProyecto) {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo actualizar",
        mensaje: "No se encontró el identificador del proyecto.",
      });

      return;
    }

    const proyecto = obtenerDatosFormulario(formulario);

    try {
      const resultado = await actualizarProyecto(
        idProyecto,
        proyecto,
      );

      const filaActualizada = actualizarProyectoEnTabla(
        idProyecto,
        resultado.fila_html,
      );

      if (!filaActualizada) {
        console.warn(
          `No se encontró la fila del proyecto ${idProyecto} en la tabla.`,
        );
      }

      mostrarNotificacion({
        tipo: "success",
        titulo: "Proyecto actualizado",
        mensaje: resultado.mensaje,
      });

      cerrarModalEditarProyecto();
    } catch (error) {
      console.error("Error al actualizar el proyecto:", error);

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo actualizar",
        mensaje: error.message,
      });
    }
  });
}