import {
  inicializarSeleccionApi,
} from "./documentacion/seleccion.js";

import {
  inicializarCopiarEjemplo,
} from "./documentacion/ejemplo.js";

import {
  inicializarEditarApi,
} from "./acciones/editar.js";

import {
  inicializarEstadoApi,
} from "./acciones/estado.js";

import {
  inicializarAdministrarApi,
} from "./acciones/administrar.js";

/*==================================================
=              DOCUMENTACIÓN DE APIs                =
==================================================*/

inicializarSeleccionApi();

inicializarCopiarEjemplo();

inicializarEditarApi();

inicializarEstadoApi();

inicializarAdministrarApi();