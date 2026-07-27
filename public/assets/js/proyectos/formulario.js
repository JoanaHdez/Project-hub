import { inicializarFormularioNuevo } from "./formularios/nuevo.js";
import { inicializarFormularioEditar } from "./formularios/editar.js";
import { inicializarFormularioEliminar } from "./formularios/eliminar.js";
import { inicializarFormularioDetalle } from "./formularios/detalle.js";

export function inicializarFormularioProyecto() {
    inicializarFormularioNuevo();
    inicializarFormularioEditar();
    inicializarFormularioEliminar();
    inicializarFormularioDetalle();
}
