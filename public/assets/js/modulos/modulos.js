import {
    inicializarSeleccionSistema,
} from "./sistemas/seleccion.js";

import {
    inicializarModalNuevoModulo,
} from "./modulos/modal.js";

import {
    inicializarNuevoModulo,
} from "./modulos/nuevo.js";

import {
    inicializarDetalleModulo,
} from "./modulos/detalle.js";

import {
    inicializarEditarModulo,
} from "./modulos/editar.js";

import {
    inicializarEliminarModulo,
} from "./modulos/eliminar.js";

import {
    inicializarEstadoModulo,
} from "./modulos/estado.js";

import {
    inicializarImagenModulo,
} from "./modulos/imagen.js";

/*==================================================*
*=                  MÓDULOS                        =*
*==================================================*/

document.addEventListener(
    "DOMContentLoaded",
    () => {
        inicializarSeleccionSistema();

        inicializarNuevoModulo();

        inicializarDetalleModulo();

        inicializarModalNuevoModulo();

        inicializarEditarModulo();

        inicializarEliminarModulo();

        inicializarEstadoModulo();

        inicializarImagenModulo();
    },
);