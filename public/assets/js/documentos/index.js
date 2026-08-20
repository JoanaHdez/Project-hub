import {
    inicializarNuevoDocumento,
} from "./nuevo.js";

import {
    inicializarEliminarDocumento,
} from "./eliminar.js";


/*==================================================*
*=                  DOCUMENTOS                     =*
*==================================================*/

document.addEventListener(
    "DOMContentLoaded",
    () => {

        inicializarNuevoDocumento();

        inicializarEliminarDocumento();

    },
);