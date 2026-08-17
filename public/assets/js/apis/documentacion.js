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

import {
  inicializarConfirmacionAccionApi,
} from "./acciones/administrar/ejecutar.js";

import {
  inicializarArquitecturaFicha,
} from "./ficha/arquitectura.js";

import {
    inicializarDependenciasFicha,
} from "./ficha/dependencias.js";

/*==================================================
=              DOCUMENTACIÓN DE APIs                =
==================================================*/

inicializarSeleccionApi();

inicializarCopiarEjemplo();

inicializarEditarApi();

inicializarEstadoApi();

inicializarAdministrarApi();

inicializarConfirmacionAccionApi();

inicializarArquitecturaFicha();

inicializarDependenciasFicha();