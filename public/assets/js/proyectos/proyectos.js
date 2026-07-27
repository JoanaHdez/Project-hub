import { inicializarFormularioProyecto } from "./formulario.js";
import { inicializarTablaProyectos } from "./tabla.js";

function inicializarModuloProyectos() {
  inicializarFormularioProyecto();
  inicializarTablaProyectos();
}

document.addEventListener("DOMContentLoaded", inicializarModuloProyectos);