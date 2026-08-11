import { inicializarFormularioNuevoSistema } from "./formularios/nuevo.js";
import { inicializarFormularioDetalleSistema } from "./formularios/detalle.js";
import { inicializarFormularioEditarSistema } from "./formularios/editar.js";
import { inicializarFormularioEliminarSistema } from "./formularios/eliminar.js";

export function inicializarFormularioSistema() {
  inicializarFormularioNuevoSistema();
  inicializarFormularioDetalleSistema();
  inicializarFormularioEditarSistema();
  inicializarFormularioEliminarSistema();
}