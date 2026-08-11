import { inicializarFormularioProyecto } from "./formulario.js";
import { inicializarTablaProyectos } from "./tabla.js";
import { inicializarSistemasAsociados } from "./sistemas_asociados.js";

function inicializarModuloProyectos() {
  inicializarFormularioProyecto();
  inicializarTablaProyectos();
  inicializarSistemasAsociados();
}

document.addEventListener("DOMContentLoaded", inicializarModuloProyectos);