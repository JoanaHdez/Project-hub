import { validarFormulario } from "../validaciones.js";
import { mostrarNotificacion } from "../notificaciones.js";
import { agregarProyectoATabla } from "../tabla.js";

function construirProyecto(formulario) {
  const datosFormulario = new FormData(formulario);

  return {
    nombre: String(datosFormulario.get("nombre") ?? "").trim(),
    estado: String(datosFormulario.get("estado") ?? ""),
    origen: String(datosFormulario.get("origen") ?? ""),
    descripcion: String(datosFormulario.get("descripcion") ?? "").trim(),
    repositorio_url: String(
      datosFormulario.get("repositorio_url") ?? "",
    ).trim(),
    ruta_local: String(datosFormulario.get("ruta_local") ?? "").trim(),
    url_servidor: String(datosFormulario.get("url_servidor") ?? "").trim(),
    id_especificacion: String(
      datosFormulario.get("id_especificacion") ?? "",
    ),
    responsable: String(datosFormulario.get("responsable") ?? "").trim(),
    observaciones: String(
      datosFormulario.get("observaciones") ?? "",
    ).trim(),
  };
}

function cerrarModalNuevoProyecto() {
  const modal = document.getElementById("modal-nuevo-proyecto");

  if (!modal) {
    return;
  }

  const botonCerrar = modal.querySelector("[data-modal-cerrar]");

  if (botonCerrar) {
    botonCerrar.click();
  }
}

export function inicializarFormularioNuevo() {
  console.log("Inicializando formulario nuevo");

  const formulario = document.getElementById("form-proyecto");

  console.log(formulario);

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", async (event) => {
    event.preventDefault();

    console.log("Entró al submit");

    if (!validarFormulario(formulario)) {
      return;
    }

    const proyecto = construirProyecto(formulario);

    try {
      const respuesta = await fetch("/proyectos", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(proyecto),
      });

      const resultado = await respuesta.json();

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
        mensaje: error.message,
      });
    }
  });
}