import { inicializarFormularioNuevoSistema } from "./formularios/nuevo.js";
import { inicializarFormularioDetalleSistema } from "./formularios/detalle.js";

export function inicializarFormularioSistema() {
  inicializarFormularioNuevoSistema();
  inicializarFormularioDetalleSistema();
}