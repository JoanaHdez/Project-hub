import {
    inicializarSeleccionSistema,
} from "./sistemas/seleccion.js";

import {
    inicializarModalNuevoModulo,
} from "./modulos/modal.js";

import {
    inicializarNuevoModulo,
} from "./modulos/nuevo.js";


/*==================================================*
*=                  MÓDULOS                        =*
*==================================================*/

document.addEventListener(
    "DOMContentLoaded",
    () => {

        inicializarSeleccionSistema();

        inicializarModalNuevoModulo();

        inicializarNuevoModulo();
    },
);