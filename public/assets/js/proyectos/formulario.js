import { inicializarFormularioNuevo } from "./formularios/nuevo.js";
import { inicializarFormularioEditar } from "./formularios/editar.js";
import { inicializarFormularioEliminar } from "./formularios/eliminar.js";

export function inicializarFormularioProyecto() {
    inicializarFormularioNuevo();
    inicializarFormularioEditar();
    inicializarFormularioEliminar();
}