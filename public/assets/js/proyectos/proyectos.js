import { inicializarFormularioProyecto } from "./formulario.js";
import { inicializarTablaProyectos } from "./tabla.js";

export function inicializarProyectos() {
  inicializarFormularioProyecto();
  inicializarTablaProyectos();
}

document.addEventListener("DOMContentLoaded", () => {
  inicializarProyectos();
});