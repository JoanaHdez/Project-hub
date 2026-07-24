import { validarFormulario } from "./validaciones.js";
import { mostrarNotificacion } from "./notificaciones.js";
import { agregarProyectoATabla } from "./tabla.js";

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
    id_especificacion: String(datosFormulario.get("id_especificacion") ?? ""),
    responsable: String(datosFormulario.get("responsable") ?? "").trim(),
    observaciones: String(datosFormulario.get("observaciones") ?? "").trim(),
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

export function inicializarFormularioProyecto() {
  const formulario = document.getElementById("form-proyecto");

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!validarFormulario(formulario)) {
      return;
    }

    const proyecto = construirProyecto(formulario);

    agregarProyectoATabla(proyecto);

    console.log("Proyecto capturado:");
    console.table(proyecto);

    mostrarNotificacion({
      tipo: "success",
      titulo: "Proyecto registrado",
      mensaje: "El proyecto se registró correctamente.",
    });

    cerrarModalNuevoProyecto();

    formulario.reset();
  });
}
