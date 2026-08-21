import { inicializarFormularioProyecto } from "./formulario.js";
import { inicializarTablaProyectos } from "./tabla.js";
import { inicializarSistemasAsociados } from "./sistemas_asociados.js";
import { inicializarNuevaFichaTecnica, } from "./ficha_tecnica/nueva.js";
import { inicializarVerFichaTecnica, } from "./ficha_tecnica/ver.js";

function inicializarModuloProyectos() {
  inicializarFormularioProyecto();
  inicializarTablaProyectos();
  inicializarSistemasAsociados();
  inicializarNuevaFichaTecnica();
  inicializarVerFichaTecnica();
}

document.addEventListener("DOMContentLoaded", inicializarModuloProyectos);