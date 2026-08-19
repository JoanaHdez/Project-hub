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
    },
);