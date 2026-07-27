import { validarFormulario } from "../validaciones.js";
import { mostrarNotificacion } from "../notificaciones.js";
import { agregarProyectoATabla } from "../tabla.js";
import { obtenerDatosFormulario } from "../datos_Formulario.js";

function cerrarModalNuevoProyecto() {
  const modal = document.getElementById("modal-nuevo-proyecto");

  if (!modal) {
    return;
  }

  const botonCerrar = modal.querySelector("[data-modal-cerrar]");

  if (botonCerrar) {
    botonCerrar.focus();
    botonCerrar.click();
  }
}

export function inicializarFormularioNuevo() {
  const formulario = document.getElementById("form-proyecto");

  console.log("Formulario nuevo encontrado:", formulario);

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", async (event) => {
    event.preventDefault();

    console.log("Entró al submit de nuevo proyecto");

    if (!validarFormulario(formulario)) {
      console.log("El formulario no superó la validación.");
      return;
    }

    const proyecto = obtenerDatosFormulario(formulario);

    console.log("Proyecto obtenido del formulario:", proyecto);

    if (!proyecto || typeof proyecto !== "object") {
      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo registrar",
        mensaje: "No fue posible obtener los datos del formulario.",
      });

      console.error(
        "obtenerDatosFormulario() no devolvió un objeto:",
        proyecto,
      );

      return;
    }

    const cuerpoPeticion = JSON.stringify(proyecto);

    console.log("JSON que se enviará:", cuerpoPeticion);

    try {
      const respuesta = await fetch("/proyectos", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: cuerpoPeticion,
      });

      const contenido = await respuesta.text();

      console.log("Código HTTP:", respuesta.status);
      console.log("Respuesta completa del servidor:", contenido);

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
          resultado.mensaje || "No fue posible registrar el proyecto.",
        );
      }

      agregarProyectoATabla(resultado.fila_html);

      mostrarNotificacion({
        tipo: "success",
        titulo: "Proyecto registrado",
        mensaje: resultado.mensaje,
      });

      cerrarModalNuevoProyecto();
      formulario.reset();
    } catch (error) {
      console.error("Error al registrar el proyecto:", error);

      mostrarNotificacion({
        tipo: "error",
        titulo: "No se pudo registrar",
        mensaje: error.message || String(error),
      });
    }
  });
}