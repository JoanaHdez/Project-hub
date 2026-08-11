import { inicializarFormularioNuevoSistema } from "./formularios/nuevo.js";
import { inicializarFormularioDetalleSistema } from "./formularios/detalle.js";
import { inicializarFormularioEditarSistema } from "./formularios/editar.js";

export function inicializarFormularioSistema() {
  inicializarFormularioNuevoSistema();
  inicializarFormularioDetalleSistema();
  inicializarFormularioEditarSistema();
}